<?php
/**
 * Class BitBuffer
 *
 * @filesource   BitBuffer.php
 * @created      25.11.2015
 * @package      RefPro\QRCode\Helpers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace RefPro\QRCode\Helpers;

class BitBuffer{

	/**
	 * @var  int[]
	 */
	public $buffer = array();

	/**
	 * @var int
	 */
	public $length = 0;

	/**
	 * @return \RefPro\QRCode\Helpers\BitBuffer
	 */
	public function clear(){
		$this->buffer = array();
		$this->length = 0;

		return $this;
	}

	/**
	 * @param int $num
	 * @param int $length
	 *
	 * @return \RefPro\QRCode\Helpers\BitBuffer
	 */
	public function put($num, $length){

		for($i = 0; $i < $length; $i++){
			$this->putBit((($num >> ($length - $i - 1))&1) === 1);
		}

		return $this;
	}

	/**
	 * @param bool $bit
	 *
	 * @return \RefPro\QRCode\Helpers\BitBuffer
	 */
	public function putBit($bit){
		$bufIndex = floor($this->length / 8);

		if(count($this->buffer) <= $bufIndex){
			$this->buffer[] = 0;
		}

		if($bit === true){
			$this->buffer[(int)$bufIndex] |= (0x80 >> ($this->length % 8));
		}

		$this->length++;

		return $this;
	}

}
