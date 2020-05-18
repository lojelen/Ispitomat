<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php
foreach($exams as $exam)
{
  $examInfo = "<div class='examTaken'><ul><li><b>ID ispita:</b> " . $exam["examID"] . "</li><li><b>Datum ispita:</b> " .
               date("d.m.Y", strtotime($exam["date"])) . "</li><li><b>Šifra predmeta:</b> " .
               $exam["subjectID"] . "</li><li><b>Naziv predmeta:</b> " . $exam["subjectName"] . "</li><li><b>Semestar:</b> ";
  if (strcmp($exam["semester"], "Z") === 0)
    $examInfo = $examInfo . "zimski</li><li><b>Ispit položen:</b> ";
  else
    $examInfo = $examInfo . "ljetni</li><li><b>Ispit položen:</b> ";
  if ($exam["passed"])
    $examInfo = $examInfo . "DA";
  else
    $examInfo = $examInfo . "NE";
  $examInfo = $examInfo . "</li><li><b>Broj bodova:</b> " . $exam["score"] . "</li>";
  if ($exam["grade"] !== null)
    $examInfo = $examInfo . "<li><b>Ocjena:</b> " . $exam["grade"] . "</li>";
  $examInfo = $examInfo . "<li><b>Prosječan broj bodova:</b> " . round($exam["avgScore"], 3) . "</li></ul></div>";
  echo $examInfo;
} ?>

<div/>

<?php require_once "view/_footer.php"; ?>
