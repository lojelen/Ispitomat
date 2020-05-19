<?php require_once "view/_header.php";

require_once "view/_navSubject.php"; ?>

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
  $examInfo = $examInfo . "<li><b>Prosječan broj bodova:</b> " . round($exam["avgScore"], 3) . "</li></ul></div>";
  echo $examInfo;

  //Upiši bodove
  ?>
  <form id="registerForm" method="post" action="ispitomat.php?rt=teacher/evaluate&examID=<?php echo $exam["examID"]; ?>">
    <button type="submit" name="evaluate" id="evaluate">Upiši bodove</button>
  </form>
<?php } ?>

<div/>

<?php require_once "view/_footer.php"; ?>
