<?php

namespace NAttreid\FacebookPixel\Hooks;

use NAttreid\Form\Form;
use NAttreid\WebManager\Services\Hooks\HookFactory;

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

		$form->onSuccess[] = [$this, 'mailchimpFormSucceeded'];

		return $form;
	}

	public function mailchimpFormSucceeded(Form $form, $values)
	{
		$this->configurator->facebookPixelApiKey = $values->apiKey;

		$this->flashNotifier->success('default.dataSaved');
	}
}