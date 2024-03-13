<?php
/**
 * Interface QRDataInterface
 *
 * @filesource   QRDataInterface.php
 * @created      01.12.2015
 * @package      RefPro\QRCode\Data
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace RefPro\QRCode\Data;

/**
 *
 */
interface QRDataInterface{

	/**
	 * @param string $data
	 *
	 * @return \RefPro\QRCode\Data\QRDataInterface
	 */
	public function setData($data);

	/**
	 * @param int  $maskPattern
	 * @param bool $test
	 *
	 * @return \RefPro\QRCode\Data\QRMatrix
	 */
	public function initMatrix($maskPattern, $test = null);

}
