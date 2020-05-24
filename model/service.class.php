<?php

require_once "db.class.php";
require_once "user.class.php";
require_once "teacher.class.php";
require_once "student.class.php";
require_once "subject.class.php";
require_once "exam.class.php";
require_once "examtaken.class.php";
require_once "studentsubject.class.php";
require_once "subjectteaching.class.php";
use Ispitomat\User;
use Ispitomat\Teacher;
use Ispitomat\Student;
use Ispitomat\Subject;
use Ispitomat\Exam;
use Ispitomat\ExamTaken;
use Ispitomat\StudentSubject;
use Ispitomat\SubjectTeaching;

class Service
{
	function getUserTypeByUserID($userID)
	{
		// funkcija na temelju userIDja vraća string Teacher ili Student, ovisno o tome je li korisnik s danim userIDjem nastavnik ili student
		// (ako korisnik s danim userIDjem)
		try
		{
			$em = DB::getConnection();
			$query = $em->createQuery("MATCH (user:User) WHERE user.userID={userID} RETURN labels(user) AS labels");
			$query->setParameter("userID", $userID);
			$result = $query->execute()[0];
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }

		if ($result === null)
			return null;
		foreach ($result["labels"] as $label) {
			if (strcmp($label, "Teacher") === 0)
				return "Teacher";
			else if (strcmp($label, "Student") === 0)
				return "Student";
		}
	}

	function getExamsTakenByStudent($userID)
	{
		try
		{
			$em = DB::getConnection();
			/*$query = $em->createQuery("MATCH (st:Student)-[t:TAKES]->(e:Exam)-[:IN]->(s:Subject)
																 WHERE st.userID={userID}
																 WITH t, e, s
																 MATCH (:Student)-[tOthers:TAKES]->(eOthers:Exam)
																 WHERE ID(eOthers)=ID(e)
																 RETURN t, e, s, AVG(tOthers.score) as avgScore");*/
			$studentsRepository = $em->getRepository(\Ispitomat\Student::class);
			$student = $studentsRepository->findOneBy(["userID" => $userID]);
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }

		$examsData = array();
		$examsTaken = $student->examsTaken;
		$examsRegisteredFor = $student->examsRegisteredFor;
		foreach ($examsTaken as $examTaken) {
			$avgScore = 0;
			$cnt = 0;
			$students = array();
			foreach ($examTaken->exam->studentsTakenBy as $et) {
				if (!isset($students[$et->student->userID])) {
					$cnt += 1;
					$avgScore += $et->score;
					$students[$et->student->userID] = true;
				}
			}
			$ssubjects = $student->subjects;
			foreach ($ssubjects as $ssubject) {
				if (strcmp($ssubject->subject->subjectID, $examTaken->exam->subject[0]->subjectID) === 0) {
					$ss = $ssubject;
					break;
				}
			}
			$examsData[] = ["examTaken" => $examTaken, "exam" => $examTaken->exam, "subject" => $examTaken->exam->subject[0],
											"avgScore" => $avgScore / $cnt, "date" => $examTaken->exam->date, "subjectPassed" => ($ss->grade !== null)];
		}
		foreach ($examsRegisteredFor as $examRegisteredFor) {
			date_default_timezone_set("Europe/Zagreb");
			$d1 = date("Y-m-d", strtotime($examRegisteredFor->date));
			$d2 = date("Y-m-d");
			if (strcmp($examRegisteredFor->type, "written") === 0) {
				$t1 = substr($examRegisteredFor->time, 0, 5);
				$t2 = date("H:i", time());
				$t1 = intval(substr($t1, 0, 2)) * 60 + intval(substr($t1, 3, 2)) + $examRegisteredFor->duration;
				$t2 = intval(substr($t2, 0, 2)) * 60 + intval(substr($t2, 3, 2));
				if ($d1 < $d2 || (strcmp($d1, $d2) === 0 && $t2 >= $t1)) {
					$examsData[] = ["exam" => $examRegisteredFor, "subject" => $examRegisteredFor->subject[0], "date" => $examTaken->exam->date];
				}
			}
			else {
				if ($d1 <= $d2) {
					$examsData[] = ["exam" => $examRegisteredFor, "subject" => $examRegisteredFor->subject[0]];
				}
			}
		}
		return $examsData;
	}

