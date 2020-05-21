$(document).ready(function()
{
  $(".deregisterButton").on("click", function(event)
	{
    var examID = $(this).prop("id").substring(11);
    var parent = $(this).parent();
    $.ajax(
    {
      url: "ispitomat.php?rt=ajax/deregister",
      data: { examID: examID },
      type: "GET",
      dataType: "json",
      success: function(data)
      {
        $("#exams").prepend("<div class='info'>Uspješno ste se odjavili s ispita ID-a " + examID + "!</div>");
        parent.remove();
      }
    });
	});
});
