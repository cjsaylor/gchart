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
			'package' => 'corechart'
		),
		'bar' => array(
			'method' => 'BarChart',
			'package' => 'corechart'
		),
		'pie' => array(
			'method' => 'PieChart',
			'package' => 'corechart'
		),
		'line' => array(
			'method' => 'LineChart',
			'package' => 'corechart'
		),
		'table' => array(
			'method' => 'Table',
			'package' => 'table'
		),
		'geochart' => array(
			'method' => 'GeoChart',
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

		$o = $this->loadPackage($data['type']);
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
			$entry = array_map(function($entry) {
				return array('v' => $entry);
			}, $datum);
			$formatted['rows'][] = array(
				'c' => $entry
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
		$graphInitTemplate = '
var chart = new google.visualization.%s(document.getElementById("%s"));
		';
		return sprintf(trim($graphInitTemplate), $this->chart_types[$type]['method'], $name);
	}
}
