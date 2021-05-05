<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Hooks;

use NAttreid\Form\Form;
use NAttreid\WebManager\Services\Hooks\HookFactory;
use Nette\ComponentModel\Component;
use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;

/**
 * Class FacebookPixelHook
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixelHook extends HookFactory
{
	/** @var IConfigurator */
	protected $configurator;

	public function init(): void
	{
		$this->latte = __DIR__ . '/facebookPixelHook.latte';
		$this->component = __DIR__ . '/component.latte';
	}

	/** @return Component */
	public function create(): Component
	{
		$form = $this->formFactory->create();

		$pixels = $form->addDynamic('facebookPixel', function (Container $container) {
			$container->addText('pixelId', 'webManager.web.hooks.facebookPixel.pixelId')
				->setRequired();
			$container->addText('accessToken', 'webManager.web.hooks.facebookPixel.accessToken')
				->setRequired();
			$container->addSubmit('remove', 'default.delete')
				->setValidationScope(FALSE)
				->onClick[] = [$this, 'removeFacebookPixel'];
		});

		$pixels->addSubmit('add', 'default.add')
			->setValidationScope(FALSE)
			->onClick[] = [$this, 'addFacebookPixel'];

		$this->setDefaults($form);

		$form->addSubmit('save', 'form.save')
			->onClick[] = [$this, 'facebookPixelFormSucceeded'];

		return $form;
	}

	public function facebookPixelFormSucceeded(SubmitButton $button): void
	{
		$arr = [];
		$counter = 1;
		foreach ($button->form['facebookPixel']->values as $values) {
			$config = new FacebookPixelConfig();
			$config->pixelId = $values->pixelId;
			$config->accessToken = $values->accessToken;
			$arr[$counter++] = $config;
		}
		$this->configurator->facebookPixel = $arr;

		$this->flashNotifier->success('default.dataSaved');
	}

	public function removeFacebookPixel(SubmitButton $button): void
	{
		$id = $button->parent->name;
		$arr = $this->configurator->facebookPixel;
		unset($arr[$id]);
		$this->configurator->facebookPixel = $arr;

		$this->onDataChange();
	}

	public function addFacebookPixel(SubmitButton $button): void
	{
		/* @var $pixelsId \Kdyby\Replicator\Container */
		$pixelsId = $button->parent;

		if ($pixelsId->isAllFilled()) {
			$pixelsId->createOne();
		}
	}

	private function setDefaults(Form $form): void
	{
		if ($this->configurator->facebookPixel) {
			foreach ($this->configurator->facebookPixel as $key => $config) {
				$form['facebookPixel'][$key]->setDefaults([
					'pixelId' => $config->pixelId,
					'accessToken' => $config->accessToken,
				]);
			}
		}
	}
}