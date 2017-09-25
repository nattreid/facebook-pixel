<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events\Abstracts;

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
	public function addId(string $id)
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
	 * @param float $price
	 * @param int $count
	 * @return static
	 */
	public function addContent(string $id, float $itemPrice, int $quantity)
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
}