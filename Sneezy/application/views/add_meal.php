

<div>
	<h1>Add Meal</h1>
	<form action="post">
		<div class="ui-widget">
  				<label for="food_types">Food Type: </label>
  				<input id="food_types" />
		</div>
		
		<div class="ui-widget">
  				<label for="meal-note">Note: </label>
  				<input id="meal-note" />
		</div>
		
		<div id="meal_date_container" class="ui-widget visible-desktop visible-tablet">
  				<label for="meal_date">Date: </label>
  				<input id="meal_date" value="<?php echo date("m/d/Y h:i a"); ?>"/>
		</div>
		
		<div id="meal_date_wheel_container" class="ui-widget visible-phone">
  				<label for="meal_date_wheel">Date: </label>
  				<input id="meal_date_wheel" type="datetime-local"/>
		</div>
		
		<div id='add-meal-submit' >
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
	<div id="meal_response"  class="alert_response" ></div>
</div>
