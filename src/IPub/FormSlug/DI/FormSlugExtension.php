<?php
/**
 * FormSlugExtension.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FormSlug!
 * @subpackage     DI
 * @since          1.0.0
 *
 * @date           08.01.15
 */

namespace IPub\FormSlug\DI;

use Nette;
use Nette\Application\UI;
use Nette\DI;
use Nette\PhpGenerator as Code;

/**
 * Form slug control extension container
 *
 * @package        iPublikuj:FormSlug!
 * @subpackage     DI
 *
 * @author         Adam Kadlec <adam.kadlec@fastybird.com>
 */
final class FormSlugExtension extends DI\CompilerExtension
{
	/**
	 * @param Code\ClassType $class
	 *
	 * @return void
	 */
	public function afterCompile(Code\ClassType $class)
	{
		parent::afterCompile($class);

		$builder = $this->getContainerBuilder();

		$initialize = $class->methods['initialize'];
		$initialize->addBody('IPub\FormSlug\Controls\Slug::register($this->getService(?));', [
			$builder->getByType(UI\ITemplateFactory::class)
		]);
	}
}
