<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel;

use FacebookAds\Api;
use FacebookAds\Logger\CurlLogger;
use FacebookAds\Object\ServerSide\EventRequest;
use NAttreid\FacebookPixel\Events\Abstracts\Event;
use NAttreid\FacebookPixel\Events\AddPaymentInfo;
use NAttreid\FacebookPixel\Events\AddToCart;
use NAttreid\FacebookPixel\Events\AddToWishlist;
use NAttreid\FacebookPixel\Events\CompleteRegistration;
use NAttreid\FacebookPixel\Events\InitiateCheckout;
use NAttreid\FacebookPixel\Events\Lead;
use NAttreid\FacebookPixel\Events\Purchase;
use NAttreid\FacebookPixel\Events\Search;
use NAttreid\FacebookPixel\Events\ViewContent;
use NAttreid\FacebookPixel\Hooks\FacebookPixelConfig;
use Nette\Application\UI\Control;
use Nette\Http\IRequest;
use Nette\InvalidArgumentException;

/**
 * Class FacebookPixelClient
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixel extends Control
{

	/** @var FacebookPixelConfig[] */
	private $config;

	/** @var Event[] */
	private $events = [];

	/** @var Event[] */
	private $ajaxEvents = [];

	/** @var IRequest */
	private $request;

	/** @var int|null */
	private $configKey;

	public function __construct(array $config, IRequest $request)
	{
		parent::__construct();
		$this->config = $config;
		$this->request = $request;
	}

	/**
	 * Search event
	 * @return Search
	 */
	public function search(): Search
	{
		return $this->events[] = new Search();
	}

	/**
	 * View Content (Detail) event
	 * @return ViewContent
	 */
	public function viewContent(): ViewContent
	{
		return $this->events[] = new ViewContent();
	}

	/**
	 * Add to Cart event
	 * @return AddToCart
	 */
	public function addToCart(): AddToCart
	{
		$this->redrawControl('ajaxEvents');
		return $this->ajaxEvents[] = new AddToCart();
	}

	/**
	 * Add to Wish List event
	 * @return AddToWishlist
	 */
	public function addToWishList(): AddToWishlist
	{
		$this->redrawControl('ajaxEvents');
		return $this->ajaxEvents[] = new AddToWishlist();
	}

	/**
	 * Initiate Checkout event
	 * @return InitiateCheckout
	 */
	public function initiateCheckout(): InitiateCheckout
	{
		return $this->events[] = new InitiateCheckout();
	}

	/**
	 * Add Payment Info event
	 * @return AddPaymentInfo
	 */
	public function addPaymentInfo(): AddPaymentInfo
	{
		return $this->events[] = new AddPaymentInfo();
	}

	/**
	 * Purchase event
	 * @return Purchase
	 */
	public function purchase(): Purchase
	{
		$this->redrawControl('ajaxEvents');
		return $this->ajaxEvents[] = new Purchase();
	}

	/**
	 * Lead event (customer)
	 * @return Lead
	 */
	public function lead(): Lead
	{
		return $this->events[] = new Lead();
	}

	/**
	 * Return pixelsId array
	 * @return string[]
	 */
	public function getPixelList(): array
	{
		return $this->pixelId;
	}

	/**
	 * Select pixelId
	 * @param int $key key of pixelId list
	 * @throws InvalidArgumentException
	 */
	public function usePixelId(int $key): void
	{
		if (!isset($this->config[$key])) {
			throw new InvalidArgumentException();
		}
		$this->configKey = $key;
	}

	/**
	 * Complete Registration event (customer)
	 * @return CompleteRegistration
	 */
	public function completeRegistration(): CompleteRegistration
	{
		return $this->events[] = new CompleteRegistration();
	}

	private function getConfig(): array
	{
		if ($this->configKey) {
			return [$this->config[$this->configKey]];
		} else {
			return $this->config;
		}
	}

	public function render(): void
	{
		if ($this->request->isAjax()) {
			$this->renderAjax();
		} else {
			$this->template->config = $this->getConfig();
			$this->processEvents($this->events);
			$this->template->events = $this->events;
			$this->template->setFile(__DIR__ . '/templates/default.latte');
			$this->template->render();
		}
	}

	public function renderAjax(): void
	{
		$this->template->config = $this->getConfig();
		$this->processEvents($this->ajaxEvents);
		$this->template->events = $this->ajaxEvents;
		$this->template->setFile(__DIR__ . '/templates/ajax.latte');
		$this->template->render();
	}

	/**
	 * @param Event[] $events
	 */
	private function processEvents(array $events): void
	{
		foreach ($this->getConfig() as $config) {

			$api = Api::init(null, null, $config->accessToken);
			$api->setLogger(new CurlLogger());

			if (!empty($events)) {
				$arr = [];
				foreach ($events as $row) {
					$arr[] = $row->getEvent();
				}

				$request = (new EventRequest($config->pixelId))
					->setEvents($arr);
				$request->execute();
			}
		}
	}
}

interface IFacebookPixelFactory
{
	public function create(): FacebookPixel;
}