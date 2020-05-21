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
			$examsData[] = ["examTaken" => $examTaken, "exam" => $examTaken->exam, "subject" => $examTaken->exam->subject[0],
											"avgScore" => $avgScore / $cnt];
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
					$examsData[] = ["exam" => $examRegisteredFor, "subject" => $examRegisteredFor->subject[0]];
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
		foreach ($subjects as $ss) {
			$available = 1; // 0 = nisu dostupni ni pismeni ni usmeni, 1 = dostupni su samo pismeni, 2 = dostupni su samo usmeni
			foreach ($student->examsRegisteredFor as $examRegisteredFor) {
				if (strcmp($examRegisteredFor->subject[0]->subjectID, $ss->subject->subjectID) === 0) {
					$available = 0;
					break;
				}
			}
			if ($available === 0)
				continue;
			foreach ($student->examsTaken as $et) {
				if (strcmp($et->exam->subject[0]->subjectID, $ss->subject->subjectID) === 0) {
					if (strcmp($et->exam->type, "oral") === 0 && $et->passed) {
						$available = 0;
						break;
					}
					else if (strcmp($et->exam->type, "written") === 0 && $et->passed && !$et->exam->subject[0]->oralExam) {
						$available = 0;
						break;
					}
					else if (strcmp($et->exam->type, "written") === 0 && $et->passed && $et->exam->subject[0]->oralExam) {
						$available = 2;
					}
				}
			}
			if ($available === 0)
				continue;
			foreach ($ss->subject->exams as $exam) {
				$d1 = date("Y-m-d", strtotime($exam->date));
				if ($d1 > $d2) {
					if ($available === 1 && strcmp($exam->type, "written") === 0)
						$examsData[] = ["exam" => $exam, "subject" => $exam->subject[0]];
					else if ($available === 2 && strcmp($exam->type, "oral") === 0)
						$examsData[] = ["exam" => $exam, "subject" => $exam->subject[0]];
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
					$exams[] = ["exam" => $examRegisteredFor, "deregister" => ($d1 > $d2)];
			}
			else {
				if ($d1 > $d2)
					$exams[] = ["exam" => $examRegisteredFor, "deregister" => true];
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

	function getSubjectBySubjectID($subjectID)
 	{
 		// funkcija vraća odgovarajući Subject objekt
 		try
 		{
 			$client = DB::getClient();
 			$query = "MATCH (subject:Subject {subjectID:'" . $subjectID . "'}) RETURN subject";
 			$result = $client->run($query)->getRecord();
 		}
 		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

 		if ($result === null) // provjeriti vraća li null ako ne postoji
 			return null;
 		else {
 			$subject = $result->value("subject");
 			return Subject::withArgs($subject->get("subjectID"), $subject->get("subjectName"), $subject->get("year"),
 												 			 $subject->get("semester"));
 		}
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
 			$client = DB::getClient();
 			$query = "MATCH (s:Subject {subjectID:'" . $subjectID . "'})<-[f:IN]-(e:Exam)
 								WITH s, f, e
 								MATCH (:Student)-[tOthers:TAKES]->(:Exam {examID: e.examID})
 								RETURN s, f,e, AVG(tOthers.score) as avgScore";
 			$results = $client->run($query)->getRecords();
 		}
 		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

 		$arr = array();
 		foreach ($results as $result) {
 			$f = $result->value("f");
 			$e = $result->value("e");
 			$s = $result->value("s");
 			$avgScore = $result->value("avgScore");
 			$arr[] = ["examID" => $e->get("examID"), "date" => $e->get("date"), "subjectID" => $s->get("subjectID"),
 								"subjectName" => $s->get("subjectName"), "semester" => $s->get("semester"), "avgScore" => $avgScore];
 		}
 		return $arr;
 	}

 	function getExamsAvailableFromSubject($subjectID)
 	{
 		try
 		{
 			$client = DB::getClient();
 			$query = "MATCH (s:Subject {subjectID:'" . $subjectID . "'})<-[f:FROM]-(e:Exam)
 								WHERE date(e.date)>date()
 								RETURN e, s";
 			$results = $client->run($query)->getRecords();
 		}
 		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

 		$arr = array();
 		foreach ($results as $result) {
 			$exam = $result->value("e");
 			$subject = $result->value("s");
 			$arr[] = ["subjectID" => $subject->get("subjectID"), "subjectName" => $subject->get("subjectName"), "semester" => $subject->get("semester"),
 								"examID" => $exam->get("examID"), "date" => $exam->get("date")];
 		}
 		return $arr;
 	}
};

?>
