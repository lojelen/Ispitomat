<nav id="nav">
  <div id="welcome2"><h3><?php echo $_SESSION["subjectID"]; ?></h3></div>
  <div class="navElement"><a class="navLink" id="availableExams" href="ispitomat.php?rt=teacher/availableExams">Nadolazeći ispiti</a></div>
  <div class="navElement"><a class="navLink" id="takenExams" href="ispitomat.php?rt=teacher/takenExams">Obavljeni ispiti</a></div>
  <div class="navElement"><a class="navLink" id="addExam" href="ispitomat.php?rt=teacher/addExam">Dodaj ispit</a></div>
  <div class="navElement"><a class="navLink" id="selectSubject" href="ispitomat.php?rt=teacher/index">Izbor kolegija</a></div>
</nav>
