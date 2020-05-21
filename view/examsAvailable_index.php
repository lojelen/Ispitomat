<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php

foreach($exams as $exam)
{
  $examInfo = "<div class='examAvailable'><ul><li><b>Šifra predmeta:</b> " . $exam["subject"]->subjectID . "</li><li><b>Naziv predmeta:</b> " .
               $exam["subject"]->subjectName . "</li><li><b>Semestar:</b> ";
  if (strcmp($exam["subjet"]->semester, "Z") === 0)
    $examInfo = $examInfo . "zimski";
  else
    $examInfo = $examInfo . "ljetni";
  $examInfo = $examInfo . "</li><li><b>Datum ispita:</b> " .
               date("d.m.Y", strtotime($exam["exam"]->date)) . "</li></ul><br>";
  echo $examInfo; ?>

<button type="submit" class="registerButton" id="register_<?php echo $exam["exam"]->neo4jID; ?>">Prijavi se</button>
</div>
<?php } ?>
<div/>

<script src="./scripts/register.js?2"></script>

<?php require_once "view/_footer.php"; ?>
