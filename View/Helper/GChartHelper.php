<?php

App::uses('AppHelper', 'View/Helper');
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
		return $this->Html->tag('div', '', $options);
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

		$o = $this->loadPackage($data['type']) . "\n";
		$drawTemplate = '
<script type="text/javascript">
	google.setOnLoadCallback(function() {
		var data = new google.visualization.DataTable(%s);
		%s
		chart.draw(data, {
			width: %s,
			height: %s,
			is3D: %s,
			legend: "%s",
			title: "%s"
		});
	});
</script>
		';
		$o .= sprintf($drawTemplate, 
			$this->loadDataAndLabels($data), 
			$this->instantiateGraph($name, $data['type']), 
			$data['width'],
			$data['height'],
			$data['is3D'],
			$data['legend'],
			$data['title']
		);
		return trim($o);
   }

	/**
	 * Returns json representation of labels and data for the visualization constructor.
	 *
	 * @param array $data
	 * @return string
	 */
	protected function loadDataAndLabels($data) {
		$formatted = array(
			'cols' => array(),
			'rows' => array()
		);
		foreach ($data['labels'] as $labels) {
			foreach ($labels as $type => $label) {
				$formatted['cols'][] = compact('label', 'type');
			}
		}
		foreach ($data['data'] as $datum) {
			$keyVals = array();
			foreach ($datum as $entry) {
				$keyVals[] = array(
					'v' => $entry
				);
			}
			$formatted['rows'][] = array(
				'c' => $keyVals
			);
		}
		return json_encode($formatted);
	}

	/**
	 * Loads the specific visualization package.  Will only load a package once.
	 *
	 * @param string $type
	 * @return string
	 */
	protected function loadPackage($type) {
		if (!empty($this->packages_loaded[$this->chart_types[$type]['package']])) {
			return '';
		}
		$packageTemplate = '
<script type="text/javascript">
	google.load("visualization", "1", {packages: ["%s"]});
</script>
		';
		$this->packages_loaded[$this->chart_types[$type]['package']] = true;
		return sprintf(trim($packageTemplate), $this->chart_types[$type]['package']);
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
