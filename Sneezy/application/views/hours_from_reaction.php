<div id="hours-from-reaction-controls">
	<h1>Reaction to Food by Hour</h1>
	<form action="post">
		<div class="float-left">
			<div class="ui-widget">
	  				<label for="hours-from-reaction-type">Reaction Type: </label>
	  				<input id="hours-from-reaction-type" value="Vomit" />
			</div>
			<div class="ui-widget">
				<label for="hours-from-reaction-gap">Num Of Gaps: </label>
	  			<input id="hours-from-reaction-gap" value="2"/>
	  		</div>
            <div class="ui-widget">
                <label for="hours-from-reaction-initial-hour">Initial Hour: </label>
                <input id="hours-from-reaction-initial-hour" value="1"/>
            </div>
        </div>
        <div class="float-left">
            <div class="ui-widget">
                <label for="hours-from-reaction-start-date">Start Date: </label>
                <input id="hours-from-reaction-start-date" value=""/>
            </div>
            <div class="ui-widget">
                <label for="hours-from-reaction-end-date">End Date: </label>
                <input id="hours-from-reaction-end-date" value="<?php echo date("m/d/Y"); ?>"/>
            </div>
            <div class="ui-widget">
                <label for="hours-from-reaction-food-filter">Filter Food: </label>
                <input id="hours-from-reaction-food-filter" value=""/>
            </div>
        </div>
        <div class="float-left">
            <div class="ui-widget">
                <label for="">Min total times eaten: </label>
                <input id="hours-from-reaction-min-eaten" value="0"/>
            </div>
            <div class="ui-widget">
                <label for="hours-from-reaction-scale">Scale: </label>
                <select id = "hours-from-reaction-scale">
                    <option value = "linear" selected>Linear</option>
                    <option value = "quadratic">Quadratic</option>
                    <option value = "exponential">Exponential</option>
                </select>
            </div>
        </div>
		<div id='retrieve-hours-from-reaction-submit' class="clear">
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
</div>
<div id="hours-from-reaction-grid"></div>
<script>
  	sneezySingleton.getInstance().initializeHourReactionButton();
</script>
