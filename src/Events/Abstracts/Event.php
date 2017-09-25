<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events\Abstracts;

use Nette\SmartObject;
use ReflectionClass;

/**
 * Class Event
 *
 * @property-read array $items;
 * @property-read string $name;
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Event
{
	use SmartObject;

	/** @var array */
	protected $values = [];

	/**
	 * Set Value
	 * @param float $value
	 * @return static
	 */
	public function setValue(float $value)
	{
		$this->values['value'] = $value;
		return $this;
	}

	/**
	 * Set Currency
	 * @param string $currency
	 * @return static
	 */
	public function setCurrency(string $currency)
	{
		$this->values['currency'] = $currency;
		return $this;
	}

	protected function check()
	{
	}

	protected function getItems(): array
	{
		$this->check();
		return $this->values;
	}

	protected function getName(): string
	{
		return (new ReflectionClass(get_called_class()))->getShortName();
	}

}