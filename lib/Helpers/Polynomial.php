<?php
/**
 * Class Polynomial
 *
 * @filesource   Polynomial.php
 * @created      25.11.2015
 * @package      RefPro\QRCode\Helpers
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace RefPro\QRCode\Helpers;

use RefPro\QRCode\QRCodeException;

/**
 * @link http://www.thonky.com/qr-code-tutorial/error-correction-coding
 */
class Polynomial{

	/**
	 * @link http://www.thonky.com/qr-code-tutorial/log-antilog-table
	 */
	protected static $table = array(
		array(  1,   0), array(  2,   0), array(  4,   1), array(  8,  25), array( 16,   2), array( 32,  50), array( 64,  26), array(128, 198),
		array( 29,   3), array( 58, 223), array(116,  51), array(232, 238), array(205,  27), array(135, 104), array( 19, 199), array( 38,  75),
		array( 76,   4), array(152, 100), array( 45, 224), array( 90,  14), array(180,  52), array(117, 141), array(234, 239), array(201, 129),
		array(143,  28), array(  3, 193), array(  6, 105), array( 12, 248), array( 24, 200), array( 48,   8), array( 96,  76), array(192, 113),
		array(157,   5), array( 39, 138), array( 78, 101), array(156,  47), array( 37, 225), array( 74,  36), array(148,  15), array( 53,  33),
		array(106,  53), array(212, 147), array(181, 142), array(119, 218), array(238, 240), array(193,  18), array(159, 130), array( 35,  69),
		array( 70,  29), array(140, 181), array(  5, 194), array( 10, 125), array( 20, 106), array( 40,  39), array( 80, 249), array(160, 185),
		array( 93, 201), array(186, 154), array(105,   9), array(210, 120), array(185,  77), array(111, 228), array(222, 114), array(161, 166),
		array( 95,   6), array(190, 191), array( 97, 139), array(194,  98), array(153, 102), array( 47, 221), array( 94,  48), array(188, 253),
		array(101, 226), array(202, 152), array(137,  37), array( 15, 179), array( 30,  16), array( 60, 145), array(120,  34), array(240, 136),
		array(253,  54), array(231, 208), array(211, 148), array(187, 206), array(107, 143), array(214, 150), array(177, 219), array(127, 189),
		array(254, 241), array(225, 210), array(223,  19), array(163,  92), array( 91, 131), array(182,  56), array(113,  70), array(226,  64),
		array(217,  30), array(175,  66), array( 67, 182), array(134, 163), array( 17, 195), array( 34,  72), array( 68, 126), array(136, 110),
		array( 13, 107), array( 26,  58), array( 52,  40), array(104,  84), array(208, 250), array(189, 133), array(103, 186), array(206,  61),
		array(129, 202), array( 31,  94), array( 62, 155), array(124, 159), array(248,  10), array(237,  21), array(199, 121), array(147,  43),
		array( 59,  78), array(118, 212), array(236, 229), array(197, 172), array(151, 115), array( 51, 243), array(102, 167), array(204,  87),
		array(133,   7), array( 23, 112), array( 46, 192), array( 92, 247), array(184, 140), array(109, 128), array(218,  99), array(169,  13),
		array( 79, 103), array(158,  74), array( 33, 222), array( 66, 237), array(132,  49), array( 21, 197), array( 42, 254), array( 84,  24),
		array(168, 227), array( 77, 165), array(154, 153), array( 41, 119), array( 82,  38), array(164, 184), array( 85, 180), array(170, 124),
		array( 73,  17), array(146,  68), array( 57, 146), array(114, 217), array(228,  35), array(213,  32), array(183, 137), array(115,  46),
		array(230,  55), array(209,  63), array(191, 209), array( 99,  91), array(198, 149), array(145, 188), array( 63, 207), array(126, 205),
		array(252, 144), array(229, 135), array(215, 151), array(179, 178), array(123, 220), array(246, 252), array(241, 190), array(255,  97),
		array(227, 242), array(219,  86), array(171, 211), array( 75, 171), array(150,  20), array( 49,  42), array( 98,  93), array(196, 158),
		array(149, 132), array( 55,  60), array(110,  57), array(220,  83), array(165,  71), array( 87, 109), array(174,  65), array( 65, 162),
		array(130,  31), array( 25,  45), array( 50,  67), array(100, 216), array(200, 183), array(141, 123), array(  7, 164), array( 14, 118),
		array( 28, 196), array( 56,  23), array(112,  73), array(224, 236), array(221, 127), array(167,  12), array( 83, 111), array(166, 246),
		array( 81, 108), array(162, 161), array( 89,  59), array(178,  82), array(121,  41), array(242, 157), array(249,  85), array(239, 170),
		array(195, 251), array(155,  96), array( 43, 134), array( 86, 177), array(172, 187), array( 69, 204), array(138,  62), array(  9,  90),
		array( 18, 203), array( 36,  89), array( 72,  95), array(144, 176), array( 61, 156), array(122, 169), array(244, 160), array(245,  81),
		array(247,  11), array(243, 245), array(251,  22), array(235, 235), array(203, 122), array(139, 117), array( 11,  44), array( 22, 215),
		array( 44,  79), array( 88, 174), array(176, 213), array(125, 233), array(250, 230), array(233, 231), array(207, 173), array(131, 232),
		array( 27, 116), array( 54, 214), array(108, 244), array(216, 234), array(173, 168), array( 71,  80), array(142,  88), array(  1, 175),
	);

