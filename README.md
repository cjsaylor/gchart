# GChart [![Build Status](https://travis-ci.org/cjsaylor/gchart.png)](https://travis-ci.org/cjsaylor/gchart)

Google visualization API wrapper helper for CakePHP that adds a simple API within CakePHP to create line, bar, area, and pie charts.

## Requirements

* PHP 5.3+
* CakePHP 2.* (1.3 is no longer supported, but available in the `1.3` branch)

## Installation

### Using Composer

Ensure require is present in composer.json. This will install the plugin into Plugin/DebugKit:

```javascript
{
    "require": {
        "cjsaylor/gchart": "2.0.*"
    }
}
```

### Using git submodules or downloaded source

* Add the submodule as a plugin. (or simply copy the contents)

`git clone git://github.com/cjsaylor/gchart.git app/Plugin/GChart`

### CakePHP Setup

* Load the plugin in your bootstrap.

```php
CakePlugin::load('GChart');
```

* Make available as a helper if you app.

```php
$this->helpers[] = 'GChart.GChart';
```

* Include Google's jsapi script in your layout/view.

```html
<script src="https://www.google.com/jsapi"></script>
```

## Example

### In your controller

```php
<?php

$data = array(
  'labels' => array(
    array('string' => 'Sample'),
    array('number' => 'Piston 1'),
    array('number' => 'Piston 2')
  ),
  'data' => array(
    array('S1', 74.01, 74.03),
    array('S2', 74.05, 74.04),
    array('S3', 74.03, 74.01),
    array('S4', 74.00, 74.02),
    array('S5', 74.12, 74.05),
    array('S6', 74.04, 74.04),
    array('S7', 74.05, 74.06),
    array('S8', 74.03, 74.02),
    array('S9', 74.01, 74.03),
    array('S10', 74.04, 74.01),
  ),
  'title' => 'Piston Ring Diameter',
  'type' => 'line'
);
```

### In your view

```php
<?php

echo $this->GChart->start('test');
echo $this->GChart->visualize('test', $data);
```

Produces the following:

![Piston Ring Diameter Example Line Graph](http://assets.chris-saylor.com/img/g_chart_example1.png "Line Chart Example")

HTML Output:

```html
<div id="test"></div>
<script type="text/javascript">
  google.load("visualization", "1", {packages: ["corechart"]});
</script>
<script type="text/javascript">
  google.setOnLoadCallback(function() {
    var data = new google.visualization.DataTable({"cols":[{"label":"Sample","type":"string"},{"label":"Piston 1","type":"number"},{"label":"Piston 2","type":"number"}],"rows":[{"c":[{"v":"S1"},{"v":74.01},{"v":74.03}]},{"c":[{"v":"S2"},{"v":74.05},{"v":74.04}]},{"c":[{"v":"S3"},{"v":74.03},{"v":74.01}]},{"c":[{"v":"S4"},{"v":74},{"v":74.02}]},{"c":[{"v":"S5"},{"v":74.12},{"v":74.05}]},{"c":[{"v":"S6"},{"v":74.04},{"v":74.04}]},{"c":[{"v":"S7"},{"v":74.05},{"v":74.06}]},{"c":[{"v":"S8"},{"v":74.03},{"v":74.02}]},{"c":[{"v":"S9"},{"v":74.01},{"v":74.03}]},{"c":[{"v":"S10"},{"v":74.04},{"v":74.01}]}]});
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
```

## Notes

Currently Supports the following visualizations:

- Area Chart
- Bar Chart
- Pie Chart
- Line Chart
- Table
- Geo Chart

## License

MIT License, see [license.txt](license.txt)

