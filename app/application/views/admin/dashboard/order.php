<?php
$orderGM = json_encode($orderGM);
?>
<div class="row">
	<div class="col-md-4 wow fadeInLeft animated">
		<h1>Order</h1>
		<div class="stats-left ">
			<h5>Juni 2017</h5>
			<h4>Monthly</h4>
		</div><div class="stats-right">
			<label>10</label>
		</div>
		<div class="graph">
			<div id="orderGM" class="graph-element"></div>
			<script></script>
		</div>
	</div>
	<div class="col-md-8 wow fadeInLeft animated">
		<h1>&nbsp;</h1>
		<div class="stats-left ">
			<h5>Juni 2017</h5>
			<h4>Monthly</h4>
		</div><div class="stats-right">
			<label>10</label>
		</div>
		<div class="graph">
			<div id="orderGD" class="graph-element"></div>
			<script></script>
		</div>
	</div>
</div>	

<script>
$(window).load(function(){
	<?php $color = "#9c2b26"; ?>
	$("#orderGM").buildGraph('area', ['Registrant'], <?php echo $orderGM; ?>, 'month_name', ['total'], ['Registrant'], ['<?php echo $color; ?>']);
});
</script>