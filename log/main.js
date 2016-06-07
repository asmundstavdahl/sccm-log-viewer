/**
 * SCCM Log Viewer
 * @author Åsmund Stavdahl <asmund.stavdahl@itk.ntnu.no>
 */


$(function(){
	// Don't fort table when clicking in a filter input
	$("input").on("click", e => false);
	// Apply filter the enter key is pressed in a filter input
	$("input").on("keypress", e => {
		(e.keyCode ? e.keyCode : e.which) == 13 && Filters.evaluate();
	});
});

var Filters = {
	/**
	 * The previous state (content) of the filters.
	 * @type string
	 */
	lastState: null,
	/**
	 * Check if any filters have changed since last time.
	 * @return boolean true if filters have been changed since last call to this function
	 */
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
	// Apply the filters to the log rows
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
