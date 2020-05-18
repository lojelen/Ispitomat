$(document).ready(function()
{
	/*
	Skripta pomoću koje reagiramo na događaje vezane uz elemente liste unutar navigacije u headeru.
	Prilikom klika na neki od elemenata, mijenja se podatak o trenutno aktivnom odjeljku.
	Element liste (koji predstavlja trenutni aktivni odjeljak) dobiva klasu "current", dok svim ostalima
	oduzimamo klasu "current". Pomoću CSS-a mijenjamo izgled elemenata s klasom "current".
	*/
	navDecoration();

	$(".navLink").on("click", function(event)
	{
		sessionStorage.setItem("active", $(this).prop("id"));
		navDecoration();
	});

  $("#logout").on("click", function(event)
  {
    var elements = $(".navLink");
    for (var i = 0 ; i < elements.length ; ++i) {
			if (sessionStorage.getItem("active") === elements.eq(i).prop("id"))
				elements.eq(i).parent().removeClass("current");
    }
    sessionStorage.removeItem("active");
  });

	function navDecoration()
	{
		var elements = $(".navLink");
		for (var i = 0 ; i < elements.length ; ++i) {
			if (sessionStorage.getItem("active") === elements.eq(i).prop("id"))
				elements.eq(i).parent().addClass("current");
			else
				elements.eq(i).parent().removeClass("current");
		}
	};
});
