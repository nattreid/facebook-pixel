<?php

declare(strict_types=1);

namespace NAttreid\FacebookPixel\Hooks;

use NAttreid\Form\Form;
use NAttreid\WebManager\Services\Hooks\HookFactory;
use Nette\ComponentModel\Component;
use Nette\Forms\Container;
use Nette\Forms\Controls\SubmitButton;
use Tracy\Debugger;

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

		$pixels = $form->addMultiplier('pixelsId', function (Container $container) {
			$container->addText('pixelId', 'webManager.web.hooks.facebookPixel.pixelId');
		});

		$pixels->addCreateButton('default.add');
		$pixels->addRemoveButton('default.delete');

		$this->setDefaults($form);

		$form->addSubmit('save', 'form.save')
			->onClick[] = [$this, 'facebookPixelFormSucceeded'];

		return $form;
	}

	public function facebookPixelFormSucceeded(SubmitButton $button): void
	{
		$arr = [];
		foreach ($button->form['pixelsId']->values as $values) {
			$arr[] = $values->pixelId;
		}
		$this->configurator->facebookPixelId = $arr;

		$this->flashNotifier->success('default.dataSaved');
	}

	private function setDefaults(Form $form): void
	{
		if ($this->configurator->facebookPixelId) {
			$defaults = [];
			foreach ($this->configurator->facebookPixelId as $pixelId) {
				$defaults[] = [
					'pixelId' => $pixelId
				];
			}
			$form['pixelsId']->setDefaults($defaults);
		}
	}
}