<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

use NAttreid\FacebookPixel\Events\Abstracts\ContentEvent;

/**
 * Class Search
 *
 * @author Attreid <attreid@gmail.com>
 */
class Search extends ContentEvent
{
	/**
	 * Set Content name
	 * @param string $name
	 * @return static
	 */
	public function setSearch(string $search): self
	{
		$this->values['search_string'] = $search;
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