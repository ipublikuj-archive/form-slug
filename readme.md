# Form Slug control

[![Build Status](https://img.shields.io/travis/iPublikuj/form-slug.svg?style=flat-square)](https://travis-ci.org/iPublikuj/form-slug)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/form-slug.svg?style=flat-square)](https://packagist.org/packages/ipub/form-slug)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/form-slug.svg?style=flat-square)](https://packagist.org/packages/ipub/form-slug)

Add form elements for creating slug fields under [Nette Framework](http://nette.org/) forms

## Installation

The best way to install ipub/form-slug is using  [Composer](http://getcomposer.org/):

```json
{
	"require": {
		"ipub/form-slug": "dev-master"
	}
}
```

or

```sh
$ composer require ipub/form-slug:@dev
```

After that you have to register extension in config.neon.

```neon
extensions:
	formSlug: IPub\FormSlug\DI\FormSlugExtension
```

> In Nette 2.0, registration is done in `app/bootstrap.php`:
```php
$configurator->onCompile[] = function ($configurator, $compiler) {
	$compiler->addExtension('formSlug', new IPub\FormSlug\DI\FormSlugExtension);
};
```

And you also need to include static files into your page:

```html
	<script src="{$basePath}/libs/ipub.formSlug.js"></script>
</body>
```

note: You have to upload static files from **client-site** folder to your project.

## Usage

```php
$form->addSlug('slug', 'Slug:');
```

You can connect other text inputs to this field and activate auto-generation slug, for example from item name:

```php
$form->addText('name', 'Name:')

$form->addSlug('slug', 'Slug:')
	->addField($form['name']);
```

You can add more than one field:

```php
$form->addSlug('slug', 'Slug:')
	->addField($form['name'])
	->addField($form['subname']);
```

This control return values as normal text input, so you can acces your slug like this:

```php
$slug = $form->values->slug;
```

### Validation

Validation can be done as on usual text input, no changes are made here.

### Custom templates and rendering

This control come with templating feature, that mean form element of this control is rendered in latte template. There are 3 predefined templates:

* bootstrap.latte if you are using [Twitter Bootstrap](http://getbootstrap.com/)
* uikit.latte if you are using [YooTheme UIKit](http://getuikit.com/)
* default.latte for custom styling (this template is loaded as default)

You can also define you own template if you need to fit this control into your layout.

Template can be set in form definition:

```php
$form->addSlug('slug', 'Slug:')
	->setTemplate('path/to/your/template.latte');
```

or in manual renderer of the form:

```php
{?$form['slug']->setTemplate('path/to/your/template.latte')}
{input slug class => "some-custom-class"}
```

and if you want to switch default template to **bootstrap** or **uikit** just it write into template setter:

```php
$form->addSlug('slug', 'Slug:')
	->setTemplate('bootstrap.latte');
```

or

```php
{?$form['slug']->setTemplate('bootstrap.latte')}
{input slug class => "some-custom-class"}
```
