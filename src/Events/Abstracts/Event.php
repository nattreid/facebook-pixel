<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events\Abstracts;

use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event as ServerEvent;
use FacebookAds\Object\ServerSide\UserData;
use Nette\SmartObject;
use Nette\Utils\Random;
use ReflectionClass;

/**
 * Class Event
 *
 * @property-read array $items;
 * @property-read string $name;
 * @property-read string $eventId;
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class Event
{
	use SmartObject;

	/** @var array */
	protected $values = [];

	/** @var string */
	private $eventId;

	/**
	 * Set Value
	 * @param float $value
	 * @return static
	 */
	public function setValue(float $value): self
	{
		$this->values['value'] = $value;
		return $this;
	}

	/**
	 * Set Currency
	 * @param string $currency
	 * @return static
	 */
	public function setCurrency(string $currency): self
	{
		$this->values['currency'] = $currency;
		return $this;
	}

	protected function check(): void
	{
	}

	protected function getEventId(): string
	{
		if ($this->eventId === null) {
			$this->eventId = Random::generate();
		}
		return $this->eventId;
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

	public function getEvent(): ServerEvent
	{
		$userData = (new UserData())
			->setClientIpAddress($_SERVER['REMOTE_ADDR'])
			->setClientUserAgent($_SERVER['HTTP_USER_AGENT']);

		$event = (new ServerEvent())
			->setEventName($this->name)
			->setEventTime(time())
			->setEventId($this->getEventId())
			->setUserData($userData)
			->setActionSource(ActionSource::WEBSITE);

		$customData = new CustomData();
		if (isset($this->values['value'])) {
			$customData->setValue($this->values['value']);
		}
		if (isset($this->values['currency'])) {
			$customData->setCurrency($this->values['currency']);
		}

		$event->setCustomData($customData);

		return $event;
	}
}