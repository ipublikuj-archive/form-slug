<?php
/**
 * Slug.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FormSlug!
 * @subpackage     Controls
 * @since          1.0.0
 *
 * @date           08.01.15
 */

namespace IPub\FormSlug\Controls;

use Nette;
use Nette\Application\UI;
use Nette\Bridges;
use Nette\Forms;

use IPub;
use IPub\FormSlug;
use IPub\FormSlug\Exceptions;

/**
 * Form slug control element
 *
 * @package        iPublikuj:FormSlug!
 * @subpackage     Controls
 *
 * @author         Adam Kadlec <adam.kadlec@ipublikuj.eu>
 */
class Slug extends Forms\Controls\TextInput
{
	/**
	 * @var string|NULL
	 */
	private $templateFile;

	/**
	 * @var UI\ITemplate
	 */
	private $template;

	/**
	 * @var UI\ITemplateFactory
	 */
	private $templateFactory;

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
	 * @param UI\ITemplateFactory $templateFactory
	 * @param string|NULL $label
	 * @param int|NULL $maxLength
	 */
	public function __construct(UI\ITemplateFactory $templateFactory, string $label = NULL, int $maxLength = NULL)
	{
		parent::__construct($label, $maxLength);

		$this->templateFactory = $templateFactory;
	}

	/**
	 * Add filed from which slug will be created
	 *
	 * @param Forms\Controls\BaseControl $field
	 *
	 * @return self
	 */
	public function addField(Forms\Controls\BaseControl $field)
	{
		// Assign filed to collection
		$this->fields[$field->getHtmlId()] = $field;

		return $this;
	}

	/**
	 * Generates control's HTML element
	 */
	public function getControl() : FormSlug\Utils\Html
	{
		// Create form input
		$input = parent::getControl();

		$template = $this->getTemplate();

		// If template file was not defined before...
		if ($template->getFile() === NULL) {
			// ...try to get base control template file
			$templateFile = $this->getTemplateFile();

			// ...& set it to template engine
			$template->setFile($templateFile);
		}

		// Assign vars to template
		$template->input = $input;
		$template->value = $this->getValue();
		$template->caption = $this->caption;
		$template->_form = $this->getForm();

		// Component js settings
		$template->settings = [
			'toggle'  => $this->toggleBox,
			'onetime' => $this->onetimeAutoUpdate,
			'fields'  => (array_reduce($this->fields, function (array $result, Forms\Controls\BaseControl $row) {
				$result[] = '#' . $row->getHtmlId();

				return $result;
			}, [])),
		];

		return FormSlug\Utils\Html::el()
			->addHtml($template);
	}

	/**
	 * @return UI\ITemplate|Bridges\ApplicationLatte\Template
	 */
	public function getTemplate() : UI\ITemplate
	{
		if ($this->template === NULL) {
			$this->template = $this->templateFactory->createTemplate();
			$this->template->setFile($this->getTemplateFile());
		}

		return $this->template;
	}

	/**
	 * Change default control template path
	 *
	 * @param string $templateFile
	 *
	 * @return void
	 *
	 * @throws Exceptions\FileNotFoundException
	 */
	public function setTemplateFile(string $templateFile)
	{
		// Check if template file exists...
		if (!is_file($templateFile)) {
			// ...check if extension template is used
			if (is_file(__DIR__ . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateFile)) {
				$templateFile = __DIR__ . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . $templateFile;

			} else {
				// ...if not throw exception
				throw new Exceptions\FileNotFoundException(sprintf('Template file "%s" was not found.', $templateFile));
			}
		}

		$this->templateFile = $templateFile;
	}

	/**
	 * @return string
	 */
	private function getTemplateFile() : string
	{
		return $this->templateFile !== NULL ? $this->templateFile : __DIR__ . DIRECTORY_SEPARATOR . 'template' . DIRECTORY_SEPARATOR . 'default.latte';
	}

	/**
	 * @param UI\ITemplateFactory $templateFactory
	 * @param string $method
	 *
	 * @return void
	 */
	public static function register(UI\ITemplateFactory $templateFactory, $method = 'addSlug')
	{
		// Check for multiple registration
		if (static::$registered) {
			throw new Exceptions\InvalidStateException('Slug control already registered.');
		}

		static::$registered = TRUE;

		$class = function_exists('get_called_class') ? get_called_class() : __CLASS__;
		Forms\Container::extensionMethod(
			$method, function (Forms\Container $form, $name, $label = NULL, $maxLength = NULL) use ($class, $templateFactory) {
			$component = new $class($templateFactory, $label, $maxLength);
			$form->addComponent($component, $name);

			return $component;
		}
		);
	}
}
