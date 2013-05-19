<div>
	<h1>Add Event</h1>
	<form action="post">
		<div class="ui-widget">
  				<label for="event_types">Event Type: </label>
  				<input id="event_types" />
		</div>
		
		<div class="ui-widget">
  				<label for="event-note">Note: </label>
  				<input id="event-note" />
		</div>
		
		<div class="ui-widget">
  				<label for="event_date">Date: </label>
  				<input id="event_date" value="<?php echo date("m/d/Y h:i a"); ?>"/>
		</div>

		<div id='add-event-submit' >
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
	<div id="event_response" class="alert_response"></div>
</div>