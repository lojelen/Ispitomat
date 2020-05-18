$(document).ready(function()
{
  $(".registerButton").on("click", function(event)
	{
    var examID = $(this).prop("id").substring(9);
    var parent = $(this).parent();
    $.ajax(
    {
      url: "ispitomat.php?rt=ajax/register",
      data: { examID: examID },
      type: "GET",
      dataType: "json",
      success: function(data)
      {
        $("#exams").prepend("<div class='info'>Uspješno ste se prijavili na ispit ID-a " + examID + "!</div>");
        parent.remove();
      }
    });
	});
});
