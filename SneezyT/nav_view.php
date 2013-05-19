<!DOCTYPE html>
<html lang="en">
<head>
<title>Sneezy T Food Tracker</title>
	<meta name="viewport" content="width=device-width, initial-scale=1.0">
	
	<!-- jQuery -->
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-1.9.1.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-ui-1.10.2.custom.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-ui-timepicker-addon.js"></script>
	
	<!-- Bootstrap -->
	<script src="<?php echo base_url();?>js/bootstrap/js/bootstrap.js"></script>
	
	<!-- jTables -->
	<script src="<?php echo base_url();?>js/jtable.2.3.0/jquery.jtable.min.js"></script>
	
	<!-- CSS -->
	<link rel=stylesheet href="<?php echo base_url();?>js/jquery-ui/css/ui-lightness/jquery-ui-1.10.2.custom.min.css" type="text/css" />
	<link rel=stylesheet href="<?php echo base_url();?>js/jtable.2.3.0/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" />
	<link rel=stylesheet href="<?php echo base_url();?>js/bootstrap/css/bootstrap.css" type="text/css" />
	<link rel=stylesheet href="<?php echo base_url();?>js/bootstrap/css/bootstrap-responsive.min.css"  type="text/css">
	
	<!-- handrolled css and js -->
	<script src="<?php echo base_url();?>js/sneezy.js"></script>
	<link rel=stylesheet href="<?php echo base_url();?>css/sneezy.css" type="text/css" />
</head>
<body >
<div class="navbar navbar-inverse">
	<div class="navbar-inner">
		<div class="container">
			<!-- .btn-navbar is used as the toggle for collapsed navbar content -->
			<a class="btn btn-navbar" data-toggle="collapse" data-target=".nav-collapse">
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
				<span class="icon-bar"></span>
			</a>

			<!-- Be sure to leave the brand out there if you want it shown -->
			<a class="brand" href="#">Sneezy T</a>

			<!-- Everything you want hidden at 940px or less, place within here -->
			<div class="nav-collapse">
				<ul class="nav">
				      <li class="active"><a href="#" id="nav-add-meal">Add Meal</a></li>
				      <li><a href="#" id="nav-add-event">Add Event</a></li>
				      <li><a href="#" id="nav-meal-list">Meal List</a></li>
				      <li><a href="#" id="nav-event-list">Event List</a></li>
				</ul>
			</div>
		</div>
	</div>
</div>
<div class="content-pane">
	<div id="container-add-meal" class="content-pane-container" >
	<?php echo $add_meal;?>
	</div>
	<div id="container-add-event"  class="content-pane-container hide ">
	<?php echo $add_event;?>
	</div>
	<div id="container-meal-list"  class="content-pane-container hide">
	<?php echo $meal_list;?>
	</div>
	<div id="container-event-list"  class="content-pane-container hide">
	<?php echo $event_list;?>
	</div>
</div>
<script>
  	sneezySingleton.getInstance().initializeNav();
</script>
</body>
</html>