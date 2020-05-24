<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php
foreach($examsData as $examData)
{
  $canAcceptOrReject = false;
  echo "<div class='examTaken'><h3 class='examHeader'>" . $examData["subject"]->subjectName . " (šifra: " . $examData["subject"]->subjectID . ")</h3><hr>";
  $examInfo = "<ul><li><b>Akademska godina:</b> " . $examData["exam"]->schoolYear . "</li><li><b>Semestar:</b> ";
  if (strcmp($examData["subject"]->semester, "Z") === 0)
    $examInfo = $examInfo . "zimski</li>";
  else
    $examInfo = $examInfo . "ljetni</li>";
  $examInfo = $examInfo . "<li><b>Datum ispita:</b> " . date("d.m.Y", strtotime($examData["exam"]->date)) .
              "</li><li><b>Vrsta ispita:</b> " . (strcmp($examData["exam"]->type, "written") === 0 ? "pismeni" : "usmeni") . "</li>";

  if (strcmp($examData["exam"]->type, "pismeni") === 0)
    $examInfo = $examInfo . "<li><b>Maksimalan broj bodova:</b> " . $examData["exam"]->maxScore . "</li>";

  if (isset($examData["examTaken"])) {
    if ($examData["examTaken"]->passed)
      $examInfo = $examInfo . "<li><b>Ispit položen:</b> DA";
    else
      $examInfo = $examInfo . "<li><b>Ispit položen:</b> NE";
    $examInfo = $examInfo . "</li><li><b>Broj bodova:</b> " . $examData["examTaken"]->score . "</li><li><b>Maksimalan mogući broj bodova:</b> " .
                $examData["exam"]->maxScore . "</li>";
    if ($examData["examTaken"]->grade !== null) {
      $examInfo = $examInfo . "<li class='examGrade'><b>Ocjena:</b> " . $examData["examTaken"]->grade . "</li>";
      if (!$examData["subjectPassed"])
        $canAcceptOrReject = true;
    }
    $examInfo = $examInfo . "<li><b>Prosječan broj bodova:</b> " . round($examData["avgScore"], 3) . "</li></ul>";
  }
  else {
    $examInfo = $examInfo . "<li><b>Rezultati ispita:</b> nepoznati</li></ul>";
  }

  echo $examInfo;

  if ($canAcceptOrReject) {
    echo "<button type='button' class='acceptButton' id='accept_" . $examData["exam"]->id . "'>Prihvati ocjenu</button>";
    echo "<button type='button' class='rejectButton' id='reject_" . $examData["exam"]->id . "'>Odbij ocjenu</button>";
  }
  echo "</div>";
} ?>

<div/>

<script src="./scripts/accept.js"></script>
<script src="./scripts/reject.js"></script>

<?php require_once "view/_footer.php"; ?>
