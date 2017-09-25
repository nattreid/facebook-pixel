<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

/**
 * Class InitiateCheckout
 *
 * @author Attreid <attreid@gmail.com>
 */
class InitiateCheckout extends AddToWishlist
{
	/**
	 * @param int $numItems
	 * @return static
	 */
	public function setNumItems(int $numItems): self
	{
		$this->values['num_items'] = $numItems;
		return $this;
	}
}