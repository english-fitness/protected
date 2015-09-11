<div style="position:fixed;bottom:10px;left:10px;width:160px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;">
	<span style="margin:3px"><b>Color legend</b></span>
	<div style="clear:both"></div>
	<div style="width:25px;height:15px;background-color:yellow;float:left;margin:3px"></div><span style="float:left">Available timeslot</span>
	<div style="clear:both"></div>
	<div style="width:25px;height:15px;background-color:darkgray;float:left;margin:3px"></div><span style="float:left">Closed</span>
	<div style="clear:both"></div>
	<div style="width:25px;height:15px;background-color:lime;float:left;margin:3px"></div><span style="float:left">Approved Session</span>
	<div style="clear:both"></div>
	<div style="width:25px;height:15px;background-color:darkgreen;float:left;margin:3px"></div><span style="float:left">Pending Session</span>
	<div style="clear:both"></div>
	<div style="width:25px;height:15px;background-color:turquoise;float:left;margin:3px"></div><span style="float:left">Ongoing Session</span>
	<div style="clear:both"></div>
	<div style="width:25px;height:15px;background-color:darkorange;float:left;margin:3px"></div><span style="float:left">Ended Session</span>
	<div style="clear:both"></div>
</div>
<div style="position:fixed;top:330px;left:10px;width:160px;padding:3px;border:solid 1px #ddd;box-sizing:border-box;background-color:white;box-shadow:1px 1px 1px #ddd;border-radius:3px;text-align:justify; display:none"
	 id="changingSchedule">
	<p><b>Changing schedule</b></p>
	<p>Click on an available slot to change schedule. Click the button bellow to cancel</p>
	<button id="cancelChangeSchedule" class="btn" style="margin-left: 35px">Cancel</button>
</div>
<script>
$(function(){
	$('#cancelChangeSchedule').click(function(){
		toggleChangeSchedule(false);
	});
});
</script>
