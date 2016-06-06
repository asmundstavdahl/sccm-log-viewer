/**
 * SCCM Log Viewer
 * @author Åsmund Stavdahl <asmund.stavdahl@itk.ntnu.no>
 */


$(function(){
	// Hindre sortering når man klikker inni filterboksen
	$("input").on("click", e => false);
	//$("input").on("change", e => Filters.evaluate());
	// Filtrer når man trykker enter i tekstboksen
	$("input").on("keypress", e => {
		(e.keyCode ? e.keyCode : e.which) == 13 && Filters.evaluate();
	});
	//setInterval(e => Filters.hasChanged() && Filters.evaluate(), 1000);
});

// Object med filterfunksjonalitet
var Filters = {
	// Siste filtersignatur
	lastState: null,
	// Sjekk om noen av filterne har endred seg siden siste kall
	hasChanged: () => {
		var currentState = "";
		$(".filter input").each(function(){
			currentState += "~" + $(this).val();
		});
		var ret = currentState != Filters.lastState;
		//console.log(currentState, Filters.lastState);
		Filters.lastState = currentState;
		return ret;
	},
	// Kjør filtrene mit loggradene
	evaluate: () => {
		$(".filter input").attr("disabled", true);

		var totalMatchingEntries = 0;

		/**
		 * This flag will be decide whether #filter-tip will be displayed
		 * @type boolean
		 */
		var allFiltersAreEmpty = true;
		
		var propMatchesNeeded = 0;
		$(".filter input").each(function(){
			$(this).val() ?propMatchesNeeded++ :false;
		});

		$(".log-entry").each(function(){
			$(this).removeClass("shown");
			$(this).data("matches", "0");
		});
		$(".filter input").each(function(){
			if(!$(this).val()){
				//console.log("No filter for", propName);
				return;
			} else {
				allFiltersAreEmpty = false;
			}

			var propName = $(this).data("prop");
			var propFilter = new RegExp($(this).val(), "i");

			$(".log-entry:not(.shown)").each(function(){
				var propHost = $(this).find("td[data-prop='"+propName+"'] > *");
				//console.log("Applying filter for", propName, propFilter);
				//console.log("propHost", propHost);
				var matches = propFilter.test(propHost.html());
				console.log("Did it match?", matches);
				if(matches){
					var currentPropMatches = parseInt($(this).data("matches"));
					$(this).data("matches", currentPropMatches + 1);
					console.log("New number of matches is", $(this).data("matches"));
				}
				if(propMatchesNeeded == parseInt($(this).data("matches"))){
					$(this).addClass("shown");
					totalMatchingEntries++;
				} else {
					console.log("propMatchesNeeded not met", propMatchesNeeded);
				}
			});
		});
		$("#match-counter").html(totalMatchingEntries);
		$(".filter input").attr("disabled", false);

		if(allFiltersAreEmpty){
			$("#filter-tip").show();
		} else {
			$("#filter-tip").hide();
			if(totalMatchingEntries == 0){
				$("#no-match").show();
			} else {
				$("#no-match").hide();
			}
		}

	}
}
