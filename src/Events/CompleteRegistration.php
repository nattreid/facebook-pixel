<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Events;

use NAttreid\FacebookPixel\Events\Abstracts\Event;

/**
 * Class CompleteRegistration
 *
 * @author Attreid <attreid@gmail.com>
 */
class CompleteRegistration extends Event
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
	 * Set Status
	 * @param string $status
	 * @return static
	 */
	public function setStatus(string $status): self
	{
		$this->values['status'] = $status;
		return $this;
	}
}