	function getExamsAvailableToStudent($userID)
	{
		try
		{
			$em = DB::getConnection();
			// Smatramo da se student mora prijaviti za ispit najkasnije dan ranije
			/*$query = $em->createQuery("MATCH (student:Student {userID:{userID}})-[:ENROLLED_IN]->(subject:Subject)<-[:IN]-(exam:Exam)
																 WHERE size((student)-[:REGISTERED_FOR]->(:Exam)-[:IN]->(subject))=0
																 AND size((student)-[:TAKEN {passed:true}]->(:Exam)-[:IN]->(subject))=0
																 AND date(exam.date)>date()
																 RETURN exam, subject");
			$query->addEntityMapping("exam", \Ispitomat\Exam::class)
						->addEntityMapping("subject", \Ispitomat\Subject::class);
			$query->setParameter("userID", $userID);
			$results = $query->execute();*/
			$studentsRepository = $em->getRepository(\Ispitomat\Student::class);
			$student = $studentsRepository->findOneBy(["userID" => $userID]);
			$subjects = $student->subjects;
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }

		$examsData = array();
		$d2 = date("Y-m-d");
		$currYear = substr($d2, 0, 4);
		$currMonth = substr($d2, 5, 2);
		if (intval($currMonth) > 9)
			$currSchoolYear = $currYear . "./" . $currYear + 1;
		else
			$currSchoolYear = $currYear - 1 . "./" . $currYear;
		foreach ($subjects as $ss) {
			if ($ss->grade !== null)
				continue;
			$available = 1; // 0 = nisu dostupni ni pismeni ni usmeni, 1 = dostupni su samo pismeni, 2 = dostupni su samo usmeni
			foreach ($student->examsRegisteredFor as $examRegisteredFor) {
				if (strcmp($examRegisteredFor->subject[0]->subjectID, $ss->subject->subjectID) === 0) {
					$available = 0;
					break;
				}
			}
			if ($available === 0)
				continue;
			$timesFailedWritten = 0;
			$timesFailedOral = 0;
			foreach ($student->examsTaken as $et) {
				if (strcmp($et->exam->subject[0]->subjectID, $ss->subject->subjectID) === 0) {
					// Ako postoji obavljen ispit iz kojeg je student dobio ocjenu, ali je nije odbio, ne može prijaviti novi ispit iz danog predmeta
					// Da bi prijavio novi ispit iz danog predmeta, student prvo treba odbiti ocjenu
					if (strcmp($et->exam->type, "oral") === 0 && $et->grade !== null) {
						$available = 0;
						break;
					}
					else if (strcmp($et->exam->type, "written") === 0 && $et->grade !== null) {
						$available = 0;
						break;
					}
					else if (strcmp($et->exam->type, "written") === 0 && $et->passed && $et->exam->subject[0]->oralExam) {
						$available = 2;
					}
					else if (strcmp($et->exam->type, "written") === 0 && !$et->passed) {
						if (strcmp($et->exam->schoolYear, $currSchoolYear) === 0)
							$timesFailedWritten += 1;
					}
					else if (strcmp($et->exam->type, "oral") === 0 && !$et->passed) {
						if (strcmp($et->exam->schoolYear, $currSchoolYear) === 0)
							$timesFailedOral += 1;
					}
				}
			}
			if ($available === 0)
				continue;
			// Pretpostavljamo da se ispit može polagati najviše 4 puta
			else if ($available === 1 && $timesFailedWritten >= 4)
				continue;
			else if ($available === 2 && $timesFailedOral >= 4)
				continue;
			foreach ($ss->subject->exams as $exam) {
				$d1 = date("Y-m-d", strtotime($exam->date));
				if ($d1 > $d2) {
					if ($available === 1 && strcmp($exam->type, "written") === 0)
						$examsData[] = ["exam" => $exam, "subject" => $exam->subject[0], "date" => $exam->date];
					else if ($available === 2 && strcmp($exam->type, "oral") === 0)
						$examsData[] = ["exam" => $exam, "subject" => $exam->subject[0], "date" => $exam->date];
				}
			}
		}
		//return $results;
		return $examsData;
	}

	function registerStudentForExam($userID, $examID)
	{
		try
		{
			$em = DB::getConnection();
			$studentsRepository = $em->getRepository(\Ispitomat\Student::class);
			$student = $studentsRepository->findOneBy(["userID" => $userID]);

			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
			$exam = $examsRepository->find($examID);

			$student->examsRegisteredFor->add($exam);
			$exam->studentsRegistered->add($student);
			$em->flush();
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }
	}

