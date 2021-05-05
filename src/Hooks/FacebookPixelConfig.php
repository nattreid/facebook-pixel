<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Hooks;

use Nette\SmartObject;

/**
 * Class FacebookPixelConfig
 *
 * @property string $pixelId
 * @property string $accessToken
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixelConfig
{
	use SmartObject;

	/** @var string */
	private $pixelId;

	/** @var string */
	private $accessToken;

	protected function getPixelId(): string
	{
		return $this->pixelId;
	}

	protected function setPixelId(string $pixelId): void
	{
		$this->pixelId = $pixelId;
	}

	protected function getAccessToken(): string
	{
		return $this->accessToken;
	}

	protected function setAccessToken(string $accessToken): void
	{
		$this->accessToken = $accessToken;
	}
}