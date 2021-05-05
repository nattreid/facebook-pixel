<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events\Abstracts;

use FacebookAds\Object\ServerSide\Content;
use FacebookAds\Object\ServerSide\CustomData;
use FacebookAds\Object\ServerSide\DeliveryCategory;
use FacebookAds\Object\ServerSide\Event as ServerEvent;
use stdClass;

/**
 * Class ContentEvent
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class ContentEvent extends Event
{

	/**
	 * Add id
	 * @param string $id
	 * @return static
	 */
	public function addId(string $id): self
	{
		if (!isset($this->values['content_ids'])) {
			$this->values['content_ids'] = [];
		}
		$this->values['content_ids'][] = $id;
		return $this;
	}

	/**
	 * Add Content
	 * @param string $id
	 * @param float $itemPrice
	 * @param int $quantity
	 * @return static
	 */
	public function addContent(string $id, float $itemPrice, int $quantity): self
	{
		if (!isset($this->values['contents'])) {
			$this->values['contents'] = [];
		}
		$obj = new stdClass;
		$obj->id = $id;
		$obj->quantity = $quantity;
		$obj->item_price = $itemPrice;

		$this->values['contents'][] = $obj;
		return $this;
	}

	public function getEvent(): ServerEvent
	{
		$event = parent::getEvent();

		$contents = [];
		if (isset($this->values['contents'])) {
			foreach ($this->values['contents'] as $row) {
				$content = (new Content())
					->setProductId($row->id)
					->setQuantity($row->quantity)
					->setItemPrice($row->item_price)
					->setDeliveryCategory(DeliveryCategory::HOME_DELIVERY);
				$contents[] = $content;
			}
		}
		/* @var $customData CustomData */
		$customData = $event->getCustomData();
		$customData->setContents($contents);

		return $event;
	}
}