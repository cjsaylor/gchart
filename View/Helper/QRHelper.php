<?php
/**
 * CakePHP helper that acts as a wrapper for Google's QR Code image generator.
 */
class QRHelper extends AppHelper {

	const API = 'http://chart.apis.google.com/chart';

	public $helpers = array('Html');

	protected $defaults = array(
		'chs' => '300x300',
		'cht' => 'qr'
	);

	public function image($qrString = '', $options = array(), $tagOptions = array()) {
		$settings = array_merge($this->defaults, $options);
		$settings['chl'] = $qrString;
		return $this->Html->image(static::API . '?' . http_build_query($settings), $tagOptions);
	}

}