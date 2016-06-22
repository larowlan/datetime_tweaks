/**
 * @file
 * Default date values.
 */

(function ($, Drupal) {

  'use strict';

  Drupal.behaviors.datetimeTweaksDefaultDate = {
    attach: function (context, settings) {
      var $context = $(context);
      // Skip if date is supported by the browser.
      if (Modernizr.inputtypes.date === true) {
        return;
      }
      $context.find('input[data-drupal-date-format]').once('default-date').each(function () {
        var $el = $(this), parts;
        var val = $el.val();
        var regexp = /[0-9]{4}-[0-9]{2}-[0-9]{2}/;
        if (val.match(regexp)) {
          parts = val.split('-');
          $el.val(parts[2] + '/' + parts[1] + '/' + parts[0]);
        }
        var attributes = ['min', 'max'], i, field;
        for (i in attributes) {
          if (attributes.hasOwnProperty(i)) {
            field = attributes[i];
            val = $el.attr(field);
            if (val && val.match(regexp)) {
              parts = val.split('-');
              $el.attr(field, parts[2] + '/' + parts[1] + '/' + parts[0]);
            }
          }
        }
      });
    }
  };

})(jQuery, Drupal);
