<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\DI;

use NAttreid\Cms\Configurator\Configurator;
use NAttreid\Cms\DI\ExtensionTranslatorTrait;
use NAttreid\FacebookPixel\FacebookPixel;
use NAttreid\FacebookPixel\Hooks\FacebookPixelConfig;
use NAttreid\FacebookPixel\Hooks\FacebookPixelHook;
use NAttreid\FacebookPixel\IFacebookPixelFactory;
use NAttreid\WebManager\Services\Hooks\HookService;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\InvalidStateException;

/**
 * Class AbstractFacebookPixelExtension
 *
 * @author Attreid <attreid@gmail.com>
 */
abstract class AbstractFacebookPixelExtension extends CompilerExtension
{
	private $defaults = [
		'credentials' => []
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->getConfig());

		$credentials = $config['credentials'];
		$configs = [];
		foreach ($credentials as $credential) {
			if (!isset($credential[0])) {
				throw new InvalidStateException("FacebookPixel: 'credentials' first (pixelId) does not set in config.neon");
			}
			if (!isset($credential[1])) {
				throw new InvalidStateException("FacebookPixel: 'credentials' second (accessToken) does not set in config.neon");
			}

			$config = new FacebookPixelConfig();
			$config->pixelId = $credential[0];
			$config->accessToken = $credential[1];
		}

		$configs = $this->prepareConfig($configs);

		if ($configs === null) {
			throw new InvalidStateException("FacebookPixel: 'credentials' does not set in config.neon");
		}

		$builder->addDefinition($this->prefix('factory'))
			->setImplement(IFacebookPixelFactory::class)
			->setFactory(FacebookPixel::class)
			->setArguments([$configs]);
	}

	protected function prepareConfig(array $configs)
	{
		return $configs;
	}
}