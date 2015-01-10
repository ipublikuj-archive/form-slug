<?php
/**
* Test: Nella\Forms\DateTime\DateInput
* @testCase
*
* This file is part of the Nella Project (http://nella-project.org).
*
* Copyright (c) Patrik Votoček (http://patrik.votocek.cz)
*
* For the full copyright and license information,
* please view the file LICENSE.md that was distributed with this source code.
*/

namespace IPub\Forms\Slug;

use Nette;
use Nette\Forms;

use Tester;
use Tester\Assert;

use IPub;
use IPub\FormSlug;

require __DIR__ . '/../../../bootstrap.php';

class SlugInputTest extends Tester\TestCase
{
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
		FormSlug\Controls\Slug::register();
		FormSlug\Controls\Slug::register();
	}

	public function testRegistration()
	{
		FormSlug\Controls\Slug::register();

		// Create form
		$form = new Forms\Form;
		// Create form control
		$control = $form->addSlug('slug', 'Slug');

		Assert::type('FormSlug\Controls\Slug', $control);
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
		$_FILES = array();
		$_POST = $data;

		// Create form
		$form = new Forms\Form;
		// Create form control
		$control = new FormSlug\Controls\Slug;
		// Add form control to form
		$form->addComponent($control, 'slug');

		return $control;
	}
}

id(new SlugInputTest)->run(isset($_SERVER['argv'][1]) ? $_SERVER['argv'][1] : NULL);