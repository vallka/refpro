<?php
/**
 * Interface SettingsContainerInterface
 *
 * @filesource   SettingsContainerInterface.php
 * @created      28.08.2018
 * @package      RefPro\Settings
 * @author       Smiley <smiley@chillerlan.net>
 * @copyright    2018 Smiley
 * @license      MIT
 */

namespace RefPro\Settings;

/**
 * a generic container with magic getter and setter
 */
interface SettingsContainerInterface{

	/**
	 * Retrieve the value of $property
	 *
	 * @param string $property
	 *
	 * @return mixed
	 */
	public function __get($property);

	/**
	 * Set $property to $value while avoiding private and non-existing properties
	 *
	 * @param string $property
	 * @param mixed  $value
	 *
	 * @return void
	 */
	public function __set($property, $value);

	/**
	 * Checks if $property is set (aka. not null), excluding private properties
	 *
	 * @param string $property
	 *
	 * @return bool
	 */
	public function __isset($property);

	/**
	 * Unsets $property while avoiding private and non-existing properties
	 *
	 * @param string $property
	 *
	 * @return void
	 */
	public function __unset($property);

	/**
	 * @see SettingsContainerInterface::toJSON()
	 *
	 * @return string
	 */
	public function __toString();

	/**
	 * Returns an array representation of the settings object
	 *
	 * @return array
	 */
	public function toArray();

	/**
	 * Sets properties from a given iterable
	 *
	 * @param iterable $properties
	 *
	 * @return \RefPro\Settings\SettingsContainerInterface
	 */
	public function fromIterable($properties);

	/**
	 * Returns a JSON representation of the settings object
	 * @see \json_encode()
	 *
	 * @param int|null $jsonOptions
	 *
	 * @return string
	 */
	public function toJSON($jsonOptions = null);

	/**
	 * Sets properties from a given JSON string
	 *
	 * @param string $json
	 *
	 * @return \RefPro\Settings\SettingsContainerInterface
	 */
	public function fromJSON($json);

}
