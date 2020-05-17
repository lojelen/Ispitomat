<?php

session_start();

if (isset($_GET["rt"]))
	$route = $_GET["rt"];
else
	$route = "index";

// Ako je $route == "con/act", onda rastavi na $controllerName="con", $action="act"
$parts = explode("/", $route);

$controllerName = $parts[0] . "Controller";
if(isset($parts[1]))
	$action = $parts[1];
else
	$action = "index";

// Controller $controllerName nalazi se u poddirektoriju controller
$controllerFileName = "controller/" . $controllerName . ".php";

// Includeaj tu datoteku
if (!file_exists($controllerFileName))
{
	$controllerName = "_404Controller";
	$controllerFileName = "controller/" . $controllerName . ".php";
}

require_once $controllerFileName;

// Stvori pripadni kontroler
$con = new $controllerName;

// Ako u njemu nema tražene akcije, kao akciju postavi index
if (!method_exists($con, $action))
	$action = "index";

// Pozovi odgovarajuću akciju
$con->$action();

?>
