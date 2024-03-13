<?php
/**
 * Class QRCode
 *
 * @filesource   QRCode.php
 * @created      26.11.2015
 * @package      RefPro\QRCode
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace RefPro\QRCode;

use RefPro\QRCode\Data\MaskPatternTester;
use RefPro\QRCode\Data\QRCodeDataException;
use RefPro\QRCode\Data\QRDataInterface;
use RefPro\QRCode\Data\QRDataAbstract;
use RefPro\QRCode\Data\QRMatrix;

use RefPro\QRCode\Output\QRCodeOutputException;
use RefPro\QRCode\Output\QRImage;
use RefPro\QRCode\Output\QRMarkup;
use RefPro\QRCode\Output\QROutputInterface;

use RefPro\Settings\SettingsContainerInterface;

require_once __DIR__.'/Output/QRCodeOutputException.php';
require_once __DIR__.'/Output/QRMarkup.php';
require_once __DIR__.'/Output/QRImage.php';
require_once __DIR__.'/Data/QRMatrix.php';
require_once __DIR__.'/Data/QRDataInterface.php';
require_once __DIR__.'/Data/QRDataAbstract.php';
require_once __DIR__.'/Data/QRCodeDataException.php';
require_once __DIR__.'/Data/Byte.php';
require_once __DIR__.'/Data/MaskPatternTester.php';

/**
 * Turns a text string into a Model 2 QR Code
 *
 * @link https://github.com/kazuhikoarase/qrcode-generator/tree/master/php
 * @link http://www.qrcode.com/en/codes/model12.html
 * @link http://www.thonky.com/qr-code-tutorial/
 */
class QRCode{

	/**
	 * API constants
	 */
	const OUTPUT_MARKUP_HTML = 'html';
	const OUTPUT_MARKUP_SVG  = 'svg';
	const OUTPUT_IMAGE_PNG   = 'png';
	const OUTPUT_IMAGE_JPG   = 'jpg';
	const OUTPUT_IMAGE_GIF   = 'gif';
	const OUTPUT_STRING_JSON = 'json';
	const OUTPUT_STRING_TEXT = 'text';
	const OUTPUT_IMAGICK     = 'imagick';
	const OUTPUT_CUSTOM      = 'custom';

	const VERSION_AUTO       = -1;
	const MASK_PATTERN_AUTO  = -1;

	const ECC_L         = 1; // 7%.
	const ECC_M         = 0; // 15%.
	const ECC_Q         = 3; // 25%.
	const ECC_H         = 2; // 30%.

	const DATA_NUMBER   = 1;
	const DATA_ALPHANUM = 2;
	const DATA_BYTE     = 4;
	const DATA_KANJI    = 8;

	public static $ECC_MODES = array(
		self::ECC_L => 0,
		self::ECC_M => 1,
		self::ECC_Q => 2,
		self::ECC_H => 3,
	);

	public static $DATA_MODES = array(
		self::DATA_NUMBER   => 0,
		self::DATA_ALPHANUM => 1,
		self::DATA_BYTE     => 2,
		self::DATA_KANJI    => 3,
	);

	public static $OUTPUT_MODES = array(
		'RefPro\QRCode\Output\QRMarkup' => array(
			self::OUTPUT_MARKUP_SVG,
			self::OUTPUT_MARKUP_HTML,
		),
		'RefPro\QRCode\Output\QRImage' => array(
			self::OUTPUT_IMAGE_PNG,
			self::OUTPUT_IMAGE_GIF,
			self::OUTPUT_IMAGE_JPG,
		),
	);

	/**
	 * @var \RefPro\QRCode\QROptions
	 */
	protected $options;

	/**
	 * @var \RefPro\QRCode\Data\QRDataInterface
	 */
	protected $dataInterface;

	/**
	 * @see http://php.net/manual/function.mb-internal-encoding.php
	 * @var string
	 */
	protected $mbCurrentEncoding;

	/**
	 * QRCode constructor.
	 *
	 * @param \RefPro\Settings\SettingsContainerInterface|null $options
	 */
	public function __construct(SettingsContainerInterface $options = null){
		// save the current mb encoding (in case it differs from UTF-8)
		$this->mbCurrentEncoding = mb_internal_encoding();
		// use UTF-8 from here on
		mb_internal_encoding('UTF-8');

		$this->options = isset($options) ? $options : new QROptions;
	}

	/**
	 * @return void
	 */
	public function __destruct(){
		// restore the previous mb_internal_encoding, so that we don't mess up the rest of the script
		mb_internal_encoding($this->mbCurrentEncoding);
	}

