<div id="hours-from-reaction-controls">
	<form action="post">
		<div class="ui-widget">
			<label for="hours-from-reaction-gap">Num Of Gaps: </label>
  			<input id="hours-from-reaction-gap" />
  		</div>
		<div class="ui-widget">
			<select id = "hours-from-reaction-scale">
               <option value = "linear" selected>Linear</option>
               <option value = "quadratic">Quadratic</option>
               <option value = "exponential">Exponential</option>
             </select>
		</div>
		<div id='retrieve-hours-from-reaction-submit' >
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
</div>
<div id="hours-from-reaction-grid">
</div>
<script>
  	sneezySingleton.getInstance().initializeHourReactionButton();
</script>
