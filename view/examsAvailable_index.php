<?php require_once "view/_header.php";

require_once "view/_navStudent.php"; ?>

<div id="exams">

<?php
/*if (isset($registered))
  echo "<div class='info'>Uspješno ste se prijavili na ispit ID-a " . $examID . "!</div>";*/

foreach($exams as $exam)
{
  $examInfo = "<div class='examAvailable'><ul><li><b>Šifra predmeta:</b> " . $exam["subjectID"] . "</li><li><b>Naziv predmeta:</b> " .
               $exam["subjectName"] . "</li><li><b>Semestar:</b> ";
  if (strcmp($exam["semester"], "Z") === 0)
    $examInfo = $examInfo . "zimski";
  else
    $examInfo = $examInfo . "ljetni";
  $examInfo = $examInfo . "</li><li><b>ID ispita:</b> " . $exam["examID"] . "</li><li><b>Datum ispita:</b> " .
               date("d.m.Y", strtotime($exam["date"]));
  echo $examInfo; ?>

<!--<form id="registerForm" method="post" action="ispitomat.php?rt=student/register&examID=<?php echo $exam["examID"]; ?>">
  <button type="submit" name="register" id="register">Prijavi se</button>
</form>-->
</li></ul><br><button type="submit" class="registerButton" id="register_<?php echo $exam["examID"]; ?>">Prijavi se</button>
</div>
<?php } ?>

<div/>

<script src="./scripts/register.js"></script>

<?php require_once "view/_footer.php"; ?>
