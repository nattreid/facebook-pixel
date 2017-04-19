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

		$pixels = $form->addDynamic('pixelsId', function (Container $container) {
			$container->addText('pixelId', 'webManager.web.hooks.facebookPixel.pixelId');
			$container->addSubmit('remove', 'default.delete')
				->setValidationScope(FALSE)
				->onClick[] = [$this, 'removePixel'];
		});

		$pixels->addSubmit('add', 'default.add')
			->setValidationScope(FALSE)
			->onClick[] = [$this, 'addPixel'];

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

	public function removePixel(SubmitButton $button): void
	{
		$id = $button->parent->name;
		$arr = $this->configurator->facebookPixelId;
		unset($arr[$id]);
		$this->configurator->facebookPixelId = $arr;

		$this->onDataChange();
	}

	public function addPixel(SubmitButton $button): void
	{
		/* @var $pixelsId \Kdyby\Replicator\Container */
		$pixelsId = $button->parent;

		if ($pixelsId->isAllFilled()) {
			$pixelsId->createOne();
		}
	}

	private function setDefaults(Form $form): void
	{
		if ($this->configurator->facebookPixelId) {
			foreach ($this->configurator->facebookPixelId as $key => $id) {
				$form['pixelsId'][$key]->setDefaults([
					'pixelId' => $id
				]);
			}
		}
	}
}