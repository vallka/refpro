<?php
/**
 * Class SettingsContainerAbstract
 *
 * @filesource   SettingsContainerAbstract.php
 * @created      28.08.2018
 * @package      RefPro\Settings
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace RefPro\Settings;

use ReflectionClass, ReflectionProperty;

require_once __DIR__.'/SettingsContainerInterface.php';

abstract class SettingsContainerAbstract implements SettingsContainerInterface{

	/**
	 * SettingsContainerAbstract constructor.
	 *
	 * @param iterable|null $properties
	 */
	public function __construct($properties = null){

		if(!empty($properties)){
			$this->fromIterable($properties);
		}

		$this->construct();
	}

	/**
	 * @return void
	 */
	protected function construct(){
	}

	/**
	 * @inheritdoc
	 */
	public function __get($property){

		if($this->__isset($property)){
			return $this->{$property};
		}

		return null;
	}

	/**
	 * @inheritdoc
	 */
	public function __set($property, $value){

		if(!property_exists($this, $property) || $this->isPrivate($property)){
			return;
		}

		$this->{$property} = $value;
	}

	/**
	 * @inheritdoc
	 */
	public function __isset($property){
		return isset($this->{$property}) && !$this->isPrivate($property);
	}

	/**
	 * @internal Checks if a property is private
	 *
	 * @param string $property
	 *
	 * @return bool
	 */
	protected function isPrivate($property){
		$property = (new ReflectionProperty($this, $property));
		return $property->isPrivate();
	}

	/**
	 * @inheritdoc
	 */
	public function __unset($property){

		if($this->__isset($property)){
			unset($this->{$property});
		}

	}

	/**
	 * @inheritdoc
	 */
	public function __toString(){
		return $this->toJSON();
	}

	/**
	 * @inheritdoc
	 */
	public function toArray(){
		return get_object_vars($this);
	}

	/**
	 * @inheritdoc
	 */
	public function fromIterable($properties){

		foreach($properties as $key => $value){
			$this->__set($key, $value);
		}

		return $this;
	}

	/**
	 * @inheritdoc
	 */
	public function toJSON($jsonOptions = null){
		return json_encode($this->toArray(), isset($jsonOptions) ? $jsonOptions : 0);
	}

	/**
	 * @inheritdoc
	 */
	public function fromJSON($json){
		return $this->fromIterable(json_decode($json, true));
	}

}
