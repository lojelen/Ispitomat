<?php require_once "view/_header.php"; ?>

 <div> </br> Moji kolegiji:</div>

 <?php
 	foreach($subjectList as $subject)
 	{
 		echo "<div class='subject' id='div_".$subject["subjectID"]."'><h2>";
 		echo "<a href='ispitomat.php?rt=teacher/subject&subjectID=". $subject["subjectID"] . "'>" . $subject["subjectName"] . "</a>";
 		echo "</h2></div>";
 	}
 ?>
