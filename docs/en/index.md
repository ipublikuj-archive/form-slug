# Quickstart

This extension extend your [Nette](http://nette.org) forms with slug control field with automatic slug creation based on selected fields.

## Installation

The best way to install ipub/form-slug is using [Composer](http://getcomposer.org/):

```sh
$ composer require ipub/form-slug:@dev
```

After that you have to register extension in config.neon.

```neon
extensions:
	formSlug: IPub\FormSlug\DI\FormSlugExtension
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
	->setTemplateFile('path/to/your/template.latte');
```

or in manual renderer of the form:

```php
{?$form['slug']->setTemplateFile('path/to/your/template.latte')}
{input slug class => "some-custom-class"}
```

and if you want to switch default template to **bootstrap** or **uikit** just it write into template setter:

```php
$form->addSlug('slug', 'Slug:')
	->setTemplateFile('bootstrap.latte');
```

or

```php
{?$form['slug']->setTemplateFile('bootstrap.latte')}
{input slug class => "some-custom-class"}
```
