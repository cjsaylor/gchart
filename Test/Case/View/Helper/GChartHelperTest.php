<?php

App::uses('GChartHelper', 'GChart.View/Helper');
App::uses('View', 'View');
App::uses('HtmlHelper', 'View/Helper');

class GChartHelperTestCase extends CakeTestCase {

	public function setUp() {
		parent::setUp();

		$this->GChart = new GChartHelper(new View(null));
		$this->GChart->Html = new HtmlHelper(new View(null));
	}

	public function testStart() {
		$expected = '<div id="test"></div>';
		$this->assertEquals($expected, $this->GChart->start('test'));
	}

	public function testVisualizeBasic() {
		$data = array(
			'labels' => array(
				array('string' => 'Sample'),
				array('number' => 'Piston 1'),
				array('number' => 'Piston 2')
			),
			'data' => array(
			    array('S1', 74.01, 74.03),
			    array('S2', 74.05, 74.04),
			),
			'title' => 'Piston Ring Diameter',
			'type' => 'line'
		);
		$expected = '
<script type="text/javascript">
	google.load("visualization", "1", {packages: ["corechart"]});
</script>
<script type="text/javascript">
	google.setOnLoadCallback(function() {
		var data = new google.visualization.DataTable({"cols":[{"label":"Sample","type":"string"},{"label":"Piston 1","type":"number"},{"label":"Piston 2","type":"number"}],"rows":[{"c":[{"v":"S1"},{"v":74.01},{"v":74.03}]},{"c":[{"v":"S2"},{"v":74.05},{"v":74.04}]}]});
		var chart = new google.visualization.LineChart(document.getElementById("test"));
		chart.draw(data, {
			width: 450,
			height: 300,
			is3D: true,
			legend: "bottom",
			title: "Piston Ring Diameter"
		});
	});
</script>
		';
		$this->assertEquals(trim($expected), $this->GChart->visualize('test', $data));
	}

	public function testVisualizeMultipleCalls() {
		$data = array(
			'labels' => array(
				array('string' => 'Sample'),
				array('number' => 'Piston 1'),
				array('number' => 'Piston 2')
			),
			'data' => array(
			    array('S1', 74.01, 74.03),
			    array('S2', 74.05, 74.04),
			),
			'title' => 'Piston Ring Diameter',
			'type' => 'line'
		);
		$expected = '
<script type="text/javascript">
	google.setOnLoadCallback(function() {
		var data = new google.visualization.DataTable({"cols":[{"label":"Sample","type":"string"},{"label":"Piston 1","type":"number"},{"label":"Piston 2","type":"number"}],"rows":[{"c":[{"v":"S1"},{"v":74.01},{"v":74.03}]},{"c":[{"v":"S2"},{"v":74.05},{"v":74.04}]}]});
		var chart = new google.visualization.LineChart(document.getElementById("test"));
		chart.draw(data, {
			width: 450,
			height: 300,
			is3D: true,
			legend: "bottom",
			title: "Piston Ring Diameter"
		});
	});
</script>
		';
		$this->GChart->visualize('test', $data);
		$this->assertEquals(trim($expected), $this->GChart->visualize('test', $data));
	}

}
