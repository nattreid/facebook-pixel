<?php

declare(strict_types = 1);

namespace NAttreid\FacebookPixel;

use Nette\Application\UI\Control;
use Nette\Http\IRequest;

/**
 * Class FacebookPixelClient
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixel extends Control
{

	/** @var string */
	private $apiKey;

	/** @var string[][] */
	private $events = [];

	/** @var string[][] */
	private $ajaxEvents = [];

	/** @var IRequest */
	private $request;

	public function __construct(string $apiKey, IRequest $request)
	{
		parent::__construct();
		$this->apiKey = $apiKey;
		$this->request = $request;
	}

	/**
	 * Search event
	 * @param string $searchString
	 */
	public function search(string $searchString)
	{
		$this->events['Search'] = [
			'search_string' => $searchString
		];
	}

	/**
	 * View Content (Detail) event
	 * @param float $value
	 * @param string $currency
	 */
	public function viewContent(float $value = null, string $currency = null)
	{
		$values = [];
		if ($value !== null) {
			$values['value'] = $value;
		}
		if ($currency !== null) {
			$values['currency'] = $currency;
		}

		$this->events['ViewContent'] = $values;
	}

	/**
	 * Add to Cart event
	 * @param float $value
	 * @param string $currency
	 */
	public function addToCart(float $value = null, string $currency = null)
	{
		$values = [];
		if ($value !== null) {
			$values['value'] = $value;
		}
		if ($currency !== null) {
			$values['currency'] = $currency;
		}

		$this->ajaxEvents['AddToCart'] = $values;
		$this->redrawControl('ajaxEvents');
	}

	/**
	 * Add to Wish List event
	 * @param float $value
	 * @param string $currency
	 */
	public function addToWishList(float $value = null, string $currency = null)
	{
		$values = [];
		if ($value !== null) {
			$values['value'] = $value;
		}
		if ($currency !== null) {
			$values['currency'] = $currency;
		}

		$this->ajaxEvents['AddToWishList'] = $values;
		$this->redrawControl('ajaxEvents');
	}

	/**
	 * Initiate Checkout event
	 */
	public function initiateCheckout()
	{
		$this->events['InitiateCheckout'] = [];
	}

	/**
	 * Add Payment Info event
	 */
	public function addPaymentInfo()
	{
		$this->events['AddPaymentInfo'] = [];
	}

	/**
	 * Purchase event
	 * @param float $value
	 * @param string $currency
	 */
	public function purchase(float $value = null, string $currency = null)
	{
		$values = [];
		if ($value !== null) {
			$values['value'] = $value;
		}
		if ($currency !== null) {
			$values['currency'] = $currency;
		}

		$this->ajaxEvents['Purchase'] = $values;
		$this->redrawControl('ajaxEvents');
	}

	/**
	 * Lead event (customer)
	 */
	public function lead()
	{
		$this->events['Lead'] = [];
	}

	/**
	 * Complete Registration event (customer)
	 */
	public function completeRegistration()
	{
		$this->events['CompleteRegistration'] = [];
	}

	public function render()
	{
		if ($this->request->isAjax()) {
			$this->renderAjax();
		} else {
			$this->template->apiKey = $this->apiKey;
			$this->template->events = $this->events;
			$this->template->setFile(__DIR__ . '/templates/default.latte');
			$this->template->render();
		}
	}

	public function renderAjax()
	{
		$this->template->apiKey = $this->apiKey;
		$this->template->ajaxEvents = $this->ajaxEvents;
		$this->template->setFile(__DIR__ . '/templates/ajax.latte');
		$this->template->render();
	}
}

interface IFacebookPixelFactory
{
	public function create(): FacebookPixel;
}