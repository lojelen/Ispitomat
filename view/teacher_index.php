<?php require_once "view/_header.php"; ?>

<div id="welcome2">
  <h3>Dobrodošli u <span class="ispitomat">Ispitomat!</span></h3>

  </br> Moji kolegiji:
</div>

 <?php
 	foreach($subjectList as $subject)
 	{
 		echo "<a href='ispitomat.php?rt=teacher/subject&subjectID=". $subject->subjectID . "'>";
 		echo "<div class='subject' id='div_".$subject->subjectID."'><h2>";
    echo $subject->subjectName;
  		echo "</h2></div></a>";
 	}
 ?>
