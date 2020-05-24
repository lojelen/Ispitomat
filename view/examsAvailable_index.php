<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php

foreach($exams as $exam)
{
  echo "<div class='examAvailable'><h3 class='examHeader'>" . $exam["subject"]->subjectName . " (šifra: " . $exam["subject"]->subjectID . ")</h3><hr>";
  $examInfo = "<ul><li><b>Akademska godina:</b> " . $exam["exam"]->schoolYear . "</li><li><b>Semestar:</b> ";
  if (strcmp($exam["subject"]->semester, "Z") === 0)
    $examInfo = $examInfo . "zimski";
  else
    $examInfo = $examInfo . "ljetni";
  $examInfo = $examInfo . "</li><li><b>Datum ispita:</b> " . date("d.m.Y", strtotime($exam["exam"]->date)) .
              "</li><li><b>Vrsta ispita:</b> " . (strcmp($exam["exam"]->type, "written") === 0 ? "pismeni" : "usmeni") . "</li>";
  if (strcmp($exam["exam"]->type, "written") === 0) {
    $examInfo = $examInfo . "<li><b>Vrijeme ispita:</b> " . $exam["exam"]->time . "</li><li><b>Trajanje ispita: </b>" .
                $exam["exam"]->duration . " min</li>";
  }
  $examInfo = $examInfo . "<li><b>Lokacija ispita:</b> " . $exam["exam"]->location . "</li></ul>";
  echo $examInfo; ?>

<button type="button" class="registerButton" id="register_<?php echo $exam["exam"]->id; ?>">Prijavi se</button>
</div>
<?php } ?>
<div/>

<script src="./scripts/register.js?2"></script>

<?php require_once "view/_footer.php"; ?>
