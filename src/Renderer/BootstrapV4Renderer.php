<?php

declare(strict_types=1);

namespace MatiCore\Form;


use Nette\Forms\Controls\BaseControl;
use Nette\Forms\Controls\Button;
use Nette\Forms\Controls\Checkbox;
use Nette\Forms\Controls\RadioList;
use Nette\Forms\Controls\TextInput;
use Nette\Forms\Controls\UploadControl;
use Nette\Forms\IControl;
use Nette\Forms\Rendering\DefaultFormRenderer;
use Nette\InvalidArgumentException;
use Nette\Utils\Html;
use Nette\Utils\Strings;

/**
 * Class BootstrapV4Renderer
 * @package MatiCore\Form
 */
class BootstrapV4Renderer extends DefaultFormRenderer
{

	public function __construct()
	{
		$this->wrappers = [
			'form' => [
				'container' => null,
			],
			'error' => [
				'container' => 'div class="row mb-3"',
				'item' => 'div class="col-12 alert alert-danger"',
			],
			'group' => [
				'container' => null,
				'label' => 'p class="h3 modal-header"',
				'description' => 'p class="pl-3 lead"',
			],
			'controls' => [
				'container' => null,
			],
			'pair' => [
				'container' => 'div class="form-group row"',
				'.required' => null,
				'.optional' => null,
				'.odd' => null,
				'.error' => null,
			],
			'control' => [
				'container' => 'div class="col-lg-9 col-md-9 col-sm-12"',
				'.odd' => null,
				'description' => 'small class="form-text text-muted"',
				'requiredsuffix' => null,
				'errorcontainer' => 'div class="invalid-feedback"',
				'erroritem' => null,
				'.required' => null,
				'.text' => null,
				'.password' => null,
				'.file' => null,
				'.email' => null,
				'.number' => null,
				'.submit' => null,
				'.image' => null,
				'.button' => null,
			],
			'label' => [
				'container' => 'div class="col-lg-3 col-md-3 text-md-right col-sm-12"',
				'suffix' => null,
				'requiredsuffix' => '*',
			],
			'hidden' => [
				'container' => null,
			],
		];
	}

	/**
	 * @param IControl $control
	 * @param bool $own
	 * @return string
	 */
	public function renderErrors(IControl $control = null, bool $own = true): string
	{
		$temp = null;

		if ($control instanceof Checkbox || $control instanceof RadioList || $control instanceof UploadControl) {
			$temp = $this->wrappers['control']['errorcontainer'];
			$this->wrappers['control']['errorcontainer'] .= ' style="display: block"';
		}

		$parent = parent::renderErrors($control, $own);

		if ($control instanceof Checkbox || $control instanceof RadioList || $control instanceof UploadControl) {
			$this->wrappers['control']['errorcontainer'] = $temp;
		}

		return $parent;
	}

	/**
	 * @param IControl[] $controls
	 * @return string
	 */
	public function renderPairMulti(array $controls): string
	{
		$isPrimarySet = false;

		foreach ($controls as $control) {
			if ($control instanceof Button) {
				if ($control->getControlPrototype()->getAttribute('class') === null
					|| (\is_array($control->getControlPrototype()->getAttribute('class'))
						&& !Strings::contains(
							implode(' ', array_keys($control->getControlPrototype()->getAttribute('class'))),
							'btn btn-'
						)
					)
				) {
					$control->getControlPrototype()
						->appendAttribute('class', ($isPrimarySet === false ? 'btn btn-primary' : 'btn btn-secondary'));
				}
				$isPrimarySet = true;
			}
		}

		return parent::renderPairMulti($controls);
	}

	/**
	 * @param IControl $control
	 * @return Html
	 */
	public function renderLabel(IControl $control): Html
	{
		if ($control instanceof Checkbox) {
			$control->getLabelPrototype()
				->appendAttribute('class', 'form-check-label');
		} elseif ($control instanceof RadioList) {
			$control->getLabelPrototype()
				->appendAttribute('class', 'form-check-label');
		} elseif ($control instanceof BaseControl) {
			$control->getLabelPrototype()
				->appendAttribute('class', 'col-form-label');
		}

		return parent::renderLabel($control);
	}

	/**
	 * @param IControl $control
	 * @return Html
	 * @throws InvalidArgumentException
	 */
	public function renderControl(IControl $control): Html
	{
		if ($control instanceof Checkbox) {
			$control->getControlPrototype()
				->appendAttribute('class', 'form-check-input');
		} elseif ($control instanceof RadioList) {
			$control->getContainerPrototype()
				->setName('div')
				->appendAttribute('class', 'form-check');

			$control->getItemLabelPrototype()
				->appendAttribute('class', 'form-check-label');
			$control->getControlPrototype()
				->appendAttribute('class', 'form-check-input');
		} elseif ($control instanceof UploadControl) {
			$control->getControlPrototype()
				->appendAttribute('class', 'form-control-file');
		} elseif ($control instanceof BaseControl) {
			if ($control->hasErrors()) {
				$control->getControlPrototype()
					->appendAttribute('class', 'is-invalid');
			}
			$control->getControlPrototype()
				->appendAttribute('class', 'form-control');
		}

		$parent = parent::renderControl($control);

		if ($control instanceof TextInput) {
			$leftAddon = $control->getOption('left-addon');
			$rightAddon = $control->getOption('right-addon');
			if ($leftAddon !== null || $rightAddon !== null) {
				$children = $parent->getChildren();
				$parent->removeChildren();
				$container = Html::el('div')->setAttribute('class', 'input-group');
				if ($leftAddon !== null) {
					if (!\is_array($leftAddon)) {
						$leftAddon = [$leftAddon];
					}
					$div = Html::el('div')->setAttribute('class', 'input-group-prepend');
					foreach ($leftAddon as $v) {
						$div->insert(null, Html::el('span')
							->setAttribute('class', 'input-group-text')
							->setText($v)
						);
					}
					$container->insert(null, $div);
				}

				foreach ($children as $child) {
					$foo = Strings::after($child, $control->getControlPart()->render());
					if ($foo !== false) {
						$container->insert(null, $control->getControlPart()->render());
						$description = $foo;
					} else {
						$container->insert(null, $child);
					}
				}

				if ($rightAddon !== null) {
					if (!\is_array($rightAddon)) {
						$rightAddon = [$rightAddon];
					}

					$div = Html::el('div')
						->setAttribute('class', 'input-group-append');

					foreach ($rightAddon as $v) {
						$div->insert(null, Html::el('span')
							->setAttribute('class', 'input-group-text')
							->setText($v)
						);
					}
					$container->insert(null, $div);
				}

				$parent->insert(null, $container);

				if (!empty($description)) {
					$parent->insert(null, $description);
				}
			}
		}

		return $parent;
	}

}