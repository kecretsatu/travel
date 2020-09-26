<?php
$registrantGM = json_encode($registrantGM);
$orderGM = json_encode($orderGM);
$messageGM = json_encode($messageGM);
?>
<div class="row">
	<div class="col-md-4 wow fadeInLeft animated">
		<!--<h1>Registrant</h1>-->
		<div class="stats-left ">
			<h4>Monthly</h4>
		</div><div class="stats-right">
			<label></label>
		</div>
		<div class="graph">
			<div id="registrantGM" class="graph-element"></div>
			<script></script>
		</div>
	</div>
	<div class="col-md-4 states-mdl wow fadeInLeft animated">
		<div class="stats-left ">
			<h4>Monthly</h4>
		</div><div class="stats-right">
			<label></label>
		</div>
		<div class="graph">
			<div id="orderGM" class="graph-element"></div>
			<script></script>
		</div>
	</div>
	<div class="col-md-4 states-last wow fadeInLeft animated">
		<div class="stats-left ">
			<h4>Monthly</h4>
		</div><div class="stats-right">
			<label></label>
		</div>
		<div class="graph">
			<div id="messageGM" class="graph-element"></div>
			<script></script>
		</div>
	</div>
	<!--<div class="col-md-8 wow fadeInLeft animated">
		<h1>&nbsp;</h1>
		<div class="stats-left ">
			<h5>Juni 2017</h5>
			<h4>Monthly</h4>
		</div><div class="stats-right">
			<label>10</label>
		</div>
		<div class="graph">
			<div id="registrantGD" class="graph-element"></div>
			<script></script>
		</div>
	</div>-->
</div>	

<script>
$(window).load(function(){
	<?php $color = "#026aa6"; ?>
	$("#registrantGM").buildGraph('area', ['Registrant'], <?php echo $registrantGM; ?>, 'month_name', ['total'], ['Registrant'], ['<?php echo $color; ?>']);
	
	<?php $color = "#7c38bc"; ?>
	$("#orderGM").buildGraph('area', ['Order'], <?php echo $orderGM; ?>, 'month_name', ['total'], ['Order'], ['<?php echo $color; ?>']);
	
	<?php $color = "#68b828"; ?>
	$("#messageGM").buildGraph('area', ['Message'], <?php echo $messageGM; ?>, 'month_name', ['total'], ['Message'], ['<?php echo $color; ?>']);
});
</script>