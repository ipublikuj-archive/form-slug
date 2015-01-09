<?php
/**
 * FormSlugExtension.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FormSlug!
 * @subpackage	DI
 * @since		5.0
 *
 * @date		08.01.15
 */

namespace IPub\FormSlug\DI;

use Nette;
use Nette\DI;
use Nette\PhpGenerator as Code;

class FormSlugExtension extends DI\CompilerExtension
{
	/**
	 * @param Code\ClassType $class
	 */
	public function afterCompile(Code\ClassType $class)
	{
		parent::afterCompile($class);

		$initialize = $class->methods['initialize'];
		$initialize->addBody('IPub\FormSlug\Controls\Slug::register();');
	}
}