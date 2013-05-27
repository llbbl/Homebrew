<div>
	<h1>Add <?php echo $header; ?></h1>
	<form action="post">
		<div class="ui-widget">
  				<label for="<?php echo $name; ?>-types"><?php echo ucfirst($name); ?> Type: </label>
  				<input id="<?php echo $name; ?>-types" />
		</div>
		
		<div class="ui-widget">
  				<label for="<?php echo $name; ?>-note">Note: </label>
  				<input id="<?php echo $name; ?>-note" />
		</div>
		
		<div id="<?php echo $name; ?>-date-container" class="ui-widget visible-desktop visible-tablet">
  				<label for="<?php echo $name; ?>-date">Date: </label>
  				<input id="<?php echo $name; ?>-date" value="<?php echo date("m/d/Y h:i a"); ?>"/>
		</div>
		
		<div id="<?php echo $name; ?>-date-wheel-container" class="ui-widget visible-phone">
  				<label for="<?php echo $name; ?>-date-wheel">Date: </label>
  				<input id="<?php echo $name; ?>-date-wheel" type="datetime-local"/>
		</div>
		
		<div id='add-<?php echo $name; ?>-submit' >
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
	<div id="<?php echo $name; ?>-response" class="alert_response"></div>
</div>
<script>
  	sneezySingleton.getInstance().initializeAdd('<?php echo $name; ?>');
</script>
