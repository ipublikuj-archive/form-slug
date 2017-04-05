<?php
/**
 * Html.php
 *
 * @copyright      More in license.md
 * @license        http://www.ipublikuj.eu
 * @author         Adam Kadlec http://www.ipublikuj.eu
 * @package        iPublikuj:FormSlug!
 * @subpackage     Utils
 * @since          1.0.0
 *
 * @date           09.01.15
 */

declare(strict_types = 1);

namespace IPub\FormSlug\Utils;

use Nette;
use Nette\Utils;
use Nette\Bridges;

class Html extends Utils\Html
{
	/**
	 * Inserts child node
	 *
	 * @param int
	 * @param Utils\Html|Bridges\ApplicationLatte\Template|string node
	 * @param bool
	 *
	 * @return $this
	 *
	 * @throws \Exception
	 */
	public function insert($index, $child, $replace = FALSE)
	{
		if ($child instanceof Bridges\ApplicationLatte\Template) {
			// Append
			if ($index === NULL) {
				$this->children[] = $child;

				// Insert or replace
			} else {
				array_splice($this->children, (int) $index, $replace ? 1 : 0, [$child]);
			}

		} else {
			parent::insert($index, $child, $replace);
		}

		return $this;
	}

	/**
	 * Renders element's start tag, content and end tag
	 *
	 * @param int $indent
	 *
	 * @return string
	 */
	public function render($indent = NULL)
	{
		$s = $this->startTag();

		if ($this->isEmpty() !== FALSE) {
			// Add content
			if ($indent !== NULL) {
				$indent++;
			}

			foreach ($this->children as $child) {
				if ($child instanceof Bridges\ApplicationLatte\Template) {
					// Pass form element attributes to input element
					$child->input->addAttributes($this->attrs);

					// Render template into string
					$s .= (string) $child;

				} else if (is_object($child)) {
					$s .= $child->render($indent);

				} else {
					$s .= $child;
				}
			}

			// add end tag
			$s .= $this->endTag();
		}

		if ($indent !== NULL) {
			return "\n" . str_repeat("\t", $indent - 1) . $s . "\n" . str_repeat("\t", max(0, $indent - 2));
		}

		return $s;
	}
}
