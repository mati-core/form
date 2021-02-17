<?php

declare(strict_types=1);

namespace MatiCore\Form;

/**
 * Class InputNotImplementedException
 * @package MatiCore\Form
 */
class InputNotImplementedException extends \Exception
{

	/**
	 * @param string $class
	 * @param string $package
	 * @throws InputNotImplementedException
	 */
	public static function check(string $class, string $package): void
	{
		if (class_exists($class)) {
			return;
		}

		throw new self(
			'Input type "' . $class . '" does not exist.'
			. "\n"
			. 'Did you install "' . $package . '"?'
		);
	}

}