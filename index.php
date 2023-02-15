<?php
	require_once('mvClass.php');
	
	use Math\MovingAverage;
	
	$mv		=	new MovingAverage();
	$days	=	$mv->getGraphDataDays(24);
	$weeks	=	$mv->getGraphDataWeeks(4);
	$months	=	$mv->getGraphDataMonths(30);
?>

<!DOCTYPE html>
<html>
	<head>
    	<meta name="viewport" content="width=device-width, initial-scale=1.0">
		<script type="text/javascript" src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.2/Chart.bundle.min.js"></script>
		<title>Moving Average / Average</title>

		<style type="text/css">			
			body{
				font-family: Arial;
			    margin: 80px 100px 10px 100px;
			    padding: 0;
			    color: white;
			    text-align: center;
			    background: #555652;
			}

			.container {
				color: #E8E9EB;
				background: #222;
				border: #555652 1px solid;
				padding: 10px;
			}
		</style>

	</head>

	<body>
		<h1>&copy; 2023 Yuryi Seliverstov</h1>
		<h3><a style="color:white" href="https://t.me/yuryi_seliverstov" target="_blank">https://t.me/yuryi_seliverstov</a></h3>
	    <?php $mv->renderChunk('Days',$days,'days');?>
		<?php $mv->renderChunk('Weeks',$weeks,'weeks');?>
		<?php $mv->renderChunk('Months',$months,'months');?>
	</body>
</html>