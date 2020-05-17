<!DOCTYPE html>
<html>
<head>
	<meta charset="utf8">
	<title>Ispitomat</title>
	<link rel="stylesheet" type="text/css" href="./css/style.css">
	<link rel="stylesheet" type="text/css" href="//fonts.googleapis.com/css?family=Atomic+Age" />
</head>
<body>
	<div id="header">
		<h2 id="title">Ispitomat</h2>
		<img src="./data/exam-icon.png" height="70px">
		<h3 id="userID"><?php echo $_SESSION["userID"]; ?></h3>
		<a id="logout" href="ispitomat.php?rt=login/logout">Logout</a>
	</div>
