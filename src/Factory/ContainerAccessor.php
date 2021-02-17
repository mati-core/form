<?php

declare(strict_types=1);

namespace MatiCore\Form;


use Nette\DI\Container;

/**
 * Interface ContainerAccessor
 * @package MatiCore\Form
 */
interface ContainerAccessor
{

	/**
	 * @return Container
	 */
	public function get(): Container;

}