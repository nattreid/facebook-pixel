<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

use NAttreid\FacebookPixel\Events\Abstracts\ContentEvent;

/**
 * Class AddPaymentInfo
 *
 * @author Attreid <attreid@gmail.com>
 */
class AddPaymentInfo extends ContentEvent
{
	/**
	 * Set Content category
	 * @param string $category
	 * @return static
	 */
	public function setContentCategory(string $category): self
	{
		$this->values['content_category'] = $category;
		return $this;
	}
}