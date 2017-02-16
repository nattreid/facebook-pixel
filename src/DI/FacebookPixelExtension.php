<?php

namespace NAttreid\FacebookPixel\DI;

use NAttreid\Cms\Configurator\Configurator;
use NAttreid\Cms\ExtensionTranslatorTrait;
use NAttreid\FacebookPixel\FacebookPixel;
use NAttreid\FacebookPixel\Hooks\FacebookPixelHook;
use NAttreid\FacebookPixel\IFacebookPixelFactory;
use NAttreid\WebManager\Services\Hooks\HookService;
use Nette\DI\CompilerExtension;
use Nette\DI\Statement;
use Nette\InvalidStateException;

/**
 * Class FacebookPixelExtension
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixelExtension extends CompilerExtension
{
	use ExtensionTranslatorTrait;

	private $defaults = [
		'apiKey' => null
	];

	public function loadConfiguration()
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->getConfig());

		$hook = $builder->getByType(HookService::class);
		if ($hook) {
			$builder->addDefinition($this->prefix('facebookPixelHook'))
				->setClass(FacebookPixelHook::class);

			$this->setTranslation(__DIR__ . '/../lang/', [
				'webManager'
			]);

			$config['apiKey'] = new Statement('?->facebookPixelApiKey', ['@' . Configurator::class]);
		}

		if ($config['apiKey'] === null) {
			throw new InvalidStateException("FacebookPixel: 'apiKey' does not set in config.neon");
		}

		$builder->addDefinition($this->prefix('factory'))
			->setImplement(IFacebookPixelFactory::class)
			->setFactory(FacebookPixel::class)
			->setArguments([$config['apiKey']]);
	}
}