<?php
/**
 * Slug.php
 *
 * @copyright	More in license.md
 * @license		http://www.ipublikuj.eu
 * @author		Adam Kadlec http://www.ipublikuj.eu
 * @package		iPublikuj:FormSlug!
 * @subpackage	Controls
 * @since		5.0
 *
 * @date		08.01.15
 */

namespace IPub\FormSlug\Controls;

use Nette;
use Nette\Application\UI;
use Nette\Bridges;
use Nette\Forms;
use Nette\Localization;

use Latte;

use IPub;
use IPub\FormSlug;

class Slug extends Forms\Controls\TextInput
{
	/**
	 * @var string
	 */
	private $templatePath;

	/**
	 * @var UI\ITemplate
	 */
	private $template;

	/**
	 * @var Forms\Controls\BaseControl[]
	 */
	private $fields = [];

	/**
	 * Toggle box selector
	 *
	 * @var string
	 */
	private $toggleBox = '.ipub-slug-box';

	/**
	 * @var bool
	 */
	private static $registered = FALSE;

	/**
	 * Enable or disable one time auto updating slug field from watched fields
	 *
	 * @var bool
	 */
	private $onetimeAutoUpdate = TRUE;

	/**
	 * This method will be called when the component becomes attached to Form
	 *
	 * @param  Nette\ComponentModel\IComponent
	 */
	public function attached($form)
	{
		parent::attached($form);

		// Create control template
		$this->template = $this->createTemplate();
	}

	/**
	 * Add filed from which slug will be created
	 *
	 * @param Forms\Controls\BaseControl $field
	 *
	 * @return $this
	 */
	public function addField(Forms\Controls\BaseControl $field)
	{
		// Assign filed to collection
		$this->fields[$field->getHtmlId()] = $field;

		return $this;
	}

	/**
	 * Generates control's HTML element.
	 */
	public function getControl()
	{
		// Create form input
		$input = parent::getControl();

		// If template file was not defined before...
		if ($this->template->getFile() === NULL) {
			// ...try to get base control template file
			$templatePath = !empty($this->templatePath) ? $this->templatePath : __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR .'default.latte';
			// ...& set it to template engine
			$this->template->setFile($templatePath);
		}

		// Assign vars to template
		$this->template->input		= $input;
		$this->template->value		= $this->getValue();
		$this->template->caption	= $this->caption;
		$this->template->_form		= $this->getForm();
		// Control js settings
		$this->template->settings	= [
			'toggle'	=> $this->toggleBox,
			'onetime'	=> $this->onetimeAutoUpdate,
			'fields'	=> (array_reduce($this->fields, function (array $result, Forms\Controls\BaseControl $row) {
				$result[] = '#'. $row->getHtmlId();

				return $result;
			}, []))
		];

		return FormSlug\Utils\Html::el()
			->add($this->template);
	}

	/**
	 * @return Bridges\ApplicationLatte\Template
	 */
	protected function createTemplate()
	{
		// Create latte engine
		$latte = new Latte\Engine;

		// Check for cache dir for latte files
		if (defined('TEMP_DIR') && ($cacheFolder = TEMP_DIR . DIRECTORY_SEPARATOR .'cache'. DIRECTORY_SEPARATOR .'latte' AND is_dir($cacheFolder))) {
			$latte->setTempDirectory($cacheFolder);
		}

		$latte->onCompile[] = function($latte) {
			// Register form macros
			Bridges\FormsLatte\FormMacros::install($latte->getCompiler());
		};

		// Create nette template from latte engine
		$template = new Bridges\ApplicationLatte\Template($latte);

		// Check if translator is available
		if ($this->getTranslator() instanceof Localization\ITranslator) {
			$template->setTranslator($this->getTranslator());
		}

		return $template;
	}

	/**
	 * @return UI\ITemplate|Bridges\ApplicationLatte\Template
	 */
	public function getTemplate()
	{
		if ($this->template === NULL) {
			$this->template = $this->createTemplate();
		}

		return $this->template;
	}

	/**
	 * Change default control template path
	 *
	 * @param string $templatePath
	 *
	 * @return $this
	 *
	 * @throws \Nette\FileNotFoundException
	 */
	public function setTemplate($templatePath)
	{
		// Check if template file exists...
		if (!is_file($templatePath)) {
			// ...check if extension template is used
			if (is_file(__DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $templatePath)) {
				$templatePath = __DIR__ . DIRECTORY_SEPARATOR .'template'. DIRECTORY_SEPARATOR . $templatePath;

			} else {
				// ...if not throw exception
				throw new Nette\FileNotFoundException('Template file "'. $templatePath .'" was not found.');
			}
		}

		$this->templatePath = $templatePath;

		return $this;
	}

	/**
	 * @param string $method
	 */
	public static function register($method = 'addSlug')
	{
		// Check for multiple registration
		if (static::$registered) {
			throw new Nette\InvalidStateException('Slug control already registered.');
		}

		static::$registered = TRUE;

		$class = function_exists('get_called_class')?get_called_class():__CLASS__;
		Forms\Container::extensionMethod(
			$method, function (Forms\Container $form, $name, $label = NULL) use ($class) {
				$component = new $class($label);
				$form->addComponent($component, $name);
				return $component;
			}
		);
	}
}