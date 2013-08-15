<div id="type-merge">
	<h1>Merge Food Types</h1>
	<form action="post">
		<div class="ui-widget">
  				<label for="type-merge-from">From Type: </label>
  				<input id="type-merge-from" value="" />
		</div>
		<div class="ui-widget">
			<label for="type-merge-to">To Type:</label>
  			<input id="type-merge-to" value=""/>
  		</div>
		<div id='type-merge-submit' class="clear">
			<button class="btn btn-primary" type="button">Submit</button>
		</div>
	</form>
	<div id="merge-response" class="alert_response"></div>
</div>
<script>
	sneezySingleton.getInstance().initializeMergeType();
</script>
