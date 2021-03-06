<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\DI;

use NAttreid\Cms\Configurator\Configurator;
use NAttreid\Cms\DI\ExtensionTranslatorTrait;
use NAttreid\FacebookPixel\Hooks\FacebookPixelHook;
use NAttreid\WebManager\Services\Hooks\HookService;
use Nette\DI\Statement;

if (trait_exists('NAttreid\Cms\DI\ExtensionTranslatorTrait')) {
	class FacebookPixelExtension extends AbstractFacebookPixelExtension
	{
		use ExtensionTranslatorTrait;

		protected function prepareConfig(array $configs)
		{
			$builder = $this->getContainerBuilder();
			$hook = $builder->getByType(HookService::class);
			if ($hook) {
				$builder->addDefinition($this->prefix('facebookPixelHook'))
					->setType(FacebookPixelHook::class);

				$this->setTranslation(__DIR__ . '/../lang/', [
					'webManager'
				]);

				return new Statement('?->facebookPixel \?: []', ['@' . Configurator::class]);
			} else {
				return parent::prepareConfig($configs);
			}
		}
	}
} else {
	class FacebookPixelExtension extends AbstractFacebookPixelExtension
	{
	}
}