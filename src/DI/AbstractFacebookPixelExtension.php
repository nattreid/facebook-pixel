<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\DI;

use NAttreid\Cms\Configurator\Configurator;
use NAttreid\Cms\DI\ExtensionTranslatorTrait;
use NAttreid\FacebookPixel\FacebookPixel;
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
		'pixelId' => null
	];

	public function loadConfiguration(): void
	{
		$builder = $this->getContainerBuilder();
		$config = $this->validateConfig($this->defaults, $this->getConfig());

		$pixelId = $config['pixelId'];
		if ($pixelId !== null && !is_array($pixelId)) {
			$pixelId = [$pixelId];
		}

		$pixelId = $this->prepareConfig($pixelId);

		if ($pixelId === null) {
			throw new InvalidStateException("FacebookPixel: 'pixelId' does not set in config.neon");
		}

		$builder->addDefinition($this->prefix('factory'))
			->setImplement(IFacebookPixelFactory::class)
			->setFactory(FacebookPixel::class)
			->setArguments([$pixelId]);
	}

	protected function prepareConfig($pixelId)
	{
		return $pixelId;
	}
}