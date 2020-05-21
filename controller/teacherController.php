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

 		$exams = $tus->getExamsTakenFromSubject($_SESSION["subjectID"]);

 		require_once "view/T_examsTaken_index.php";
 	}

 	public function addExam()
 	{
 		require_once "view/T_addExam_index.php";
 	}
 	/*

 	public function addExamInput()
 	{
 		// Funkcija pomoću koje pokrećemo ubacivanje podataka o novom ispitu
 		$tus = new Service();

 		$errorMsg = "NOT_SET";

 		if (isset($_POST["mjesto"]) && isset($_POST["datum"]) && isset($_POST["cijena"]) &&
 		(strcmp($_POST["mjesto"], "") !== 0) && (strcmp($_POST["datum"], "") !== 0)) {
 			$retVal = $tus->insertExam($_SESSION["id"], $_POST["mjesto"], $_POST["datum"], $_POST["cijena"], $_POST["abstract"], $_POST["sirina"], $_POST["duzina"]);
 			$errorMsg = $retVal[0]; // Poruka koja se ispisuje ako je nešto pošlo po zlu prilikom ubacivanja podataka u bazu
 			$id_aktivnost = $retVal[1]; // ID novonastale aktivnosti, potreban kako bismo omogućili vođi da vidi stranicu s opisom aktivnosti koju je stvorio
 		}

 		require_once "view/T_addExam.php";
 	}
 	*/
}

?>
