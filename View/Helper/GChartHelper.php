<?php
/**
 * CakePHP helper that acts as a wrapper for Google's Visualization JS Package.
 */
class GChartHelper extends AppHelper {

	public $helpers = array('Html');

	/**
	* Available visualization types
	*
	* @var array
	*/
	protected $chart_types = array(
		'area' => array(
			'method'=>'AreaChart',
			'data_method'=>'setValue',
			'package' => 'corechart'
		),
		'bar' => array(
			'method' => 'BarChart',
			'data_method' => 'setValue',
			'package' => 'corechart'
		),
		'pie' => array(
			'method' => 'PieChart',
			'data_method' => 'setValue',
			'package' => 'corechart'
		),
		'line' => array(
			'method' => 'LineChart',
			'data_method' => 'setValue',
			'package' => 'corechart'
		),
		'table' => array(
			'method' => 'Table',
			'data_method' => 'setCell',
			'package' => 'table'
		),
		'geochart' => array(
			'method' => 'GeoChart',
			'data_method' => 'setValue',
			'package' => 'geochart'
		)
	);

	protected $packages_loaded = array();

	/**
	 * Default options
	 *
	 * @var array
	 */
	protected $defaults = array(
		'title' => '',
		'type' => 'area',
		'width' => 450,
		'height' => 300,
		'is3D' => 'true',
		'legend' => 'bottom'
	);

	/**
	 * Creates a div tag meant to be filled with the Google visualization.
	 *
	 * @param string $name
	 * @param array $options
	 * @return string Div tag output
	 */
	public function start($name, $options=array()) {
		$options = array_merge(array('id' => $name), $options);
		$o = $this->Html->tag('div', '', $options);
		return $o;
	}

	/**
	 * Returns javascript that will create the visualization requested.
	 *
	 * @param string $name
	 * @param array $data
	 * @return string
	 */
	public function visualize($name, $data=array()) {
		$data = array_merge($this->defaults, $data);

		$o = $this->loadPackage($data['type']);
		$o.= '<script type="text/javascript">
			function drawChart'.$name.'() {
			var data = new google.visualization.DataTable();
		';
		$o.= $this->loadDataAndLabels($data, $data['type']);
		$o.= $this->instantiateGraph($name, $data['type']);
		$o.= "chart.draw(data, {width: {$data['width']}, height: {$data['height']}, is3D: {$data['is3D']}, legend: '{$data['legend']}', title: '{$data['title']}'});";
		$o.= "}";
		$o.= "google.setOnLoadCallback(drawChart$name);";
		$o.= "</script>";
		return $o;
   }

	/**
	 * Returns javascript that adds the data and label to be used in the visualization.
	 *
	 * @param array $data
	 * @param string $graph_type
	 * @return string
	 */
	protected function loadDataAndLabels($data, $graph_type) {
		$o = '';
		foreach($data['labels'] as $label) {
			foreach($label as $type => $label_name) {
				$o.= "data.addColumn('$type', '$label_name');\n";
			}
		}
		$data_count = count($data['data']);
		$label_count = count($data['labels']);
		$o.= "data.addRows($data_count);\n";
		for($i = 0; $i < $data_count; $i++) {
			for($j=0; $j < $label_count; $j++) {
				$value = $data['data'][$i][$j];
				$type = key($data['labels'][$j]);
				if($type == 'string') {
					$o.= "data.{$this->chart_types[$graph_type]['data_method']}($i, $j, '$value');\n";
				} else {
					$o.= "data.{$this->chart_types[$graph_type]['data_method']}($i, $j, $value);\n";
				}
			}
		}
		return $o;
	}

	/**
	 * Loads the specific visualization package.  Will only load a package once.
	 *
	 * @param string $type
	 * @return string
	 */
	protected function loadPackage($type) {
		$o = '';
		if(!in_array($this->chart_types[$type]['package'], $this->packages_loaded)) {
			$o.= '<script type="text/javascript">'."\n";
			$o.= 'google.load("visualization", "1", {packages:["'.$this->chart_types[$type]['package'].'"]});'."\n";
			$o.= '</script>'."\n";
			$this->packages_loaded[] = $this->chart_types[$type]['package'];
		}
		return $o;
	}

	/**
	 * Returns javascript to instantiate the Google visualization package.
	 *
	 * @param string $name
	 * @param string $type
	 * @return string
	 */
	protected function instantiateGraph($name, $type='area') {
		$o = "var chart = new google.visualization.{$this->chart_types[$type]['method']}(document.getElementById('$name'));";
		return $o;
	}
}