	function getExamsStudentIsRegisteredFor($userID)
	{
		try
		{
			$em = DB::getConnection();
			$studentsRepository = $em->getRepository(\Ispitomat\Student::class);
			$student = $studentsRepository->findOneBy(["userID" => $userID]);
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }

		$exams = array();
		foreach ($student->examsRegisteredFor as $examRegisteredFor) {
			date_default_timezone_set("Europe/Zagreb");
			$d1 = date("Y-m-d", strtotime($examRegisteredFor->date));
			$d2 = date("Y-m-d");
			if (strcmp($examRegisteredFor->type, "written") === 0) {
				$t1 = substr($examRegisteredFor->time, 0, 5);
				$t2 = date("H:i", time());
				$t1 = intval(substr($t1, 0, 2)) * 60 + intval(substr($t1, 3, 2)) + $examRegisteredFor->duration;
				$t2 = intval(substr($t2, 0, 2)) * 60 + intval(substr($t2, 3, 2));
				// Prijavljenim pismenim ispitima smatramo one čiji je datum održavanja kasniji od trenutnog datuma ili
				// je jednak trenutnome, ali je vrijeme završetka ispita (početak + trajanje) manje od trenutnog vremena
				if ($d1 > $d2 || (strcmp($d1, $d2) === 0 && $t2 < $t1))
					$exams[] = ["exam" => $examRegisteredFor, "deregister" => ($d1 > $d2), "date" => $examRegisteredFor->date];
			}
			else {
				if ($d1 > $d2)
					$exams[] = ["exam" => $examRegisteredFor, "deregister" => true, "date" => $examRegisteredFor->date];
			}
		}
		return $exams;
	}

	function deregisterStudentForExam($userID, $examID)
	{
		try
		{
			$em = DB::getConnection();
			$studentsRepository = $em->getRepository(\Ispitomat\Student::class);
			$student = $studentsRepository->findOneBy(["userID" => $userID]);

			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
			$exam = $examsRepository->find($examID);

			$student->examsRegisteredFor->removeElement($exam);
			$exam->studentsRegistered->removeElement($student);
			$em->flush();
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }
	}

	function acceptExamGrade($userID, $examID)
	{
		try
		{
			$em = DB::getConnection();
			$studentsRepository = $em->getRepository(\Ispitomat\Student::class);
			$student = $studentsRepository->findOneBy(["userID" => $userID]);

			$examsTaken = $student->examsTaken;
			foreach ($examsTaken as $examTaken) {
				if ($examTaken->exam->id === intval($examID)) {
					$examGrade = $examTaken->grade;
					$et = $examTaken;
					break;
				}
			}
			$ssubjects = $student->subjects;
			foreach ($ssubjects as $ssubject) {
				if (strcmp($ssubject->subject->subjectID, $et->exam->subject[0]->subjectID) === 0) {
					$ssubject->grade = $examGrade;
					break;
				}
			}

			$em->flush();
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }
	}

	function rejectExamGrade($userID, $examID)
	{
		try
		{
			$em = DB::getConnection();
			$studentsRepository = $em->getRepository(\Ispitomat\Student::class);
			$student = $studentsRepository->findOneBy(["userID" => $userID]);

			$examsTaken = $student->examsTaken;
			foreach ($examsTaken as $examTaken) {
				if ($examTaken->exam->id === intval($examID)) {
					$examTaken->grade = null;
					break;
				}
			}
			$em->flush();
		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }
	}

 	function getSubjectsByUserID($userID)
 	{
 		try
 		{
 			$client = DB::getClient();
 			$query = "MATCH (p:Teacher {userID:'" . $userID . "'})-[t:TEACHES]->(subject:Subject)
 								RETURN subject";
 			$results = $client->run($query)->getRecords();
 		}
 		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

 		$arr = array();
 		foreach ($results as $result) {
 			$subject = $result->value("subject");
 			$arr[] = ["subjectID" => $subject->get("subjectID"), "subjectName" => $subject->get("subjectName"), "semester" => $subject->get("semester")];
 		}
 		return $arr;
 	}

