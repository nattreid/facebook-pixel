<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel;

use NAttreid\FacebookPixel\Events\AddPaymentInfo;
use NAttreid\FacebookPixel\Events\AddToCart;
use NAttreid\FacebookPixel\Events\AddToWishlist;
use NAttreid\FacebookPixel\Events\CompleteRegistration;
use NAttreid\FacebookPixel\Events\InitiateCheckout;
use NAttreid\FacebookPixel\Events\Lead;
use NAttreid\FacebookPixel\Events\Purchase;
use NAttreid\FacebookPixel\Events\Search;
use NAttreid\FacebookPixel\Events\ViewContent;
use Nette\Application\UI\Control;
use Nette\Http\IRequest;

/**
 * Class FacebookPixelClient
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixel extends Control
{

	/** @var string[] */
	private $pixelId;

	/** @var string[][] */
	private $events = [];

	/** @var string[][] */
	private $ajaxEvents = [];

	/** @var IRequest */
	private $request;

	public function __construct(array $pixelId, IRequest $request)
	{
		parent::__construct();
		$this->pixelId = $pixelId;
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
	 * Complete Registration event (customer)
	 * @return CompleteRegistration
	 */
	public function completeRegistration(): CompleteRegistration
	{
		return $this->events[] = new CompleteRegistration();
	}

	public function render(): void
	{
		if ($this->request->isAjax()) {
			$this->renderAjax();
		} else {
			$this->template->pixelId = $this->pixelId;
			$this->template->events = $this->events;
			$this->template->setFile(__DIR__ . '/templates/default.latte');
			$this->template->render();
		}
	}

	public function renderAjax(): void
	{
		$this->template->pixelId = $this->pixelId;
		$this->template->ajaxEvents = $this->ajaxEvents;
		$this->template->setFile(__DIR__ . '/templates/ajax.latte');
		$this->template->render();
	}
}

interface IFacebookPixelFactory
{
	public function create(): FacebookPixel;
}