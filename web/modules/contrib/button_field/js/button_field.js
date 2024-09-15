(function ($) {
Drupal.behaviors.buttonFieldBehavior = {
  attach: function(context) {
    var $fields = $('.button_field_confirm', context),
      guid = $.guid++;
      handler = function(event) {
        // If the user chooses to continue, initiate the ajax callback in the
        // same manner that the original handler would have.
        if (confirm(Drupal.settings[$(this).attr('id')].confirmation)) {
          var ajax = Drupal.ajax[$(this).attr('id')];
          return ajax.eventResponse(this, event);
        }
      };

    // Iterate over each of the button fields on the page.
    $fields.each(function() {
      var $field = $(this),
        events = $field.data('events') || {};

      // Iterate over all of the mousedown events attached to the field until
      // we find the ajax callback handler. Once we find it, replace it with our
      // own handler.
      events.mousedown = events.mousedown || [];
      $(events.mousedown).each(function(i, el) {
        if (el.handler.toString().match('ajax.eventResponse')) {
          el.guid = guid;
          el.handler = handler;

          return false;
        }
      });
    });
  }
};
})(jQuery);
