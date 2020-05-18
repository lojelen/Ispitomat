<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php
if (isset($deregistered))
  echo "<div class='info'>Uspješno ste se odjavili s ispita ID-a " . $examID . "!</div>";

foreach($exams as $exam)
{
  $examInfo = "<div class='examRegisteredFor'><ul><li><b>Šifra predmeta:</b> " . $exam["subjectID"] . "</li><li><b>Naziv predmeta:</b> " .
               $exam["subjectName"] . "</li><li><b>Semestar:</b> ";
  if (strcmp($exam["semester"], "Z") === 0)
    $examInfo = $examInfo . "zimski";
  else
    $examInfo = $examInfo . "ljetni";
  $examInfo = $examInfo . "</li><li><b>ID ispita:</b> " . $exam["examID"] . "</li><li><b>Datum ispita:</b> " .
               date("d.m.Y", strtotime($exam["date"]));
  echo $examInfo; ?>

<form id="deregisterForm" method="post" action="ispitomat.php?rt=student/deregister&examID=<?php echo $exam["examID"]; ?>">
  <button type="submit" name="deregister" id="deregister">Odjavi se</button>
</form>

<?php } ?>

<div/>

<?php require_once "view/_footer.php"; ?>
