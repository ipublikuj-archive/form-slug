/**
 * @package     iPublikuj:FormSlug!
 * @copyright   Copyright (C) 2015. All rights reserved.
 * @license     http://www.ipublikuj.eu
 * @author      Adam Kadlec (http://www.ipublikuj.eu)
 *
 * For the full copyright and license information, please view
 * the file LICENSE.md that was distributed with this source code.
 */

/**
 * Client-side script for iPublikuj:FormSlug!
 *
 * @author      Adam Kadlec (http://www.ipublikuj.eu)
 * @package     iPublikuj:FormSlug!
 * @version     1.0.0
 *
 * @param {jQuery} $ (version > 1.7)
 * @param {Window} window
 * @param {Document} document
 * @param {Location} location
 * @param {Navigator} navigator
 */
;(function ($, window, document, location, navigator) {
    /* jshint laxbreak: true, expr: true */
    "use strict";

    var IPub = window.IPub || {};

    IPub.Forms = IPub.Forms || {};

    /**
     * Forms slug extension definition
     *
     * @param {jQuery} $element
     * @param {Object} options
     */
    IPub.Forms.Slug = function ($element, options) {
        this.$element = $element;

        this.name = this.$element.prop('id');
        this.options = $.extend({
            'toggle': null,
            'onetime': true,
            'forceEdit': false,
            'fields': []
        }, $.fn.ipubFormsSlug.defaults, options, this.$element.data('settings') || {});
    };

    IPub.Forms.Slug.prototype =
        {
            // Initial function.
            init: function () {
                var that = this;

                this.$panel = this.$element.find("div.ipub-slug-panel");
                this.$field = this.$panel.find("input:text");
                this.$boxes = this.$element.find(this.options.toggle);

                // Get all buttons
                this.buttons = {
                    $trigger: this.$element.find('[data-action="slug.show"]'),
                    $accept: this.$panel.find('[data-action="slug.accept"]'),
                    $cancel: this.$panel.find('[data-action="slug.cancel"]')
                };

                // Check if we are editing existing slug or creating new
                this.options.edit = this.$field.val().trim() == '' ? false : true;

                if (!this.options.edit || this.options.forceEdit) {
                    $.each(this.options.fields, function () {
                        $(this).bind("blur.ipub.forms.slug", function () {
                            var val = helpers.slugify(this.value);

                            // Check if some text is entered
                            if (val != "") {
                                $.proxy(that.updateSlug(), that);

                                // Slug is update only once form this field
                                if (that.options.onetime) {
                                    // Remove update function after finishing
                                    $(this).unbind("blur.ipub.forms.slug")
                                }
                            }
                        });
                    });
                }

                this.buttons.$trigger.bind('click.ipub.forms.slug', function (event) {
                    event.preventDefault();

                    $.proxy(that.toggle(), that);

                    // Focus on slug field
                    that.$field.focus();

                    return false;
                });

                this.buttons.$accept.bind('click.ipub.forms.slug', function (event) {
                    event.preventDefault();

                    $.proxy(that.acceptSlug(), that);
                    $.proxy(that.toggle(), that);

                    return false;
                });

                this.buttons.$cancel.bind('click.ipub.forms.slug', function (event) {
                    event.preventDefault();

                    $.proxy(that.cancelSlug(), that);
                    $.proxy(that.toggle(), that);

                    return false;
                });

                return this;
            },

            toggle: function () {
                this.$boxes.each(function () {
                    if ($(this).is(':visible')) {
                        $(this).hide();

                    } else {
                        $(this).show();
                    }
                });
            },

            updateSlug: function () {
                var slug = this.generateSlug();

                // Check if some text is entered
                if (slug != "") {
                    // Update form field
                    this.$field[0].value = slug;
                    // Update trigger text
                    this.buttons.$trigger.text(slug);

                    // Fire change event
                    this.$element.trigger('change.ipub.forms.slug', slug);
                }
            },

            acceptSlug: function () {
                // Get the form field value and slugify it
                var val = helpers.slugify(this.$field[0].value);

                // Check for empty field
                if (val == "") {
                    val = this.generateSlug();
                }

                // Update form field
                this.$field[0].value = val;
                // Update trigger text
                this.buttons.$trigger.text(val);
            },

            cancelSlug: function () {
                // Revert form field value
                this.$field[0].value = this.buttons.$trigger.text();
            },

            generateSlug: function () {
                var slug = "";

                $.each(this.options.fields, function () {
                    var $field = $(this);

                    if ($field.length) {
                        slug = slug + ' ' + $field.val();
                    }
                });

                // Remove empty spaces
                $.trim(slug);
                // Slugify collected string
                slug = helpers.slugify(slug);

                return slug;
            }
        };

    /**
     * Initialize form slug plugin
     *
     * @param {jQuery} $element
     * @param {Object} options
     */
    IPub.Forms.Slug.initialize = function ($element, options) {
        $element.each(function () {
            var $this = $(this);

            if (!$this.data('ipub-forms-slug')) {
                $this.data('ipub-forms-slug', (new IPub.Forms.Slug($this, options).init()));
            }
        });
    };

    /**
     * Autoloading for form slug plugin
     *
     * @returns {jQuery}
     */
    IPub.Forms.Slug.load = function () {
        return $('[data-ipub-forms-slug]').ipubFormsSlug();
    };

    /**
     * IPub Forms slug helpers
     */

    var helpers =
        {
            tidymap: {"[\xa0\u2002\u2003\u2009]": " ", "\xb7": "*", "[\u2018\u2019]": "'", "[\u201c\u201d]": '"', "\u2026": "...", "\u2013": "-", "\u2014": "--", "\uFFFD": "&raquo;"},
            special: ['\'', 'À', 'à', 'Á', 'á', 'Â', 'â', 'Ã', 'ã', 'Ä', 'ä', 'Å', 'å', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'Ç', 'ç', 'Č', 'č', 'D', 'd', 'Ð', 'd', 'È', 'è', 'É', 'é', 'Ê', 'ê', 'Ë', 'ë', 'E', 'e', 'E', 'e', 'G', 'g', 'Ì', 'ì', 'Í', 'í', 'Î', 'î', 'Ï', 'ï', 'L', 'l', 'L', 'l', 'L', 'l', 'Ñ', 'ñ', 'N', 'n', 'N', 'n', 'Ò', 'ò', 'Ó', 'ó', 'Ô', 'ô', 'Õ', 'õ', 'Ö', 'ö', 'Ø', 'ø', 'o', 'R', 'r', 'R', 'r', 'Š', 'š', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'Ù', 'ù', 'Ú', 'ú', 'Û', 'û', 'Ü', 'ü', 'U', 'u', 'Ÿ', 'ÿ', 'ý', 'Ý', 'Ž', 'ž', 'Z', 'z', 'Z', 'z', 'Þ', 'þ', 'Ð', 'ð', 'ß', 'Œ', 'œ', 'Æ', 'æ', 'µ', 'Ğ', 'Ü', 'Ş', 'Ö', 'Ç', 'İ', 'ğ', 'ü', 'ş', 'ö', 'ç', 'ı'],
            standard: ['-', 'A', 'a', 'A', 'a', 'A', 'a', 'A', 'a', 'Ae', 'ae', 'A', 'a', 'A', 'a', 'A', 'a', 'C', 'c', 'C', 'c', 'C', 'c', 'C', 'c', 'D', 'd', 'D', 'd', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'E', 'e', 'G', 'g', 'I', 'i', 'I', 'i', 'I', 'i', 'I', 'i', 'L', 'l', 'L', 'l', 'L', 'l', 'N', 'n', 'N', 'n', 'N', 'n', 'O', 'o', 'O', 'o', 'O', 'o', 'O', 'o', 'Oe', 'oe', 'O', 'o', 'o', 'R', 'r', 'R', 'r', 'S', 's', 'S', 's', 'S', 's', 'T', 't', 'T', 't', 'T', 't', 'U', 'u', 'U', 'u', 'U', 'u', 'Ue', 'ue', 'U', 'u', 'Y', 'y', 'Y', 'y', 'Z', 'z', 'Z', 'z', 'Z', 'z', 'TH', 'th', 'DH', 'dh', 'ss', 'OE', 'oe', 'AE', 'ae', 'u', 'g', 'u', 's', 'o', 'c', 'i', 'g', 'u', 's', 'o', 'c', 'i'],

            // Remove special chars
            slugify: function (txt) {
                var that = this;

                txt = txt.toString();

                var nodiac = {
                    'á': 'a',
                    'č': 'c',
                    'ď': 'd',
                    'é': 'e',
                    'ě': 'e',
                    'í': 'i',
                    'ň': 'n',
                    'ó': 'o',
                    'ř': 'r',
                    'š': 's',
                    'ť': 't',
                    'ú': 'u',
                    'ů': 'u',
                    'ý': 'y',
                    'ž': 'z',
                    '.': '_'
                };

                var tmp = '';

                for (var i = 0; i < txt.length; i++) {
                    tmp += (typeof nodiac[txt.charAt(i)] != 'undefined' ? nodiac[txt.charAt(i)] : txt.charAt(i));
                }

                txt = tmp;

                $.each(this.tidymap, function (key, value) {
                    txt = txt.replace(new RegExp(key, 'g'), value);
                });

                $.each(this.special, function (i, ch) {
                    txt = txt.replace(new RegExp(ch, 'g'), that.standard[i]);
                });

                return $.trim(txt).replace(/\s+/g, '-').toLowerCase().replace(/[^\u0370-\u1FFF\u4E00-\u9FAFa-z0-9\-]/g, '').replace(/[-]+/g, '-').replace(/^[-]+/g, '').replace(/[-]+$/g, '');
            }
        };

    /**
     * IPub Forms slug plugin definition
     */

    var old = $.fn.ipubFormsSlug;

    $.fn.ipubFormsSlug = function (options) {
        return this.each(function () {
            var $this = $(this);

            if (!$this.data('ipub-forms-slug')) {
                $this.data('ipub-forms-slug', (new IPub.Forms.Slug($this, options).init()));
            }
        });
    };

    /**
     * IPub Forms slug plugin no conflict
     */

    $.fn.ipubFormsSlug.noConflict = function () {
        $.fn.ipubFormsSlug = old;

        return this;
    };

    /**
     * IPub Forms slug plugin default settings
     */

    $.fn.ipubFormsSlug.defaults = {
        toggle: '.ipub-slug-box',
        onetime: true,
        fields: []
    };

    /**
     * Complete plugin
     */

    // Autoload plugin
    IPub.Forms.Slug.load();

    // Autoload for ajax calls
    $(document).ajaxSuccess(function () {
        // Autoload plugin
        IPub.Forms.Slug.load();
    });

    // Assign plugin data to DOM
    window.IPub = IPub;

    return IPub;

})(jQuery, window, document, location, navigator);
