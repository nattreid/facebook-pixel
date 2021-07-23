<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

use NAttreid\FacebookPixel\Events\Abstracts\ContentEvent;
use Nette\Http\IRequest;

/**
 * Class ViewContent
 *
 * @author Attreid <attreid@gmail.com>
 */
class ViewContent extends ContentEvent
{
	public function __construct(IRequest $request, ?string $externId)
	{
		parent::__construct($request, $externId);
		$this->values['content_type'] = 'product';
	}

	/**
	 * Set type to 'product_type' (default is 'product')
	 * @return static
	 */
	public function setTypeProductGroup(): self
	{
		$this->values['content_type'] = 'product_group';
		return $this;
	}

	/**
	 * @param string $name
	 * @return static
	 */
	public function setContentName(string $name): self
	{
		$this->values['content_name'] = $name;
		return $this;
	}
}