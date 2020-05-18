<?php

require_once "model/service.class.php";

class StudentController
{
	public function index()
	{
		require_once "view/student_index.php";
	}

	public function availableExams()
	{
		$tus = new Service();

		$exams = $tus->getExamsAvailableToStudent($_SESSION["userID"]);

		require_once "view/examsAvailable_index.php";
	}

	public function takenExams()
	{
		$tus = new Service();

		$exams = $tus->getExamsTakenByStudent($_SESSION["userID"]);

		require_once "view/examsTaken_index.php";
	}

	/*public function register()
	{
		$tus = new Service();

		$examID = $_GET["examID"];
		$tus->registerStudentForExam($_SESSION["userID"], $examID);

		$exams = $tus->getExamsAvailableToStudent($_SESSION["userID"]);

		$registered = true;

		require_once "view/examsAvailable_index.php";
	}*/

	public function examsRegisteredFor()
	{
		$tus = new Service();

		$exams = $tus->getExamsStudentIsRegisteredFor($_SESSION["userID"]);

		require_once "view/examsRegisteredFor_index.php";
	}

	/*public function deregister()
	{
		$tus = new Service();

		$examID = $_GET["examID"];
		$tus->deregisterStudentForExam($_SESSION["userID"], $examID);

		$exams = $tus->getExamsStudentIsRegisteredFor($_SESSION["userID"]);

		$deregistered = true;

		require_once "view/examsRegisteredFor_index.php";
	}*/
}

?>
