<?php
/**
 * Test: IPub\Forms\SlugInput
 * @testCase
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FormSlug!
 * @subpackage     Tests
 * @since          5.0
 *
 * @date           10.01.15
 */

declare(strict_types = 1);

namespace IPubTests\Forms\Slug;

use Nette;
use Nette\Application\UI;
use Nette\Forms;

use Tester;
use Tester\Assert;

use IPub;
use IPub\FormSlug;

require __DIR__ . DIRECTORY_SEPARATOR . '..' . DIRECTORY_SEPARATOR . 'bootstrap.php';

class SlugInputTest extends Tester\TestCase
{
	/**
	 * @var UI\ITemplateFactory
	 */
	private $templateFactory;

	/**
	 * {@inheritdoc}
	 */
	public function setUp()
	{
		parent::setUp();

		$dic = $this->createContainer();

		// Get phone helper from container
		$this->templateFactory = $dic->getByType(UI\ITemplateFactory::class);
	}

	public function testHtml()
	{
		// Create form control
		$control = $this->createControl();
		// Set form control value
		$control->setValue('some-slug');

		$dq = Tester\DomQuery::fromHtml((string) $control->getControl());

		Assert::true($dq->has("input[value='some-slug']"));
	}

	/**
	 * @throws Nette\InvalidStateException
	 */
	public function testRegistrationMultiple()
	{
		FormSlug\Controls\Slug::register($this->templateFactory);
		FormSlug\Controls\Slug::register($this->templateFactory);
	}

	public function testRegistration()
	{
		FormSlug\Controls\Slug::register($this->templateFactory);

		// Create form
		$form = new Forms\Form;
		// Create form control
		$control = $form->addSlug('slug', 'Slug');

		Assert::type('IPub\FormSlug\Controls\Slug', $control);
		Assert::equal('slug', $control->getName());
		Assert::equal('Slug', $control->caption);
		Assert::same($form, $control->getForm());
	}

	/**
	 * @param array $data
	 *
	 * @return FormSlug\Controls\Slug
	 */
	private function createControl($data = [])
	{
		$_SERVER['REQUEST_METHOD'] = 'POST';
		$_FILES = [];
		$_POST = $data;

		// Create form
		$form = new Forms\Form;
		// Create form control
		$control = new FormSlug\Controls\Slug($this->templateFactory);
		// Add form control to form
		$form->addComponent($control, 'slug');

		return $control;
	}

	/**
	 * @return Nette\DI\Container
	 */
	protected function createContainer() : Nette\DI\Container
	{
		$config = new Nette\Configurator();
		$config->setTempDirectory(TEMP_DIR);

		return $config->createContainer();
	}
}

\run(new SlugInputTest());
