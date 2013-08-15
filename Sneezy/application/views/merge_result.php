<div class="alert <?php echo $alert; ?>">
	<a class="close" data-dismiss="alert">×</a>
	<span>
<?php 
if ($result)
{
	echo "Merged $from ($from_id) to $to ($to_id)";
}
else
{
	echo "Unable to merge $from to $to";
}
	
?>
	</span>
</div>