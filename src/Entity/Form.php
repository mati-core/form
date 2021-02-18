<?php

declare(strict_types=1);

namespace MatiCore\Form;


use IPub\FormPhone\Controls\Phone;
use Nette\Forms\Controls\TextInput;
use Nette\Utils\DateTime;
use Nette\Utils\Strings;

/**
 * Class Form
 * @package MatiCore\Form
 */
class Form extends \Nette\Application\UI\Form
{

	/**
	 * @var ContainerAccessor
	 */
	private $containerAccessor;

	/**
	 * @param ContainerAccessor $containerAccessor
	 */
	public function __construct(ContainerAccessor $containerAccessor)
	{
		parent::__construct();
		$this->containerAccessor = $containerAccessor;
	}

	/**
	 * @param string $name
	 * @param string|null $label
	 * @param string $pattern
	 * @return TextInput
	 */
	public function addZipCode(string $name, ?string $label = null, string $pattern = '\s*\d{3}\s*\d{2}\s*'): TextInput
	{
		return $this[$name] = (new TextInput($label))
			->setRequired(false)
			->addRule(self::PATTERN, 'Enter valid ZIP code (999 99)', $pattern)
			->addFilter(function (string $value): string {
				$value = (string) str_replace(' ', '', Strings::normalize($value));

				return substr($value, 0, 3) . ' ' . substr($value, 3, 2);
			});
	}

	/**
	 * @param string $name
	 * @param string|null $label
	 * @return TextInput
	 */
	public function addDate(string $name, ?string $label = null): TextInput
	{
		return $this[$name] = (new TextInput($label))
			->setRequired(false)
			->setHtmlAttribute('class', 'form-control datepicker')
			->addFilter(function (?string $value): ?\DateTime {
				if ($value === null || trim($value) === '') {
					return null;
				}

				return DateTime::from($value);
			});
	}

	/**
	 * @param string $name
	 * @param string|null $label
	 * @return TextInput
	 */
	public function addDateTime(string $name, ?string $label = null): TextInput
	{
		return $this[$name] = (new TextInput($label))
			->setRequired(false)
			->setHtmlAttribute('class', 'form-control datepicker')
			->addFilter(function (?string $value): ?\DateTime {
				if ($value === null || trim($value) === '') {
					return null;
				}

				return DateTime::from($value);
			});
	}

}