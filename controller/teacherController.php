<?php

require_once "model/service.class.php";

class TeacherController
{
	public function index()
 	{
 		$tus = new Service();

 		$subjectList = $tus->getSubjectsByUserID($_SESSION["userID"]);

 		require_once "view/teacher_index.php";
 	}

 	public function subject()
 	{
 		$tus = new Service();

 		if (isset($_GET["subjectID"])) {
 			$subject= $tus->getSubjectBySubjectID($_GET["subjectID"]);

 			$_SESSION["subjectID"] = $subject->subjectID;
 		}

 		require_once "view/subject_index.php";
 	}

 	public function availableExams()
 	{
 		$tus = new Service();

 		$exams = $tus->getExamsAvailableFromSubject($_SESSION["subjectID"]);

 		require_once "view/T_examsAvailable_index.php";
 	}

 	public function takenExams()
 	{
 		$tus = new Service();

 		$examsData = $tus->getExamsTakenFromSubject($_SESSION["subjectID"]);

 		require_once "view/T_examsTaken_index.php";
 	}

 	public function addExam()
 	{
		$tus = new Service();

		$subject= $tus->getSubjectBySubjectID($_SESSION["subjectID"]);

 		require_once "view/T_addExam_index.php";
 	}

 	public function addExamInput()
 	{
 		// Funkcija pomoću koje pokrećemo ubacivanje podataka o novom ispitu
 		$tus = new Service();

 		$errorMsg = "NOT_SET";

 		if (isset($_POST["date"]) && isset($_POST["type"]) && isset($_POST["location"]) && isset($_POST["max"]) &&
 		(strcmp($_POST["location"], "") !== 0) && (strcmp($_POST["date"], "") !== 0)) {
			if (strcmp($_POST["type"], "written") === 0) $retVal = $tus->insertWrittenExam($_SESSION["subjectID"], $_POST["date"], $_POST["time"], $_POST["duration"], $_POST["location"], $_POST["max"]);
			else $retVal = $tus->insertOralExam($_SESSION["subjectID"], $_POST["date"], $_POST["location"], $_POST["max"]);
 			$errorMsg = $retVal; // Poruka koja se ispisuje ako je nešto pošlo po zlu prilikom ubacivanja podataka u bazu
 		}

 		require_once "view/T_addExam.php";
 	}

	public function edit()
	{
		$tus = new Service();

 		if (isset($_GET["examID"])) {
 			$exam= $tus->getExamByExamID($_GET["examID"]);
 		}

 		require_once "view/T_examsAvailable_edit.php";
	}

	public function editExamInput()
	{
		// Funkcija pomoću koje pokrećemo ubacivanje podataka o novom ispitu
		$tus = new Service();

		$errorMsg = "NOT_SET";

		if (isset($_POST["location"]) && isset($_POST["max"]) &&
		(strcmp($_POST["location"], "") !== 0)) {
			$retVal = $tus->editExam($_POST["id"], $_POST["location"], $_POST["max"]);
			$errorMsg = $retVal; // Poruka koja se ispisuje ako je nešto pošlo po zlu prilikom ubacivanja podataka u bazu
		}

		require_once "view/T_addExam.php";
	}

	public function evaluate()
	{
		$tus = new Service();

		if (isset($_GET["examID"])) {
			$data = $tus->getStudentsByExamID($_GET["examID"]);
		}

		require_once "view/T_examsTaken_evaluate.php";
	}

	public function evaluateExamInput()
	{
		// Funkcija pomoću koje pokrećemo ubacivanje podataka o novom ispitu
		$tus = new Service();

		$errorMsg = "NOT_SET";

		if (isset($_POST["location"]) && isset($_POST["max"]) &&
		(strcmp($_POST["location"], "") !== 0)) {
			$retVal = $tus->editExam($_POST["id"], $_POST["location"], $_POST["max"]);
			$errorMsg = $retVal; // Poruka koja se ispisuje ako je nešto pošlo po zlu prilikom ubacivanja podataka u bazu
		}

		require_once "view/T_addExam.php";
	}

}

?>
