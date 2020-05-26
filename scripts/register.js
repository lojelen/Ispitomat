$(document).ready(function()
{
  $(".registerButton").on("click", function(event)
	{
    var examID = $(this).prop("id").substring(9);
    var parent = $(this).parent();
    var parentClass = parent.prop("class");
    var examsDiv = parent.parent();
    $.ajax(
    {
      url: "ispitomat.php?rt=ajax/register",
      data: { examID: examID },
      type: "GET",
      dataType: "json",
      success: function(data)
      {
        var elements = examsDiv.children("." + parentClass);
        for (var i = 0 ; i < elements.length ; ++i)
          elements.eq(i).remove();
        examsDiv.prepend("<div class='info'>Uspješno ste se prijavili na ispit ID-a " + examID + "!</div>");
      }
    });
	});
});