 	function getExamsTakenFromSubject($subjectID)
 	{
 		try
 		{
 			$em = DB::getConnection();
			$subjectRepository = $em->getRepository(\Ispitomat\Subject::class);
 			$subject = $subjectRepository->findOneBy(["subjectID" => $subjectID]);
 		}
 		catch(Exception $e) { exit("Error " . $e->getMessage()); }

		$examsData = array();
 		$exams = $subject->exams;

 		foreach ($exams as $exam) {
			if (!$exam->studentsTakenBy->isEmpty()) {
 				$cnt = 0;
 				$avgScore = 0;
 				$students = array();
 				foreach ($exam->studentsTakenBy as $et) {
 					if (!isset($students[$et->student->userID])) {
 						$cnt += 1;
 						$avgScore += $et->score;
 						$students[$et->student->userID] = true;
 					}
 				}
 				$examsData[] = ["exam" => $exam, "date" => $exam->date, "subject" => $exam->subject[0],"avgScore" => $avgScore / $cnt];
 			}
 			else {
				date_default_timezone_set("Europe/Zagreb");
 				$d1 = date("Y-m-d", strtotime($exam->date));
 				$d2 = date("Y-m-d");
 				if (strcmp($exam->type, "written") === 0) {
 					$t1 = substr($exam->time, 0, 5);
 					$t2 = date("H:i", time());
 					$t1 = intval(substr($t1, 0, 2)) * 60 + intval(substr($t1, 3, 2)) + $exam->duration;
 					$t2 = intval(substr($t2, 0, 2)) * 60 + intval(substr($t2, 3, 2));
 					if ($d1 < $d2 || (strcmp($d1, $d2) === 0 && $t2 >= $t1)) {
 						$examsData[] = ["exam" => $exam, "date" => $exam->date, "subject" => $exam->subject[0]];
 					}
 				}
 				else {
 					if ($d1 <= $d2) {
 						$examsData[] = ["exam" => $exam, "date" => $exam->date, "subject" => $exam->subject[0]];
 					}
 				}
 			}
		}
 		return $examsData;
 	}

