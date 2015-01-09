## Javascript API Documentation

API for Slug is accessible in global object `window.IPub.Forms.Slug`.

### Loading

Serverside part of Slug form element is element with custom data attribute `data-ipub-forms-slug`. This element can be initialized with method `initialize()`.

```js
IPub.Forms.Slug.initialize($('[data-ipub-forms-slug]'));
```

But there is shortcut implemented as jQuery plugin:

```js
$('[data-ipub-forms-slug]').ipubFormsSlug();
```

You can chain other jQuery methods after this as usual. If you try to initialize one Slug twice, it will fail silently (second initialization won't proceed).

Finally you can initialize all standard GpsPickers on the page by calling:

```js
IPub.Forms.Slug.load();
```

This will be automatically called when document is ready.

### Change event

You can listen to event, when slug is changed:

```js
$('#foo').on('change.ipub.forms.slug', function (e, slug) {
	console.log('new slug: ', slug);
});
```