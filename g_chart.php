<?php
/**
 * CakePHP helper that acts as a wrapper for Google's Visualization JS Package.
 */
class GChartHelper extends AppHelper {
   public $helpers = array('Html', 'Session', 'Javascript');

   /**
    * Available visualization types
    *
    * @var <array>
    */
   private $chart_types = array(
      'area' => array(
         'method'=>'AreaChart',
         'data_method'=>'setValue',
         'package' => 'areachart',
         'loaded' => false
      ),
      'bar' => array(
         'method' => 'BarChart',
         'data_method' => 'setValue',
         'package' => 'barchart',
         'loaded' => false
      ),
      'pie' => array(
         'method' => 'PieChart',
         'data_method' => 'setValue',
         'package' => 'piechart',
         'loaded' => false
      ),
      'line' => array(
         'method' => 'LineChart',
         'data_method' => 'setValue',
         'package' => 'linechart',
         'loaded' => false
      )
   );

   /**
    * Default options
    *
    * @var <array>
    */
   private $defaults = array(
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
    * @param <string> $name
    * @param <array> $options
    * @return <string> Div tag output
    */
   public function start($name, $options=array()) {
      $options = array_merge(array('id' => $name), $options);
      $o = $this->Html->tag('div', '', $options);
      return $o;
   }

   /**
    * Returns javascript that will create the visualization requested.
    *
    * @param <type> $name
    * @param <type> $data
    * @return <string>
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
    * @param <type> $data
    * @param <type> $graph_type
    * @return <type>
    */
   private function loadDataAndLabels($data, $graph_type) {
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
    * @param <type> $type
    * @return <type>
    */
   private function loadPackage($type) {
      $o = '';
      if(!$this->chart_types[$type]['loaded']) {
         $o.= '<script type="text/javascript">'."\n";
         $o.= 'google.load("visualization", "1", {packages:["'.$this->chart_types[$type]['package'].'"]});'."\n";
         $o.= '</script>'."\n";
         $this->chart_types[$type]['loaded'] = true;
      }
      return $o;
   }

   /**
    * Returns javascript to instantiate the Google visualization package.
    *
    * @param <type> $name
    * @param <type> $type
    * @return <type>
    */
   private function instantiateGraph($name, $type='area') {
      $o = "var chart = new google.visualization.{$this->chart_types[$type]['method']}(document.getElementById('$name'));";
      return $o;
   }
}
