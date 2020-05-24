<?php require_once "view/_header.php";

 require_once "view/_navSubject.php"; ?>

 <div id="exams">

 <?php
foreach($examsData as $examData)
{
  echo "<div class='examTaken'>";
  $examInfo = "<ul><li><b>Akademska godina:</b> " . $examData["exam"]->schoolYear . "</li><li><b>Semestar:</b> ";
  if (strcmp($examData["subject"]->semester, "Z") === 0)
    $examInfo = $examInfo . "zimski</li>";
  else
    $examInfo = $examInfo . "ljetni</li>";
  $examInfo = $examInfo . "<li><b>Datum ispita:</b> " . date("d.m.Y", strtotime($examData["exam"]->date)) .
              "</li><li><b>Vrsta ispita:</b> " . (strcmp($examData["exam"]->type, "written") === 0 ? "pismeni" : "usmeni") . "</li>";

  if (strcmp($examData["exam"]->type, "written") === 0)
    $examInfo = $examInfo . "<li><b>Maksimalan broj bodova:</b> " . $examData["exam"]->maxScore . "</li>";
  if (isset($examData["avgScore"])) {
    $examInfo = $examInfo . "<li><b>Prosječan broj bodova:</b> " . round($examData["avgScore"], 3) . "</li></ul>";
  }
  else {
    $examInfo = $examInfo . "<li><b>Rezultati ispita:</b> nepoznati</li></ul>";
  }
  echo $examInfo;
  //Upiši bodove
  ?>
  <form id="evaluateForm" method="post" action="ispitomat.php?rt=teacher/evaluate&examID=<?php echo $examData["exam"]->id; ?>">
    <button type="submit" name="evaluateButton" id="evaluate_<?php echo $examData["exam"]->id; ?>">Upiši bodove</button>
  </form>
  <form id="reviewForm" method="post" action="ispitomat.php?rt=teacher/review&examID=<?php echo $examData["exam"]->id; ?>">
    <button type="submit" name="reviewButton" id="review_<?php echo $examData["exam"]->id; ?>">Pregledaj upisane bodove</button>
  </form>
</div>
<?php } ?>

<div/>

<?php require_once "view/_footer.php"; ?>
