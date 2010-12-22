# GChart Helper
Google visualization API wrapper helper for CakePHP

## Purpose
To add a simple API within CakePHP to create line, bar, area, and pie charts.

## Installation

- Clone g_chart into your helpers directory (either main app folder or plugin)

	`git clone git://github.com/cjsaylor/Google-visualization-api-cakephp.git . `

- Add as a git submodule

	`git submodule add git://github.com/cjsaylor/Google-visualization-api-cakephp.git . `

## Example

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

        echo $this->GChart->start('test');
        echo $this->GChart->visualize('test', $data);

Produces the following:

![Piston Ring Diameter Example Line Graph](http://img.chris-saylor.com/g_chart_example1.png "Line Chart Example")

## Notes

Currently Supports the following visualizations:

- Area Chart
- Bar Chart
- Pie Chart
- Line Chart

I have plans to add support with additional visualization (namely, Gauge is next on the list).

## Copyright (c) 2010 Chris Saylor

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in
all copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN
THE SOFTWARE.

