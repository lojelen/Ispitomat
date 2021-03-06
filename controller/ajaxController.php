<?php

require_once "model/service.class.php";

function sendJSONandExit($message)
{
	header("Content-type:application/json;charset=utf-8");
	echo json_encode($message);
	flush();
	exit(0);
}

// Klasa pomoću koje obrađujemo razne AJAX upite. Za svaku vrstu AJAX upita postoji odgovarajuća metoda
class ajaxController
{
  function register()
  {
    $tus = new Service();

    $examID = $_GET["examID"];
    $tus->registerStudentForExam($_SESSION["userID"], $examID);

		sendJSONandExit("success");
  }

	function deregister()
  {
		$tus = new Service();

		$examID = $_GET["examID"];
		$tus->deregisterStudentFromExam($_SESSION["userID"], $examID);

		sendJSONandExit("success");
  }

	function accept()
	{
		$tus = new Service();

		$examID = $_GET["examID"];
		$tus->acceptExamGrade($_SESSION["userID"], $examID);

		sendJSONandExit("success");
	}

	function reject()
	{
		$tus = new Service();

		$examID = $_GET["examID"];
		$tus->rejectExamGrade($_SESSION["userID"], $examID);

		sendJSONandExit("success");
	}
}
?>
