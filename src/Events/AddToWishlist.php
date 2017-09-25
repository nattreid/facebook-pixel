<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

use NAttreid\FacebookPixel\Events\Abstracts\ContentEvent;

/**
 * Class AddToWishlist
 *
 * @author Attreid <attreid@gmail.com>
 */
class AddToWishlist extends ContentEvent
{
	/**
	 * Set Content name
	 * @param string $name
	 * @return static
	 */
	public function setContentName(string $name): self
	{
		$this->values['content_name'] = $name;
		return $this;
	}

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