 	function getExamsAvailableFromSubject($subjectID)
 	{
		try
 		{
 			$em = DB::getConnection();
 			$query = $em->createQuery("MATCH (subject:Subject {subjectID:{subjectID}})<-[:IN]-(exam:Exam)
 																 WHERE date(exam.date)>date()
 																 RETURN exam, subject, exam.date AS date");
 			$query->addEntityMapping("exam", \Ispitomat\Exam::class)
 						->addEntityMapping("subject", \Ispitomat\Subject::class);
 			$query->setParameter("subjectID", $subjectID);
 			$results = $query->execute();
 		}
 		catch(Exception $e) { exit("Error " . $e->getMessage()); }

 		return $results;
 	}

	function insertWrittenExam($subjectID, $date, $time, $duration, $location, $max)
 	{
 		try
 		{
 			$em = DB::getConnection();
 			$exam = new Exam();

 			$exam->__set("date",$date);
 			$exam->__set("type","written");
 			$exam->__set("time",$time);
 			$exam->__set("duration",$duration);
 			$exam->__set("location",$location);
 			$exam->__set("maxScore",$max);
 			$exam->__set("schoolYear","2019./20.");

			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
 			$exams = $examsRepository->findBy(["date" => $date, "location" => $location]);

 			foreach ($exams as $e) {
 				$t1 = substr($e->time, 0, 5);
 				$t2 = substr($exam->time, 0, 5);
 				$t1 = intval(substr($t1, 0, 2)) * 60 + intval(substr($t1, 3, 2));
 				$t2 = intval(substr($t2, 0, 2)) * 60 + intval(substr($t2, 3, 2));
 				if ($t2 >= $t1 && $t2 <= $t1  + $e->duration) {
 					$em->flush();
 					return "Postoji ispit u tom terminu na toj lokaciji.";
 				}
 			}

 			$em->persist($exam);

 			$em->flush();

 			$subjectRepository = $em->getRepository(\Ispitomat\Subject::class);
 			$subject = $subjectRepository->findOneBy(["subjectID" => $subjectID]);

 			$subject->exams->add($exam);
 			$exam->subject->add($subject);
 			$em->flush();
 		}
 		catch(Exception $e) { return $e->getMessage(); }

 		return "OK";

 	}

 	function insertOralExam($subjectID, $date, $location, $max)
 	{
 		try {
 			$em = DB::getConnection();
 			$exam = new Exam();

 			$exam->__set("date",$date);
 			$exam->__set("type","oral");
 			$exam->__set("location",$location);
 			$exam->__set("maxScore",$max);
 			$exam->__set("schoolYear","2019./20.");

 			$em->persist($exam);

 			$em->flush();

 			$subjectRepository = $em->getRepository(\Ispitomat\Subject::class);
 			$subject = $subjectRepository->findOneBy(["subjectID" => $subjectID]);

 			$subject->exams->add($exam);
 			$exam->subject->add($subject);
 			$em->flush();

 		}
 		catch(Exception $e) { return $e->getMessage(); }

 		return "OK";

 	}

 	function editExam($id, $location, $max)
 	{
 		try {
 			$em = DB::getConnection();


 			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
 			$exam = $examsRepository->find($id);

			$exams = $examsRepository->findBy(["date" => $exam->date, "location" => $location]);

 			foreach ($exams as $e) {
				if(strcmp($exam->id, $e->id) === 0) continue;
 				$t1 = substr($e->time, 0, 5);
 				$t2 = substr($exam->time, 0, 5);
 				$t1 = intval(substr($t1, 0, 2)) * 60 + intval(substr($t1, 3, 2));
 				$t2 = intval(substr($t2, 0, 2)) * 60 + intval(substr($t2, 3, 2));
 				if ($t2 >= $t1 && $t2 <= $t1  + $e->duration) {
 					$em->flush();
 					return "Postoji ispit u tom terminu na toj lokaciji.";
 				}
 			}

 			$exam->__set("location",$location);
 			$exam->__set("maxScore",$max);

 			$em->flush();

 		}
 		catch(Exception $e) { return $e->getMessage(); }

 		return "OK";
 	}

	function getSubjectBySubjectID($subjectID)
 	{
 		// funkcija vraća odgovarajući Subject objekt
 		try {
 			$em = DB::getConnection();

 			$subjectRepository = $em->getRepository(\Ispitomat\Subject::class);
 			$subject = $subjectRepository->findOneBy(["subjectID" => $subjectID]);

 			$em->flush();
 		}
 		catch(Exception $e) { exit("Error " . $e->getMessage()); }

 		return $subject;
 	}

 	function getExamByExamID($examID)
 	{
 		try {
 			$em = DB::getConnection();

 			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
 			$exam = $examsRepository->find($examID);

 			$em->flush();
 		}
 		catch(Exception $e) { exit("Error " . $e->getMessage()); }

 		return $exam;
 	}

	function setStudentsScoreOfExam($examID, array $passed, array $score, array $grade)
 	{
 		try {
 			$em = DB::getConnection();

 			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
 			$exam = $examsRepository->find($examID);

 			foreach ($passed as $jmbag => $value) {

 				$student = $em->getRepository(\Ispitomat\Student::class)->findOneBy(["jmbag" => (string) $jmbag]);

 				$et = new ExamTaken($student, $exam, $value, (float) $score[$jmbag], $grade[$jmbag]);

 				$student->examsRegisteredFor->removeElement($exam);
 				$exam->studentsRegistered->removeElement($student);

 				$student->examsTaken->add($et);
 				$exam->studentsTakenBy->add($et);
 			}

 			$em->flush();

 		}
 		catch(Exception $e) { return $e->getMessage(); }

 		return "OK";
 	}

 	function getStudentsByExamID($examID)
 	{
 		try {
 			$em = DB::getConnection();

 			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
 			$exam = $examsRepository->find($examID);

 		}
 		catch(Exception $e) { exit("Error " . $e->getMessage()); }

 		$data = array();
 		$students = $exam->studentsRegistered;
		$subject = $exam->subject[0];
 		foreach ($students as $student) {
 			$data[] = [ "subject" => $subject,"exam" => $exam, "student" => $student];
 		}

 		return $data;
 	}

	function getStudentScoresByExamID($examID)
	{
		try {
			$em = DB::getConnection();

			$examsRepository = $em->getRepository(\Ispitomat\Exam::class);
			$exam = $examsRepository->find($examID);

		}
		catch(Exception $e) { exit("Error " . $e->getMessage()); }

		$data = array();
		$examsTaken = $exam->studentsTakenBy;
		$subject = $exam->subject[0];
		foreach ($examsTaken as $et) {
			$data[] = [ "student" => $et->student, "maxScore" => $exam->maxScore,"score" => $et->score, "passed" => $et->passed, "grade" => $et->grade];
		}

		return $data;
	}

};

?>
