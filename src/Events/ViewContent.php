<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

use NAttreid\FacebookPixel\Events\Abstracts\ContentEvent;

/**
 * Class ViewContent
 *
 * @author Attreid <attreid@gmail.com>
 */
class ViewContent extends ContentEvent
{
	public function __construct()
	{
		$this->values['content_type'] = 'product';
	}

	/**
	 * Set type to 'product_type' (default is 'product')
	 * @return static
	 */
	public function setTypeProductGroup()
	{
		$this->values['content_type'] = 'product_group';
		return $this;
	}

	/**
	 * @param string $name
	 * @return static
	 */
	public function setContentName(string $name)
	{
		$this->values['content_name'] = $name;
		return $this;
	}
}