<?php

declare(strict_types=1);

namespace MatiCore\Form;


use Nette\DI\MissingServiceException;
use Nette\Localization\ITranslator;

/**
 * Class FormFactory
 * @package MatiCore\Form
 */
class FormFactory
{

	/**
	 * @var ContainerAccessor
	 */
	private $container;

	/**
	 * @var mixed[][]
	 */
	private $callbacks;

	/**
	 * @param ContainerAccessor $container
	 */
	public function __construct(ContainerAccessor $container)
	{
		$this->container = $container;
		$this->callbacks = [];
	}

	/**
	 * @return Form
	 */
	public function create(): Form
	{
		$form = new Form($this->container);

		$form->setRenderer(new BootstrapV4Renderer);
		$form->setTranslator($this->getTranslator());
		$form->addProtection('Timeout expired, please resubmit the form.');

		foreach ($this->callbacks as $callback) {
			if ($callback['type'] === 'onValidate') {
				$form->onValidate[] = $callback['service'];
			}
		}

		return $form;
	}

	/**
	 * @param string $type
	 * @param callable $service
	 */
	public function addCallback(string $type, $service): void
	{
		$this->callbacks[] = [
			'type' => $type,
			'service' => $service,
		];
	}

	/**
	 * @return ITranslator|null
	 */
	private function getTranslator(): ?ITranslator
	{
		static $translator;

		if ($translator === null) {
			try {
				$translator = $this->container->get()->getByType(ITranslator::class);
			} catch (MissingServiceException $e) {
			}
		}

		return $translator;
	}

}
