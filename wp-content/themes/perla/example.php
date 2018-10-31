<div style="width: 730px; margin: 20px auto; font-family:sans-serif;">
<?php
/** Include class */
include( 'GoogChart.class.php' );

/** Create chart */
$chart = new GoogChart();

 
/*

		Example 1
		Pie chart

*/

// Set graph data
$data = array(
			'IE7' => 22,
			'IE6' => 30.7,
			'IE5' => 1.7,
			'Firefox' => 36.5,
			'Mozilla' => 1.1,
			'Safari' => 2,
			'Opera' => 1.4,
		);

// Set graph colors
$color = array(
			'#99C754',
			'#54C7C5',
			'#999999',
		);

/* # Chart 1 # */
echo '<h2>Pie chart</h2>';
$chart->setChartAttrs( array(
	'type' => 'pie',
	'title' => 'Browser market 2008',
	'data' => $data,
	'size' => array( 400, 300 ),
	'color' => $color
	));
// Print chart
echo $chart;


/*

		Example 2
		Bar graph
		Multiple data

*/

// Set multiple graph data
	$dataMultiple = array( 
//		'February 2008' => array(12,87,75,41,23,96,68,71,34,9),
//		'January 2008' => array(98,60,27,34,56,79,58,74,18,76)
		'x' => array(
			'M0' => 0,
			'M1' => 10,
			'M2' => 20,
			'M3' => 30,
			'M4' => 40,
			'M5' => 50,
			'M6' => 60,
			'M7' => 70
			),
		'y' => array(
			'M0' => 0,
			'M1' => 87,
			'M2' => 75,
			'M3' => 41,
			'M4' => 23,
			'M5' => 96,
			'M6' => 68,
			'M7' => 71
			)		
	);

/* # Chart 2 # */
echo '<h2>Vertical Bar</h2>';
$chart->setChartAttrs( array(
	'type' => 'scatter',
	'title' => 'Browser market 2008',
	'data' => $dataMultiple,
	'size' => array( 550, 200 ),
	'color' => $color,
	'labelsXY' => true,
	'legend' => false,
	));
// Print chart
echo $chart;

/*

		Example 3
		Timeline
		Multiple data

*/

// Set timeline graph data
$dataTimeline = array( 
		'2007' => array(
			'January' => 31.0,
			'February' => 31.2,
			'March' => 31.8,
			'April' => 32.9,
			'May' => 33.7,
			'June' => 34.0,
			'July' => 34.5,
			'August' => 34.9,
			'September' => 35.4,
			'Oktober' => 36.0,
			'November' => 36.3,
			'December' => 36.3,
			),
		'2006' => array(
			'January' => 25.0,
			'February' => 24.5,
			'March' => 24.5,
			'April' => 22.9,
			'May' => 22.9,
			'June' => 25.5,
			'July' => 25.5,
			'August' => 24.9,
			'September' => 27.3,
			'Oktober' => 27.3,
			'November' => 29.9,
			'December' => 29.9,
			),
		'2005' => array(
			'January' => 15.0,
			'February' => 14.5,
			'March' => 14.5,
			'April' => 12.9,
			'May' => 12.9,
			'June' => 15.5,
			'July' => 15.5,
			'August' => 14.9,
			'September' => 17.3,
			'Oktober' => 17.3,
			'November' => 19.9,
			'December' => 19.9,
			),
	);

/* # Chart 3 # */
echo '<h2>Timeline</h2>';
$chart->setChartAttrs( array(
	'type' => 'sparkline',
	'title' => 'Firefox market share (%) 2006-07',
	'data' => $dataTimeline,
	'size' => array( 600, 200 ),
	'color' => $color,
	'labelsXY' => true,
	'fill' => array( '#eeeeee', '#aaaaaa' ),
	));
// Print chart
echo $chart;
?>
</div>