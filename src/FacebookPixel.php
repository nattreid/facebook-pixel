<?php

namespace NAttreid\FacebookPixel;

use Nette\Application\UI\Control;

/**
 * Class FacebookPixelClient
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixel extends Control
{

	/** @var string */
	private $apiKey;

	public function __construct($apiKey)
	{
		parent::__construct();
		$this->apiKey = $apiKey;
	}

	public function render()
	{
		$this->template->apiKey = $this->apiKey;
		$this->template->setFile(__DIR__ . "/templates/default.latte");
		$this->template->render();
	}
}

interface IFacebookPixelFactory
{
	/** @return FacebookPixel */
	public function create();
}