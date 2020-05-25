$(document).ready(function()
{
  $(".rejectButton").on("click", function(event)
	{
    var examID = $(this).prop("id").substring(7);
    var parent = $(this).parent();
    var examsDiv = parent.parent();
    var rejectButton = $(this);
    var acceptButton = parent.children("#accept_" + examID);
    var examGrade = parent.children("ul").children(".examGrade");
    $.ajax(
    {
      url: "ispitomat.php?rt=ajax/reject",
      data: { examID: examID },
      type: "GET",
      dataType: "json",
      success: function(data)
      {
        examsDiv.prepend("<div class='info'>Uspješno ste odbili ocjenu!</div>");
        acceptButton.remove();
        rejectButton.remove();
        examGrade.remove();
      }
    });
	});
});
