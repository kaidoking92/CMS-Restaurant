<script src="/lib/amcharts5/index.js"></script>
<script src="/lib/amcharts5/xy.js"></script>
<h1>Dashboard</h1>

<div id="main-chart" style="width: 100%;
  height: 500px;
max-width: 100%"></div>

<script type="text/javascript">
	<?php
	if(!empty($checkouts)){
		$data = [];
		foreach ($checkouts as $checkout) {
			$date = new DateTimeImmutable($checkout->getCreatedAt());
			$date = $date->format('d-m-Y');
			if(!array_key_exists($date, $data)){
				$data[$date] = 1;
			}else{
				$data[$date] += 1;
			}
		}
		$trueData = [];
		$keys = array_keys($data);
		foreach($keys as $key){
			$trueData[] = ['date'=>$key,'value'=>$data[$key]];
		}
	?>
		// Root
		var root = am5.Root.new("main-chart");


		// Chart
		var chart = root.container.children.push(
			am5xy.XYChart.new(
		    	root, {
		    		panX: true,
		    		panY: true,
		    		wheelX: "panX",
		    		wheelY: "zoomX",
		    		pinchZoomX: true
		    	}
			)
		);


		// Cursor
		var cursor = chart.set(
			"cursor", am5xy.XYCursor.new(
				root, {
		  			behavior: "none"
				}
			)
		);
		cursor.lineY.set("visible", false);


		// Data
		var date = new Date();
		date.setHours(0, 0, 0, 0);
		var value = 100;

		function generateData() {
		  value = Math.round((Math.random() * 10 - 5) + value);
		  am5.time.add(date, "day", 1);
		  return {
		    date: date.getTime(),
		    value: value
		  };
		}

		function generateDatas(count) {
		  var data = [];
		  for (var i = 0; i < count; ++i) {
		    data.push(generateData());
		  }
		  return data;
		}


		// Axis
		var xAxis = chart.xAxes.push(am5xy.DateAxis.new(root, {
		  maxDeviation: 0.2,
		  baseInterval: {
		    timeUnit: "day",
		    count: 1
		  },
		  renderer: am5xy.AxisRendererX.new(root, {}),
		  tooltip: am5.Tooltip.new(root, {})
		}));

		var yAxis = chart.yAxes.push(am5xy.ValueAxis.new(root, {
		  renderer: am5xy.AxisRendererY.new(root, {})
		}));


		// Series
		var series = chart.series.push(am5xy.LineSeries.new(root, {
		  name: "Series",
		  xAxis: xAxis,
		  yAxis: yAxis,
		  valueYField: "value",
		  valueXField: "date",
		  tooltip: am5.Tooltip.new(root, {
		    labelText: "{valueY}"
		  })
		}));

		series.data.processor = am5.DataProcessor.new(root, {
	  dateFormat: "dd-MM-yyyy",
	  dateFields: ["date"]
	});


		// Scrollbar
		chart.set("scrollbarX", am5.Scrollbar.new(root, {
		  orientation: "horizontal"
		}));


		// Set data
		var data = <?= json_encode($trueData); ?>;
		series.data.setAll(data);
		<?php
	} ?>
</script>