	/**
	 * @var array
	 */
	protected $num = array();

	/**
	 * Polynomial constructor.
	 *
	 * @param array|null $num
	 * @param int|null   $shift
	 */
	public function __construct(array $num = null, $shift = null){
		$this->setNum(isset($num) ? $num : array(1), $shift);
	}

	/**
	 * @return array
	 */
	public function getNum(){
		return $this->num;
	}

	/**
	 * @param array    $num
	 * @param int|null $shift
	 *
	 * @return \RefPro\QRCode\Helpers\Polynomial
	 */
	public function setNum(array $num, $shift = null){
		$offset = 0;
		$numCount = count($num);

		while($offset < $numCount && $num[$offset] === 0){
			$offset++;
		}

		$this->num = array_fill(0, $numCount - $offset + (isset($shift) ? $shift : 0), 0);

		for($i = 0; $i < $numCount - $offset; $i++){
			$this->num[$i] = $num[$i + $offset];
		}

		return $this;
	}

	/**
	 * @param array $e
	 *
	 * @return \RefPro\QRCode\Helpers\Polynomial
	 */
	public function multiply(array $e){
		$n = array_fill(0, count($this->num) + count($e) - 1, 0);

		foreach($this->num as $i => $vi){
			$vi = $this->glog($vi);

			foreach($e as $j => $vj){
				$n[$i + $j] ^= $this->gexp($vi + $this->glog($vj));
			}

		}

		$this->setNum($n);

		return $this;
	}

	/**
	 * @param array $e
	 *
	 * @return \RefPro\QRCode\Helpers\Polynomial
	 */
	public function mod(array $e){
		$n = $this->num;

		if(count($n) - count($e) < 0){
			return $this;
		}

		$ratio = $this->glog($n[0]) - $this->glog($e[0]);

		foreach($e as $i => $v){
			$n[$i] ^= $this->gexp($this->glog($v) + $ratio);
		}

		$this->setNum($n)->mod($e);

		return $this;
	}

	/**
	 * @param int $n
	 *
	 * @return int
	 * @throws \RefPro\QRCode\QRCodeException
	 */
	public function glog($n){

		if($n < 1){
			throw new QRCodeException('log('.$n.')');
		}

		return Polynomial::$table[$n][1];
	}

	/**
	 * @param int $n
	 *
	 * @return int
	 */
	public function gexp($n){

		if($n < 0){
			$n += 255;
		}
		elseif($n >= 256){
			$n -= 255;
		}

		return Polynomial::$table[$n][0];
	}

}
