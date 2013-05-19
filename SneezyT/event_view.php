<!DOCTYPE html>
<html lang="en">
<head>
<title>Sneezy T Food Tracker</title>
	<!-- jQuery -->
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-1.9.1.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-ui-1.10.2.custom.min.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery-ui-timepicker-addon.js"></script>
	
	<!-- explore jQuery ui touch -->
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery.ui.touch.js"></script>
	<script src="<?php echo base_url();?>js/jquery-ui/js/jquery.ui.ipad.altfix.js"></script>
	
	<!-- Bootstrap -->
	<script src="<?php echo base_url();?>js/bootstrap/js/bootstrap.js"></script>
	
	<!-- jTables -->
	<script src="<?php echo base_url();?>js/jtable.2.3.0/jquery.jtable.min.js"></script>
	
	
	<!-- CSS -->
	<link rel=stylesheet href="<?php echo base_url();?>js/jquery-ui/css/ui-lightness/jquery-ui-1.10.2.custom.min.css" type="text/css" />
	<link rel=stylesheet href="<?php echo base_url();?>js/jtable.2.3.0/themes/metro/blue/jtable.min.css" rel="stylesheet" type="text/css" />
	<link rel=stylesheet href="<?php echo base_url();?>js/bootstrap/css/bootstrap.css" type="text/css" />
	
	<!-- handrolled css and js -->
	<script src="<?php echo base_url();?>js/sneezy.js"></script>
	<link rel=stylesheet href="<?php echo base_url();?>css/sneezy.css" type="text/css" />
	
	
</head>
<body >
<div id="eventGrid" class="grid" ></div>
<script>
  	sneezySingleton.getInstance().initializeEventList();
</script>


</body>
</html>