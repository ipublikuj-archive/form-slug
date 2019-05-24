# Form Slug control

[![Build Status](https://img.shields.io/travis/ipublikuj-ui/form-slug.svg?style=flat-square)](https://travis-ci.org/ipublikuj-ui/form-slug)
[![Scrutinizer Code Coverage](https://img.shields.io/scrutinizer/coverage/g/ipublikuj-ui/form-slug.svg?style=flat-square)](https://scrutinizer-ci.com/g/ipublikuj-ui/form-slug/?branch=master)
[![Scrutinizer Code Quality](https://img.shields.io/scrutinizer/g/ipublikuj-ui/form-slug.svg?style=flat-square)](https://scrutinizer-ci.com/g/ipublikuj-ui/form-slug/?branch=master)
[![Latest Stable Version](https://img.shields.io/packagist/v/ipub/form-slug.svg?style=flat-square)](https://packagist.org/packages/ipub/form-slug)
[![Composer Downloads](https://img.shields.io/packagist/dt/ipub/form-slug.svg?style=flat-square)](https://packagist.org/packages/ipub/form-slug)
[![License](https://img.shields.io/packagist/l/ipub/form-slug.svg?style=flat-square)](https://packagist.org/packages/ipub/form-slug)

Forms control for adding slug filed for [Nette Framework](http://nette.org/)

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

## Documentation

Learn how to extend your forms with phone field in [documentation](https://github.com/iPublikuj/form-slug/blob/master/docs/en/index.md).
For JavaScript part of this extension please checkout [JS documentation](https://github.com/iPublikuj/form-slug/blob/master/public/readme.md)

***
Homepage [http://www.ipublikuj.eu](http://www.ipublikuj.eu) and repository [http://github.com/iPublikuj/form-slug](http://github.com/iPublikuj/form-slug).
