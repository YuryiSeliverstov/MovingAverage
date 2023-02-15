	<div class="container">			
		<h1><?=$title?></h1>       
			<canvas id="<?=$containerId?>" style="width: 100%; height: 65vh; background: #222; border: 1px solid #555652; margin-top: 10px;"></canvas>

			<script>
				var <?=$containerId?> = document.getElementById("<?=$containerId?>").getContext('2d');
    			var chartDays = new Chart(<?=$containerId?>, {
        		type: 'line',
		        data: {
		            labels: [<?=$labels?>],
		            datasets: 
		            [{
		                label: 'Average',
		                data: [<?=$avgValues?>],
		                bbackgroundColor: 'transparent',
		                borderColor:'rgba(255,99,132)',
		                borderWidth: 3
		            },

		            {
		            	label: 'Moving Average',
		                data: [<?=$maValues?>],
		                backgroundColor: 'transparent',
		                borderColor:'rgba(0,255,255)',
		                borderWidth: 3	
		            }]
		        },
		     
		        options: {
		            scales: {scales:{yAxes: [{beginAtZero: false}], xAxes: [{autoskip: true, maxTicketsLimit: 20}]}},
		            tooltips:{mode: 'index'},
		            legend:{display: true, position: 'top', labels: {fontColor: 'rgb(255,255,255)', fontSize: 16}}
		        }
		    });
			</script>
	    </div>