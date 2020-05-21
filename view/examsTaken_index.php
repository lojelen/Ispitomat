<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php
foreach($examsData as $examData)
{
  $examInfo = "<div class='examTaken'><ul><li><b>Datum ispita:</b> " . date("d.m.Y", strtotime($examData["exam"]->date)) .
              "</li><li><b>Šifra predmeta:</b> " . $examData["subject"]->subjectID . "</li><li><b>Naziv predmeta:</b> " .
              $examData["subject"]->subjectName . "</li><li><b>Semestar:</b> ";
  if (strcmp($examData["subject"]->semester, "Z") === 0)
    $examInfo = $examInfo . "zimski</li><li><b>Ispit položen:</b> ";
  else
    $examInfo = $examInfo . "ljetni</li><li><b>Ispit položen:</b> ";
  if ($examData["passed"])
    $examInfo = $examInfo . "DA";
  else
    $examInfo = $examInfo . "NE";
  $examInfo = $examInfo . "</li><li><b>Broj bodova:</b> " . $examData["score"] . "</li>";
  if ($examData["grade"] !== null)
    $examInfo = $examInfo . "<li><b>Ocjena:</b> " . $examData["grade"] . "</li>";
  $examInfo = $examInfo . "<li><b>Prosječan broj bodova:</b> " . round($examData["avgScore"], 3) . "</li></ul></div>";
  echo $examInfo;
} ?>

<div/>

<?php require_once "view/_footer.php"; ?>
