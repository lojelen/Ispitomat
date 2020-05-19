<?php

require_once "db.class.php";
require_once "user.class.php";
require_once "teacher.class.php";
require_once "student.class.php";
require_once "subject.class.php";

class Service
{
	function getUserByUserID($userID)
	{
		// implementacija dohvaćanja korisnika s danim userIDjem iz baze
		// funkcija vraća odgovarajući Teacher ili Student objekt
		echo $userID;
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (user:User {userID:'" . $userID . "'}) RETURN user, labels(user) AS labels";
			$result = $client->run($query)->getRecord();
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

		if ($result === null) // provjeriti vraća li null ako ne postoji
			return null;
		else {
			$user = $result->value("user");
			$labels = $result->value("labels");
			foreach ($labels as $label) {
				if (strcmp($label, "Teacher") === 0)
					return new Teacher($user->get("userID"), $user->get("name"), $user->get("surname"),
														 $user->get("gender"), $user->get("dateOfBirth"), $user->get("address"),
														 $user->get("oib"), $user->get("title"));
				else if (strcmp($label, "Student") === 0)
					return new Student($user->get("userID"), $user->get("name"), $user->get("surname"),
													 	 $user->get("gender"), $user->get("dateOfBirth"), $user->get("address"),
													 	 $user->get("jmbag"));
			}
		}
	}

	function getExamsTakenByStudent($userID)
	{
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (:Student {userID:'" . $userID . "'})-[t:TAKES]->(e:Exam)-[:IN]->(s:Subject)
								WITH t, e, s
								MATCH (:Student)-[tOthers:TAKES]->(:Exam {examID: e.examID})
								RETURN t, e, s, AVG(tOthers.score) as avgScore";
			$results = $client->run($query)->getRecords();
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

		$arr = array();
		foreach ($results as $result) {
			$t = $result->value("t");
			$e = $result->value("e");
			$s = $result->value("s");
			$avgScore = $result->value("avgScore");
			if (!$t->get("passed"))
				$grade = null;
			else
				$grade = $t->get("grade");
			$arr[] = ["examID" => $e->get("examID"), "date" => $e->get("date"), "subjectID" => $s->get("subjectID"),
								"subjectName" => $s->get("subjectName"), "semester" => $s->get("semester"), "passed" => $t->get("passed"),
								"score" => $t->get("score"), "grade" => $grade, "avgScore" => $avgScore];
		}
		return $arr;
	}

	function getExamsAvailableToStudent($userID)
	{
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (student:Student {userID:'" . $userID . "'})-[:ENROLLED_IN]->(subject:Subject)<-[:IN]-(exam:Exam)
								WHERE size((student)-[:REGISTERED_FOR]->(:Exam)-[:IN]->(subject))=0
								AND size((student)-[:TAKEN {passed:true}]->(:Exam)-[:IN]->(subject))=0
								AND date(exam.date)>date()
								RETURN exam, subject";
			$results = $client->run($query)->getRecords();
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

		$arr = array();
		foreach ($results as $result) {
			$exam = $result->value("exam");
			$subject = $result->value("subject");
			$arr[] = ["subjectID" => $subject->get("subjectID"), "subjectName" => $subject->get("subjectName"), "semester" => $subject->get("semester"),
								"examID" => $exam->get("examID"), "date" => $exam->get("date")];
		}
		return $arr;
	}

	function registerStudentForExam($userID, $examID)
	{
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (student:Student {userID:'" . $userID . "'}), (exam:Exam {examID:'" . $examID . "'})
								CREATE (student)-[:REGISTERED_FOR]->(exam)";
			$client->run($query);
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }
	}

	function getExamsStudentIsRegisteredFor($userID)
	{
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (student:Student {userID:'" . $userID . "'})-[r:REGISTERED_FOR]->(exam:Exam)-[:IN]->(subject:Subject)
								WHERE size((student)-[:TAKES]->(exam))=0
								RETURN exam, subject";
			$results = $client->run($query)->getRecords();
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

		$arr = array();
		foreach ($results as $result) {
			$exam = $result->value("exam");
			$subject = $result->value("subject");
			$arr[] = ["subjectID" => $subject->get("subjectID"), "subjectName" => $subject->get("subjectName"), "semester" => $subject->get("semester"),
								"examID" => $exam->get("examID"), "date" => $exam->get("date")];
		}
		return $arr;
	}

	function deregisterStudentForExam($userID, $examID)
	{
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (student:Student {userID:'" . $userID . "'})-[r:REGISTERED_FOR]->(exam:Exam {examID:'" . $examID . "'})
								DELETE r";
			$client->run($query);
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }
	}

	function getSubjectBySubjectID($subjectID)
	{
		// funkcija vraća odgovarajući Subject objekt
		try
		{
			$client = DB::getConnection();
			$query = "MATCH (subject:Subject {subjectID:'" . $subjectID . "'}) RETURN subject";
			$result = $client->run($query)->getRecord();
		}
		catch(PDOException $e) { exit("PDO error " . $e->getMessage()); }

		if ($result === null) // provjeriti vraća li null ako ne postoji
			return null;
		else {
			$subject = $result->value("subject");
			return new Subject($subject->get("subjectID"), $subject->get("subjectName"), $subject->get("year"),
												 $subject->get("semester"));
		}
	}

	function getSubjectsByUserID($userID)
	{
		try
		{
			$client = DB::getConnection();
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
			$client = DB::getConnection();
			$query = "MATCH (s:Subject {subjectID:'" . $subjectID . "'})<-[f:FROM]-(e:Exam)
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
			$client = DB::getConnection();
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
