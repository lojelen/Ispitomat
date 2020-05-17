<?php 

class _404Controller
{
	public function index()
	{
		$title = "Stranica nije pronađena.";

		require_once "view/404_index.php";
	}
};

?>
