<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php

foreach($exams as $exam)
{
  $examInfo = "<div class='examRegisteredFor'><ul><li><b>Šifra predmeta:</b> " . $exam->subject[0]->subjectID .
              "</li><li><b>Naziv predmeta:</b> " . $exam->subject[0]->subjectName . "</li><li><b>Semestar: </b>";
  if (strcmp($exam->subject[0]->semester, "Z") === 0)
    $examInfo = $examInfo . "zimski";
  else
    $examInfo = $examInfo . "ljetni";
  $examInfo = $examInfo . "</li><li><b>Datum ispita:</b> " . date("d.m.Y", strtotime($exam->date)) . "</li></ul><br>";
  echo $examInfo; ?>

<button type="button" class="deregisterButton" id="deregister_<?php echo $exam->neo4jID; ?>">Odjavi se</button>
</div>
<?php } ?>

<div/>

<script src="./scripts/deregister.js"></script>

<?php require_once "view/_footer.php"; ?>
