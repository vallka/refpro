<?php
/**
 * Class QROutputAbstract
 *
 * @filesource   QROutputAbstract.php
 * @created      09.12.2015
 * @package      RefPro\QRCode\Output
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace RefPro\QRCode\Output;

use RefPro\QRCode\Data\QRMatrix;
use RefPro\QRCode\QRCode;
use RefPro\Settings\SettingsContainerInterface;

require_once __DIR__.'/QROutputInterface.php';

/**
 * common output abstract
 */
abstract class QROutputAbstract implements QROutputInterface{

	public static $DEFAULT_MODULE_VALUES = array(
		// light
		QRMatrix::M_DATA            => false, // 4
		QRMatrix::M_FINDER          => false, // 6
		QRMatrix::M_SEPARATOR       => false, // 8
		QRMatrix::M_ALIGNMENT       => false, // 10
		QRMatrix::M_TIMING          => false, // 12
		QRMatrix::M_FORMAT          => false, // 14
		QRMatrix::M_VERSION         => false, // 16
		QRMatrix::M_QUIETZONE       => false, // 18
		QRMatrix::M_TEST            => false, // 255
		// dark
		512 /*QRMatrix::M_DARKMODULE << 8 */ => true,  // 512
		1024 /*QRMatrix::M_DATA << 8       */ => true,  // 1024
		1536 /*QRMatrix::M_FINDER << 8     */ => true,  // 1536
		2560 /*QRMatrix::M_ALIGNMENT << 8  */ => true,  // 2560
		3072 /*QRMatrix::M_TIMING << 8     */ => true,  // 3072
		3584 /*QRMatrix::M_FORMAT << 8     */ => true,  // 3584
		4096 /*QRMatrix::M_VERSION << 8    */ => true,  // 4096
		65280 /*QRMatrix::M_TEST << 8       */ => true,  // 65280
	);

	/**
	 * @var int
	 */
	protected $moduleCount;

	/**
	 * @param \RefPro\QRCode\Data\QRMatrix $matrix
	 */
	protected $matrix;

	/**
	 * @var \RefPro\QRCode\QROptions
	 */
	protected $options;

	/**
	 * @var string
	 */
	protected $outputMode;

	/**
	 * @var string;
	 */
	protected $defaultMode;

	/**
	 * @var int
	 */
	protected $scale;

	/**
	 * @var int
	 */
	protected $length;

	/**
	 * @var array
	 */
	protected $moduleValues;

	/**
	 * QROutputAbstract constructor.
	 *
	 * @param \RefPro\Settings\SettingsContainerInterface $options
	 * @param \RefPro\QRCode\Data\QRMatrix      $matrix
	 */
	public function __construct(SettingsContainerInterface $options, QRMatrix $matrix){
		$this->options     = $options;
		$this->matrix      = $matrix;
		$this->moduleCount = $this->matrix->size();
		$this->scale       = $this->options->scale;
		$this->length      = $this->moduleCount * $this->scale;

		$class = get_called_class();

		if(array_key_exists($class, QRCode::$OUTPUT_MODES) && in_array($this->options->outputType, QRCode::$OUTPUT_MODES[$class])){
			$this->outputMode = $this->options->outputType;
		}

		$this->setModuleValues();
	}

	/**
	 * Sets the initial module values (clean-up & defaults)
	 *
	 * @return void
	 */
	abstract protected function setModuleValues();

	/**
	 * @see file_put_contents()
	 *
	 * @param string $data
	 * @param string $file
	 *
	 * @return bool
	 * @throws \RefPro\QRCode\Output\QRCodeOutputException
	 */
	protected function saveToFile($data, $file){

		if(!is_writable(dirname($file))){
			throw new QRCodeOutputException('Could not write data to cache file: '.$file);
		}

		return (bool)file_put_contents($file, $data);
	}

	/**
	 * @param string|null $file
	 *
	 * @return string|mixed
	 */
	public function dump($file = null){
		$data = call_user_func(array($this, isset($this->outputMode) ? $this->outputMode : $this->defaultMode));
		$file = isset($file) ? $file : $this->options->cachefile;

		if($file !== null){
			$this->saveToFile($data, $file);
		}

		return $data;
	}

}
