<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

use Nette\InvalidStateException;

/**
 * Class Purchase
 *
 * @author Attreid <attreid@gmail.com>
 */
class Purchase extends ViewContent
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

	protected function check()
	{
		if (!isset($this->values['value']) || !isset($this->values['currency'])) {
			throw new InvalidStateException("'Value' and 'Currency' must be set");
		}
	}
}