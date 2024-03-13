<?php
/**
 * Class Byte
 *
 * @filesource   Byte.php
 * @created      25.11.2015
 * @package      RefPro\QRCode\Data
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace RefPro\QRCode\Data;

use RefPro\QRCode\QRCode;

require_once __DIR__.'/QRDataAbstract.php';

/**
 * Byte mode, ISO-8859-1 or UTF-8
 */
class Byte extends QRDataAbstract{

	/**
	 * @inheritdoc
	 */
	protected $datamode = QRCode::DATA_BYTE;

	/**
	 * @inheritdoc
	 */
	protected $lengthBits = array(8, 16, 16);

	/**
	 * @inheritdoc
	 */
	protected function write($data){
		$i = 0;

		while($i < $this->strlen){
			$this->bitBuffer->put(ord($data[$i]), 8);
			$i++;
		}

	}

}
