<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events\Abstracts;

use FacebookAds\Object\ServerSide\ActionSource;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\Event as ServerEvent;
use FacebookAds\Object\ServerSide\UserData;
use Nette\Http\IRequest;
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

	/** @var IRequest */
	private $request;

	public function __construct(IRequest $request)
	{
		$this->request = $request;
	}

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
	 * Set ExternalId
	 * @param string $value
	 * @return static
	 */
	public function setExternalId(string $value): self
	{
		$this->values['external_id'] = $value;
		return $this;
	}

	/**
	 * Set country
	 * @param string $value
	 * @return static
	 */
	public function setCountry(string $value): self
	{
		$this->values['country'] = $value;
		return $this;
	}

	/**
	 * Set email
	 * @param string $value
	 * @return static
	 */
	public function setEmail(string $value): self
	{
		$this->values['em'] = $value;
		return $this;
	}

	/**
	 * Set first name
	 * @param string $value
	 * @return static
	 */
	public function setFirstName(string $value): self
	{
		$this->values['fn'] = $value;
		return $this;
	}

	/**
	 * Set last name
	 * @param string $value
	 * @return static
	 */
	public function setLastName(string $value): self
	{
		$this->values['ln'] = $value;
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

		$event = (new ServerEvent())
			->setEventName($this->name)
			->setEventTime(time())
			->setEventId($this->getEventId())
			->setEventSourceUrl($this->request->getUrl()->getAbsoluteUrl())
			->setActionSource(ActionSource::WEBSITE);

		$customData = new CustomData();
		if (isset($this->values['value'])) {
			$customData->setValue($this->values['value']);
		}
		if (isset($this->values['currency'])) {
			$customData->setCurrency($this->values['currency']);
		}
		$event->setCustomData($customData);


		$userData = (new UserData())
			->setClientIpAddress($_SERVER['REMOTE_ADDR'])
			->setClientUserAgent($_SERVER['HTTP_USER_AGENT']);
		if (isset($this->values['external_id'])) {
			$userData->setExternalId($this->values['external_id']);
		}
		if (isset($this->values['country'])) {
			$userData->setCountryCode($this->values['country']);
		}
		if (isset($this->values['em'])) {
			$userData->setEmail($this->values['em']);
		}
		if (isset($this->values['fn'])) {
			$userData->setFirstName($this->values['fn']);
		}
		if (isset($this->values['ln'])) {
			$userData->setLastName($this->values['ln']);
		}
		$event->setUserData($userData);

		return $event;
	}
}