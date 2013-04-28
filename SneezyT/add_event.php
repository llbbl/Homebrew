<!DOCTYPE html>
<html lang="en">
<head>
<title>Add an event</title>
	<!-- jQuery -->
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-1.9.1.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-ui-1.10.2.custom.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-ui-timepicker-addon.js"></script>
	
	<!-- explore jQuery ui touch -->
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery.ui.touch.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery.ui.ipad.altfix.js"></script>
	
	<!-- Bootstrap -->
	<script src="<?php echo base_url();?>js/bootstrap/js/bootstrap.js"></script>
	
	<!-- CSS -->
	<link rel=stylesheet href="<?php echo base_url();?>js/jquery-ui/css/ui-lightness/jquery-ui-1.10.2.custom.min.css" type="text/css" />
	<link rel=stylesheet href="<?php echo base_url();?>js/bootstrap/css/bootstrap.css" type="text/css" />
	
	<!-- handrolled css and js -->
	<script src="<?php echo base_url();?>js/sneezy.js"></script>
	<link rel=stylesheet href="<?php echo base_url();?>css/sneezy.css" type="text/css" />
	
</head>
<body >

<div class='content-area'>
	<h1>Add Event</h1>
	<form action="post">
		<div class="ui-widget">
  				<label for="event_types">Event Type: </label>
  				<input id="event_types" />
		</div>
		<div class="ui-widget">
  				<label for="event_date">Date: </label>
  				<input id="event_date" />
		</div>
		<div id='add-event-submit' >
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
	<div id="event_response" class="alert_response"></div>
</div>
<script>
sneezySingleton.getInstance().initializeAddEvent();
</script>


</body>
</html>