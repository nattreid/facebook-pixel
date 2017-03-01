<?php

declare(strict_types = 1);

namespace NAttreid\FacebookPixel\Hooks;

use NAttreid\Form\Form;
use NAttreid\WebManager\Services\Hooks\HookFactory;
use Nette\Utils\ArrayHash;

/**
 * Class FacebookPixelHook
 *
 * @author Attreid <attreid@gmail.com>
 */
class FacebookPixelHook extends HookFactory
{
	/** @var IConfigurator */
	protected $configurator;

	public function init()
	{
		$this->latte = __DIR__ . '/facebookPixelHook.latte';
	}

	/** @return Form */
	public function create()
	{
		$form = $this->formFactory->create();
		$form->setAjaxRequest();

		$form->addText('apiKey', 'webManager.web.hooks.facebookPixel.apiKey')
			->setDefaultValue($this->configurator->facebookPixelApiKey);

		$form->addSubmit('save', 'form.save');

		$form->onSuccess[] = [$this, 'facebookPixelFormSucceeded'];

		return $form;
	}

	public function facebookPixelFormSucceeded(Form $form, ArrayHash $values)
	{
		$this->configurator->facebookPixelApiKey = $values->apiKey;

		$this->flashNotifier->success('default.dataSaved');
	}
}