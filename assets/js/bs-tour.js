(function ($, _, Drupal, drupalSettings) {
  'use strict';

  Drupal.behaviors.bsTour = {
    attach: function (context, settings) {
      $(window).on('load', function (event) {
        try
        {
          var tourOptions = $(drupalSettings.bs_tour.tour)[0];
          var tips = tourOptions.steps;
          var keyboard = tourOptions.keyboard;
          var debug = tourOptions.debug;
          var steps = [];

          for (var i = 0; i < tips.length; i++) {
            if ($(tips[i].element).length > 0) {
              tips[i].backdropPadding.top = parseInt(tips[i].backdropPadding.top);
              tips[i].backdropPadding.right = parseInt(tips[i].backdropPadding.right);
              tips[i].backdropPadding.bottom = parseInt(tips[i].backdropPadding.bottom);
              tips[i].backdropPadding.left = parseInt(tips[i].backdropPadding.left);

              switch (tips[i].backdrop) {
                case "0":
                  tips[i].backdrop = false;
                  break;

                case "1":
                  tips[i].backdrop = true;
                  break;
              }

              steps.push(tips[i]);
            }
          }

          if (steps.length) {
            var tour = new Tour({
              debug: debug,
              keyboard: keyboard,
              template: "<div class='popover tour'>\
              <div class='arrow'></div>\
              <h3 class='popover-title'></h3>\
              <div class='popover-content'></div>\
              <div class='popover-navigation'>\
              <button class='btn btn-default' data-role='prev'>« " + Drupal.t('Prev') + "</button>\
              <span data-role='separator'>|</span>\
              <button class='btn btn-default' data-role='next'>" + Drupal.t('Next') + " »</button>\
              <button class='btn btn-default' data-role='end'>" + Drupal.t('Skip tour') + "</button>\
              </div>\
              </div>",
            });

            // Add steps to the tour
            tour.addSteps(steps);

            // Initialize the tour
            tour.init();

            // Start the tour
            tour.start();
          }

        } catch (e) {
          // catch any fitvids errors
          window.console && console.warn('Bootstrap tour stopped with the following exception');
          window.console && console.error(e);
        }
      });
    }
  };

})(window.jQuery, window._, window.Drupal, window.drupalSettings);
