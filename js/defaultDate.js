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
        var $el = $(this);
        var val = $el.val();
        // If default date is in Y-m-d format, switch to d/m/Y for browsers
        // that don't support html5 date format.
        if (val.match(/[0-9]{4}-[0-9]{2}-[0-9]{2}/)) {
          var parts = val.split('-');
          $el.val(parts[2] + '/' + parts[1] + '/' + parts[0]);
        }
      });
    }
  };

})(jQuery, Drupal);
