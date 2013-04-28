<!DOCTYPE html>
<html lang="en">
<head>
<title>Add a meal</title>
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
	<h1>Add Meal</h1>
	<form action="post">
		<div class="ui-widget">
  				<label for="food_types">Food Type: </label>
  				<input id="food_types" />
		</div>
		<div class="ui-widget">
  				<label for="meal_date">Date: </label>
  				<input id="meal_date" />
		</div>
		<div id='add-meal-submit' >
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
	<div id="meal_response"  class="alert_response" ></div>
</div>
<script>
sneezySingleton.getInstance().initializeAddMeal();
</script>


</body>
</html>