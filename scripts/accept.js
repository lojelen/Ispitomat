$(document).ready(function()
{
  $(".acceptButton").on("click", function(event)
	{
    var examID = $(this).prop("id").substring(7);
    var parent = $(this).parent();
    var examsDiv = parent.parent();
    var acceptButton = $(this);
    var rejectButton = parent.children("#reject_" + examID);
    $.ajax(
    {
      url: "ispitomat.php?rt=ajax/accept",
      data: { examID: examID },
      type: "GET",
      dataType: "json",
      success: function(data)
      {
        examsDiv.prepend("<div class='info'>Uspješno ste prihvatili ocjenu!</div>");
        acceptButton.remove();
        rejectButton.remove();
      }
    });
	});
});
