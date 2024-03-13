<?php
/**
 * Class QRImage
 *
 * @filesource   QRImage.php
 * @created      05.12.2015
 * @package      RefPro\QRCode\Output
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2015 Smiley
 * @license      MIT
 */

namespace RefPro\QRCode\Output;

use RefPro\QRCode\QRCode;

/**
 * Converts the matrix into GD images, raw or base64 output
 * requires ext-gd
 * @link http://php.net/manual/book.image.php
 */
class QRImage extends QROutputAbstract{

	protected static $TRANSPARENCY_TYPES = array(
		QRCode::OUTPUT_IMAGE_PNG,
		QRCode::OUTPUT_IMAGE_GIF,
	);

	/**
	 * @var string
	 */
	protected $defaultMode  = QRCode::OUTPUT_IMAGE_PNG;

	/**
	 * @see imagecreatetruecolor()
	 * @var resource
	 */
	protected $image;

	/**
	 * @see imagecolorallocate()
	 * @var int
	 */
	protected $background;

	/**
	 * @return void
	 */
	protected function setModuleValues(){

		foreach($this::$DEFAULT_MODULE_VALUES as $M_TYPE => $defaultValue){
			$v = isset($this->options->moduleValues[$M_TYPE]) ? $this->options->moduleValues[$M_TYPE] : null;

			if(!is_array($v) || count($v) < 3){
				$this->moduleValues[$M_TYPE] = $defaultValue
					? array(0, 0, 0)
					: array(255, 255, 255);
			}
			else{
				$this->moduleValues[$M_TYPE] = array_values($v);
			}

		}

	}

	/**
	 * @param string|null $file
	 *
	 * @return string
	 */
	public function dump($file = null){
		$this->image      = imagecreatetruecolor($this->length, $this->length);
		$this->background = imagecolorallocate($this->image, $this->options->imageTransparencyBG[0], $this->options->imageTransparencyBG[1], $this->options->imageTransparencyBG[2]);

		if((bool)$this->options->imageTransparent && in_array($this->options->outputType, $this::$TRANSPARENCY_TYPES, true)){
			imagecolortransparent($this->image, $this->background);
		}

		imagefilledrectangle($this->image, 0, 0, $this->length, $this->length, $this->background);

		foreach($this->matrix->matrix() as $y => $row){
			foreach($row as $x => $M_TYPE){
				$this->setPixel($x, $y, $this->moduleValues[$M_TYPE]);
			}
		}

		$imageData = $this->dumpImage($file);

		if((bool)$this->options->imageBase64){
			$imageData = 'data:image/'.$this->options->outputType.';base64,'.base64_encode($imageData);
		}

		return $imageData;
	}

	/**
	 * @param int   $x
	 * @param int   $y
	 * @param array $rgb
	 *
	 * @return void
	 */
	protected function setPixel($x, $y, array $rgb){
		imagefilledrectangle(
			$this->image,
			$x * $this->scale,
			$y * $this->scale,
			($x + 1) * $this->scale,
			($y + 1) * $this->scale,
			imagecolorallocate($this->image, $rgb[0], $rgb[1], $rgb[2])
		);
	}

	/**
	 * @param string|null $file
	 *
	 * @return string

	 * @throws \RefPro\QRCode\Output\QRCodeOutputException
	 */
	protected function dumpImage($file = null){
		$file = isset($file) ? $file : $this->options->cachefile;

		ob_start();

		try{
			call_user_func(array($this, isset($this->outputMode) ? $this->outputMode : $this->defaultMode));
		}
		// not going to cover edge cases
		// @codeCoverageIgnoreStart
		catch(\Exception $e){
			throw new QRCodeOutputException($e->getMessage());
		}
		// @codeCoverageIgnoreEnd

		$imageData = ob_get_contents();
		imagedestroy($this->image);

		ob_end_clean();

		if($file !== null){
			$this->saveToFile($imageData, $file);
		}

		return $imageData;
	}

	/**
	 * @return void
	 */
	protected function png(){
		imagepng(
			$this->image,
			null,
			in_array($this->options->pngCompression, range(-1, 9), true)
				? $this->options->pngCompression
				: -1
		);
	}

	/**
	 * Jiff - like... JitHub!
	 * @return void
	 */
	protected function gif(){
		imagegif($this->image);
	}

	/**
	 * @return void
	 */
	protected function jpg(){
		imagejpeg(
			$this->image,
			null,
			in_array($this->options->jpegQuality, range(0, 100), true)
				? $this->options->jpegQuality
				: 85
		);
	}

}