	/**
	 * Renders a QR Code for the given $data and QROptions
	 *
	 * @param string      $data
	 * @param string|null $file
	 *
	 * @return mixed
	 */
	public function render($data, $file = null){
		return $this->initOutputInterface($data)->dump($file);
	}

	/**
	 * Returns a QRMatrix object for the given $data and current QROptions
	 *
	 * @param string $data
	 *
	 * @return \RefPro\QRCode\Data\QRMatrix
	 * @throws \RefPro\QRCode\Data\QRCodeDataException
	 */
	public function getMatrix($data){

		if(empty($data)){
			throw new QRCodeDataException('QRCode::getMatrix() No data given.');
		}

		$this->dataInterface = $this->initDataInterface($data);

		$maskPattern = $this->options->maskPattern === $this::MASK_PATTERN_AUTO
			? $this->getBestMaskPattern()
			: $this->options->maskPattern;

		$matrix = $this->dataInterface->initMatrix($maskPattern);

		if((bool)$this->options->addQuietzone){
			$matrix->setQuietZone($this->options->quietzoneSize);
		}

		return $matrix;
	}

	/**
	 * shoves a QRMatrix through the MaskPatternTester to find the lowest penalty mask pattern
	 *
	 * @see \RefPro\QRCode\Data\MaskPatternTester
	 *
	 * @return int
	 */
	protected function getBestMaskPattern(){
		$penalties = array();

		for($pattern = 0; $pattern < 8; $pattern++){
			$tester = new MaskPatternTester($this->dataInterface->initMatrix($pattern, true));

			$penalties[$pattern] = $tester->testPattern();
		}

		return array_search(min($penalties), $penalties, true);
	}

	/**
	 * returns a fresh QRDataInterface for the given $data
	 *
	 * @param string                       $data
	 *
	 * @return \RefPro\QRCode\Data\QRDataInterface
	 * @throws \RefPro\QRCode\Data\QRCodeDataException
	 */
	public function initDataInterface($data){

		foreach(array('Number', 'AlphaNum', 'Kanji', 'Byte') as $mode){
			$dataInterface = __NAMESPACE__.'\\Data\\'.$mode;

			if(call_user_func_array(array($this, 'is'.$mode), array($data)) && class_exists($dataInterface)){
				return new $dataInterface($this->options, $data);
			}

		}

		throw new QRCodeDataException('invalid data type'); // @codeCoverageIgnore
	}

	/**
	 * returns a fresh (built-in) QROutputInterface
	 *
	 * @param string $data
	 *
	 * @return \RefPro\QRCode\Output\QROutputInterface
	 * @throws \RefPro\QRCode\Output\QRCodeOutputException
	 */
	protected function initOutputInterface($data){

		if($this->options->outputType === $this::OUTPUT_CUSTOM && class_exists($this->options->outputInterface)){
			return new $this->options->outputInterface($this->options, $this->getMatrix($data));
		}

		foreach($this::$OUTPUT_MODES as $outputInterface => $modes){

			if(in_array($this->options->outputType, $modes, true) && class_exists($outputInterface)){
				return new $outputInterface($this->options, $this->getMatrix($data));
			}

		}

		throw new QRCodeOutputException('invalid output type');
	}

	/**
	 * checks if a string qualifies as numeric
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public function isNumber($string){
		return $this->checkString($string, QRDataAbstract::$NUMBER_CHAR_MAP);
	}

	/**
	 * checks if a string qualifies as alphanumeric
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public function isAlphaNum($string){
		return $this->checkString($string, QRDataAbstract::$ALPHANUM_CHAR_MAP);
	}

	/**
	 * checks is a given $string matches the characters of a given $charmap, returns false on the first invalid occurence.
	 *
	 * @param string $string
	 * @param array  $charmap
	 *
	 * @return bool
	 */
	protected function checkString($string, array $charmap){
		$len = strlen($string);

		for($i = 0; $i < $len; $i++){
			if(!in_array($string[$i], $charmap, true)){
				return false;
			}
		}

		return true;
	}

	/**
	 * checks if a string qualifies as Kanji
	 *
	 * @param string $string
	 *
	 * @return bool
	 */
	public function isKanji($string){
		$i   = 0;
		$len = strlen($string);

		while($i + 1 < $len){
			$c = ((0xff&ord($string[$i])) << 8)|(0xff&ord($string[$i + 1]));

			if(!($c >= 0x8140 && $c <= 0x9FFC) && !($c >= 0xE040 && $c <= 0xEBBF)){
				return false;
			}

			$i += 2;
		}

		return $i >= $len;
	}

	/**
	 * a dummy
	 *
	 * @param $data
	 *
	 * @return bool
	 */
	protected function isByte($data){
		return !empty($data);
	}

}
