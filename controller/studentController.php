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
		$date = array_column($exams, "date");
		array_multisort($date, SORT_ASC, $exams);

		require_once "view/examsAvailable_index.php";
	}

	public function takenExams()
	{
		$tus = new Service();

		$examsData = $tus->getExamsTakenByStudent($_SESSION["userID"]);
		$date = array_column($examsData, "date");
		array_multisort($date, SORT_DESC, $examsData);

		require_once "view/examsTaken_index.php";
	}

	public function examsRegisteredFor()
	{
		$tus = new Service();

		$exams = $tus->getExamsStudentIsRegisteredFor($_SESSION["userID"]);
		$date = array_column($exams, "date");
		array_multisort($date, SORT_ASC, $exams);

		require_once "view/examsRegisteredFor_index.php";
	}
}

?>
