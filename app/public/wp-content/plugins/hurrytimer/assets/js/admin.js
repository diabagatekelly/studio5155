"use strict";

function _objectSpread(target) { for (var i = 1; i < arguments.length; i++) { var source = arguments[i] != null ? arguments[i] : {}; var ownKeys = Object.keys(source); if (typeof Object.getOwnPropertySymbols === 'function') { ownKeys = ownKeys.concat(Object.getOwnPropertySymbols(source).filter(function (sym) { return Object.getOwnPropertyDescriptor(source, sym).enumerable; })); } ownKeys.forEach(function (key) { _defineProperty(target, key, source[key]); }); } return target; }

function _defineProperty(obj, key, value) { if (key in obj) { Object.defineProperty(obj, key, { value: value, enumerable: true, configurable: true, writable: true }); } else { obj[key] = value; } return obj; }

(function ($) {
  'use strict';

  var styles = {};
  var campaignInnerPreviewRef = $('.hurrytimer-campaign');
  var campaignPreviewRef = $('#hurrytimer-campaign-preview');
  var headlinePreviewRef = campaignInnerPreviewRef.find('.hurrytimer-headline');
  var timerPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer');
  var timerDigitPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-digit');
  var timerLabelPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-label');
  var timerBlockPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-block');
  var timerSepPreviewRef = campaignInnerPreviewRef.find('.hurrytimer-timer-sep');
  var campaignCTA = campaignInnerPreviewRef.find('.hurrytimer-button');
  /**
   * Toggle the given block visibility.
   * @param {object} toggle
   * @param {object} block
   */

  function toggleBlockVisibility(toggle, block) {
    if (toggle.is(':checked')) {
      block.removeClass('hidden');

      if ($('input[name=block_separator_visibility]').is(':checked')) {
        block.next().removeClass('hidden');
      }
    } else {
      block.addClass('hidden');
      block.next().addClass('hidden');
    }
  }
  /**
   * Change element color for the preview.
   *
   * @param {object} inputElement
   * @param {string} color
   */


  function changeColor(inputElement) {
    var color = arguments.length > 1 && arguments[1] !== undefined ? arguments[1] : '';

    if (typeof inputElement === 'string') {
      inputElement = $('input[name="' + inputElement + '"]');
    }

    color = color || inputElement.val();
    var preview = inputElement.parent().find('.hurrytimer-color-preview');
    preview.css('background-color', color);

    if (color === 'transparent') {
      preview.addClass('transparent');
    } else {
      preview.removeClass('transparent');
    }

    setCSS(preview, 'background-color', color);

    switch (inputElement.attr('name')) {
      case 'digit_color':
        setCSS(timerDigitPreviewRef, 'color', color, false);
        setCSS(timerSepPreviewRef, 'color', color);
        break;

      case 'block_border_color':
        setCSS(timerBlockPreviewRef, 'border-color', color);
        break;

      case 'block_bg_color':
        setCSS(timerBlockPreviewRef, 'background-color', color);
        break;

      case 'label_color':
        setCSS(timerLabelPreviewRef, 'color', color);
        break;

      case 'headline_color':
        setCSS(headlinePreviewRef, 'color', color);
        break;

      case 'sticky_bar_bg_color':
        setCSS($('.hurrytimer-sticky'), 'background-color', color);
        break;

      case 'call_to_action[bg_color]':
        setCSS(campaignCTA, 'background-color', color);
        break;

      case 'call_to_action[text_color]':
        setCSS(campaignCTA, 'color', color);
        break;

      case 'sticky_bar_close_btn_color':
        setCSS($('.hurrytimer-sticky-close svg'), 'fill', color);
        break;
    }
  }
  /**
   * Apply CSS for live preview.
   *
   * @param {object} element
   * @param {string} property
   * @param {string} value
   * @param {boolean} apply
   */


  function setCSS(element, property, value) {
    var apply = arguments.length > 3 && arguments[3] !== undefined ? arguments[3] : true;
    styles = _objectSpread({}, styles, _defineProperty({}, element.selector, _objectSpread({}, styles[element.selector], _defineProperty({}, property, value))));

    if (apply) {
      if ($('#hurryt-styles').length === 0) {
        $('head').append('<style id="hurryt-styles"></style>');
      }

      var css = '';

      for (var selector in styles) {
        css += " ".concat(selector, "{ ").concat(Object.entries(styles[selector]).join(';').replace(/\,/g, ':'), "}");
      }

      $('#hurryt-styles').html(css);
    }
  }

  function removeCSSProperty(element, property) {
    for (var selector in styles) {
      if (selector === element.selector) {
        delete styles[selector][property];
      }
    }
  } // ------------------------------------------------------------
  // Initialize preview items color.
  // ------------------------------------------------------------


  changeColor('digit_color');
  changeColor('label_color');
  changeColor('block_border_color');
  changeColor('block_bg_color');
  changeColor('headline_color');
  changeColor('sticky_bar_bg_color');
  changeColor('sticky_bar_close_btn_color');
  changeColor('call_to_action[bg_color]');
  changeColor('call_to_action[text_color]'); // ------------------------------------------------------------
  // Input toggle.
  // ------------------------------------------------------------

  $('.js-hurrytimer-input-toggle').each(function () {
    var input = $(this);
    input.hide();
    var toggle = $("<input \n                      type=\"hidden\" \n                      name=\"".concat(input.attr('name'), "\" \n                      value=\"no\" />\n                      <span \n                      class=\"hurrytimer-input-toggle\">\n                      </span>"));
    input.before(toggle);

    if (input.prop('checked')) {
      toggle.addClass('is-on');
    }

    toggle.on('click', function () {
      toggle.toggleClass('is-on');
      input.attr('checked', !input.prop('checked'));
      input.trigger('change');
    });
  }); // ------------------------------------------------------------
  // Datetime picker.
  // ------------------------------------------------------------

  $('.hurrytimer-datepicker').each(function () {
    $(this).datetimepicker({
      controlType: 'select',
      dateFormat: 'yy-mm-dd',
      timeFormat: 'hh:mm TT',
      oneLine: true
    });
  });
  $('.hurrytimer-timepicker').each(function () {
    $(this).timepicker({
      timeFormat: 'hh:mm TT',
      controlType: 'select',
      oneLine: true
    });
  }); // ------------------------------------------------------------
  // Handle mode toggle.
  // ------------------------------------------------------------

  function handleMode(elementRef) {
    document.querySelectorAll('.mode-settings[data-for^="hurrytMode"]').forEach(function (e) {
      e.classList.add('hidden');
    });
    document.querySelectorAll(".mode-settings[data-for=\"".concat(elementRef.attr('id'), "\"]")).forEach(function (e) {
      e.classList.remove('hidden');
    });
  }

  var toggleRecurringUntil = function toggleRecurringUntil(value) {
    if (value == 3) {
      recurringUntilElement.classList.remove('hidden');
    } else {
      recurringUntilElement.classList.add('hidden');
    }
  };

  var recurringUntilElement = document.querySelector('tr[data-for="hurrytRecurringUntil"]');
  document.querySelectorAll('input[name="recurring_until"]').forEach(function (e) {
    e.addEventListener('change', function (e) {
      return toggleRecurringEndDate(e.target.value);
    });
  });
  document.querySelectorAll('input[name="recurring_until"]:checked').forEach(function (e) {
    return toggleRecurringUntil(e.value);
  }); // Handle mode.

  $('input[name=mode]').on('change', function () {
    handleMode($(this));
  });
  handleMode($('input[name=mode]:checked')); // ------------------------------------------------------------
  // Handle products type dropdown.
  // ------------------------------------------------------------

  $('#hurrytimer-wc-products-selection-type').on('change', function () {
    var $this = $(this);
    var $selectedOption = $this.find('option:selected');
    var $label = $('.hurrytimer-products-selection-type-label');
    var $autocompleteWrap = $label.closest('.form-field');

    if ($selectedOption.data('show-autocomplete')) {
      $label.text($selectedOption.text());
      $autocompleteWrap.removeClass('hidden');
    } else {
      $autocompleteWrap.addClass('hidden');
    }
  }).change(); // Handle tabs
  // ------------------------------------------------------------

  $('.hurrytimer-tabbar a').on('click', function (e) {
    e.preventDefault();
    var $tab = $(this);
    $('.hurrytimer-tabcontent').removeClass('active');
    $($tab.attr('href')).addClass('active');
    $tab.parent().siblings().removeClass('active');
    $tab.parent().addClass('active');

    if ($tab.attr('href').indexOf('appearance') >= 0 || $tab.attr('href').indexOf('styling') >= 0) {
      $('.hurryt-fullscreen').removeClass('hidden');
    } else {
      $('.hurryt-fullscreen').addClass('hidden');
    }
  }); // ------------------------------------------------------------
  // Search for products/Categories
  // ------------------------------------------------------------

  $('#hurrytimer-wc-products-selection').select2({
    placeholder: 'Search...',
    width: '500',
    minimumInputLength: 2,
    ajax: {
      url: hurrytimer_ajax_object.ajax_url,
      dataType: 'json',
      data: function data(params) {
        return {
          action: 'wcSearchProducts',
          search: params.term,
          exclude: $(this).val(),
          productsSelection: $('#hurrytimer-wc-products-selection-type').val(),
          type: 'public'
        };
      }
    }
  }); // ------------------------------------------------------------
  // Color picker
  // ------------------------------------------------------------

  $('.hurrytimer-color-input').each(function () {
    var self = $(this);
    self.iris({
      change: function change(event) {
        changeColor(self, event.target.value);
      },
      clear: function clear() {
        changeColor(self, 'transparent');
      },
      palettes: false,
      hide: true,
      border: true
    }).on('click focus', function (event) {
      event.stopPropagation();
      $('.iris-picker').hide();
      $(this).parent().find('.iris-picker').show();
    });
  });
  $('.hurrytimer-color-clear').on('click', function () {
    var colorInput = $(this).parent().find('.hurrytimer-color-input');
    colorInput.val('transparent');
    changeColor(colorInput);
  }); // ------------------------------------------------------------
  // CUSTOM CSS
  // ------------------------------------------------------------
  // ------------------------------------------------------------
  // Handle sub tabbar.
  // ------------------------------------------------------------


  $('.hurrytimer-subtabbar a').on('click', function (e) {
    e.preventDefault();
    var self = $(this);
    $('.hurrytimer-subtabcontent').each(function () {
      $(this).removeClass('active');
    });
    $(self.attr('href')).addClass('active');
    self.parent().siblings().removeClass('active');
    self.parent().addClass('active');
    if (cssEditor) cssEditor.refresh();
  }); // ------------------------------------------------------------
  // Accordion.
  // ------------------------------------------------------------

  $('.hurrytimer-accordion-heading').on('click', function () {
    var self = $(this);
    var containerElement = self.parent();

    if (containerElement.hasClass('active')) {
      containerElement.removeClass('active');
    } else {
      containerElement.addClass('active').siblings().removeClass('active');
    }
  }); // ------------------------------------------------------------
  // Enable/disable sticky bar.
  // ------------------------------------------------------------

  $('input[name=enable_sticky]').on('change', function () {
    if ($(this).is(':checked')) {
      campaignPreviewRef.addClass('hurrytimer-sticky');
      campaignInnerPreviewRef.wrap('<div class="hurrytimer-sticky-inner"></div>');

      if (campaignPreviewRef.hasClass('hurryt-preview-fullscreen')) {
        setCSS(campaignPreviewRef, 'position', 'fixed', false);
        setCSS(campaignPreviewRef, 'top', 0);
      }
    } else {
      campaignPreviewRef.removeClass('hurrytimer-sticky');
      campaignInnerPreviewRef.unwrap('.hurrytimer-sticky-inner');
    } // refresh dismiss button


    if ($('input[name=sticky_bar_dismissible]').is(':checked')) {
      campaignPreviewRef.find('.hurrytimer-sticky-close').show();
    } else {
      campaignPreviewRef.find('.hurrytimer-sticky-close').hide();
    }
  });
  $('input[name=sticky_bar_dismissible]').on('change', function () {
    if ($(this).is(':checked')) {
      campaignPreviewRef.find('.hurrytimer-sticky-close').show();
    } else {
      campaignPreviewRef.find('.hurrytimer-sticky-close').hide();
    }
  }); // ------------------------------------------------------------
  // Change block display.
  // ------------------------------------------------------------

  $('select[name=block_display]').on('change', function () {
    var value = $(this).val();
    var blockSize = $('input[name="block_size"]').val() + 'px';
    setCSS(timerDigitPreviewRef, 'display', value, false);
    setCSS(timerLabelPreviewRef, 'display', value, false);
    var blockSizeInput = $(this).closest('.hurrytimer-style-control-field').siblings('.hurrytimer-field-block-size');

    if (value === 'inline') {
      setCSS(timerBlockPreviewRef, 'width', 'auto', false);
      setCSS(timerBlockPreviewRef, 'height', 'auto', false);
      setCSS(timerBlockPreviewRef, 'display', 'inline-block');
      blockSizeInput.hide();
    } else {
      blockSizeInput.show();
      setCSS(timerBlockPreviewRef, 'width', blockSize, false);
      setCSS(timerBlockPreviewRef, 'height', blockSize, false);
      setCSS(timerBlockPreviewRef, 'display', 'flex');
    }
  }).change(); // ------------------------------------------------------------
  // Set digit size.
  // ------------------------------------------------------------

  $('input[name=digit_size]').on('input keyup paste change', function () {
    var fontSize = parseInt($(this).val()) + 'px';
    setCSS(timerDigitPreviewRef, 'font-size', fontSize, false);
    setCSS(timerSepPreviewRef, 'font-size', fontSize);
  }).change(); // ------------------------------------------------------------
  // Set CTA text size.
  // ------------------------------------------------------------

  $('input[name="call_to_action[text_size]"]').on('input keyup paste change', function () {
    var fontSize = parseInt($(this).val()) + 'px';
    setCSS(campaignCTA, 'font-size', fontSize);
  }).change(); // ------------------------------------------------------------
  // Set block spacing.
  // ------------------------------------------------------------

  $('input[name=block_spacing]').on('input keyup paste change', function () {
    var spacing = "".concat($(this).val(), "px");

    if ($('select[name=display]').val() === 'inline') {
      setCSS(timerBlockPreviewRef, 'margin-bottom', spacing, false);
      setCSS(timerBlockPreviewRef, 'margin-top', spacing);
    } else {
      setCSS(timerBlockPreviewRef, 'margin-left', spacing, false);
      setCSS(timerBlockPreviewRef, 'margin-right', spacing);
    }
  }).change(); // ------------------------------------------------------------
  // Set block padding.
  // ------------------------------------------------------------

  $('input[name=block_padding]').on('input keyup paste change', function () {
    var padding = parseInt($(this).val()) + 'px';
    setCSS(timerBlockPreviewRef, 'padding', padding);
  }).change(); // ------------------------------------------------------------
  // Sticky Bar Y padding.
  // ------------------------------------------------------------

  $('input[name=sticky_bar_padding]').on('input keyup paste change', function () {
    var padding = "".concat($(this).val(), "px");
    var stickyBarInner = campaignPreviewRef.find('.hurrytimer-sticky-inner');
    setCSS(stickyBarInner, 'padding-top', padding, false);
    setCSS(stickyBarInner, 'padding-bottom', padding);
  }).change();
  $('select[name=sticky_bar_position]').on('input keyup paste change', function () {
    if ($(this).val() === 'top') {
      removeCSSProperty(campaignPreviewRef, 'bottom');
      setCSS(campaignPreviewRef, 'top', 0);
    } else {//removeCSSProperty(campaignPreviewRef, 'top');
      // setCSS(campaignPreviewRef, 'bottom', 0);
    }
  }).change();
  $('input[name=headline_spacing]').on('input keyup paste change', function () {
    var spacing = "".concat($(this).val(), "px");

    if ($('select[name=campaign_display]').val() === 'inline') {
      if ($('select[name=headline_position]').val() === hurrytimer_ajax_object.headline_pos.above_timer) {
        setCSS(headlinePreviewRef, 'margin-left', spacing);
        setCSS(headlinePreviewRef, 'margin-top', 0);
      } else {
        setCSS(headlinePreviewRef, 'margin-right', spacing);
        setCSS(headlinePreviewRef, 'margin-bottom', 0);
      }
    } else {
      if ($('select[name=headline_position]').val() === hurrytimer_ajax_object.headline_pos.above_timer) {
        setCSS(headlinePreviewRef, 'margin-left', 0);
        setCSS(headlinePreviewRef, 'margin-top', spacing);
      } else {
        setCSS(headlinePreviewRef, 'margin-right', 0);
        setCSS(headlinePreviewRef, 'margin-bottom', spacing);
      }
    }
  }).change();
  $('input[name="call_to_action[spacing]"]').on('input keyup paste change', function () {
    var spacing = "".concat($(this).val(), "px");

    if ($('select[name=campaign_display]').val() === 'inline') {
      setCSS(campaignCTA, 'margin-right', spacing, false);
      setCSS(campaignCTA, 'margin-left', spacing);
    } else {
      setCSS(campaignCTA, 'margin-top', spacing, false);
      setCSS(campaignCTA, 'margin-bottom', spacing);
    }
  }).change(); // ------------------------------------------------------------
  // Set label size.
  // ------------------------------------------------------------

  $('input[name=label_size]').on('input keyup paste change', function () {
    setCSS(timerLabelPreviewRef, 'font-size', parseInt($(this).val()) + 'px');
  }).change(); // ------------------------------------------------------------
  // Set block border width.
  // ------------------------------------------------------------

  $('input[name=block_border_width]').on('input keyup paste change', function () {
    var borderSize = parseInt($(this).val());
    var borderColor = $('input[name=block_border_color]').val() || 'transparent';
    setCSS(timerBlockPreviewRef, 'border', borderColor + ' solid ' + borderSize + 'px');
  }).change(); // ------------------------------------------------------------
  // Set block border radius.
  // ------------------------------------------------------------

  $('input[name=block_border_radius]').on('input keyup paste change', function () {
    setCSS(timerBlockPreviewRef, 'border-radius', "".concat($(this).val(), "px"));
  }).change(); // ------------------------------------------------------------
  // Set block size.
  // ------------------------------------------------------------

  $('input[name=block_size]').on('input keyup paste change', function () {
    var value = parseInt($(this).val());
    var size = value + 'px';

    if (value === 0 || $('select[name=block_display]').val() === 'inline') {
      size = 'auto';
    }

    setCSS(timerBlockPreviewRef, 'width', size, false);
    setCSS(timerBlockPreviewRef, 'height', size);
  }).change(); // ------------------------------------------------------------
  // Bind post title input (headline) to preview.
  // ------------------------------------------------------------

  var _loaded = false;
  $('input[name=post_title]').on('input paste keyup change', function () {
    if ($(this).val().length === 0 && !_loaded) {
      $(this).val(headlinePreviewRef.text());
      _loaded = true;
      return;
    }

    headlinePreviewRef.html($(this).val());
  }).change(); // ------------------------------------------------------------
  // Change headline position.
  // ------------------------------------------------------------

  $('select[name=headline_position]').on('change', function () {
    if (parseInt($(this).val()) === hurrytimer_ajax_object.headline_pos.above_timer) {
      headlinePreviewRef.after(timerPreviewRef);
    } else {
      headlinePreviewRef.before(timerPreviewRef);
    }
  }).change(); // ------------------------------------------------------------
  // Set headline size
  // ------------------------------------------------------------

  $('input[name=headline_size]').on('input keyup paste change', function () {
    setCSS(headlinePreviewRef, 'font-size', parseInt($(this).val()) + 'px');
  }).change(); // ------------------------------------------------------------
  // Set label case.
  // ------------------------------------------------------------

  $('select[name=label_case]').on('change', function () {
    setCSS(timerLabelPreviewRef, 'text-transform', $(this).val());
  }).change(); // ------------------------------------------------------------
  // Set CTA text.
  // ------------------------------------------------------------

  $('input[name="call_to_action[text]"]').on('change keyup paste input', function () {
    campaignCTA.text($(this).val());
  }).change(); // ------------------------------------------------------------
  // Set CTA horizontal padding
  // ------------------------------------------------------------

  $('input[name="call_to_action[x_padding]"]').on('input keyup paste change', function () {
    var padding = "".concat($(this).val(), "px");
    setCSS(campaignCTA, 'padding-left', padding, false);
    setCSS(campaignCTA, 'padding-right', padding);
  }).change(); // ------------------------------------------------------------
  // Set CTA border radius
  // ------------------------------------------------------------

  $('input[name="call_to_action[border_radius]"]').on('input keyup paste change', function () {
    setCSS(campaignCTA, 'border-radius', "".concat($(this).val(), "px"));
  }).change(); // ------------------------------------------------------------
  // Set CTA vertical padding
  // ------------------------------------------------------------

  $('input[name="call_to_action[y_padding]"]').on('input keyup paste change', function () {
    var padding = parseInt($(this).val()) + 'px';
    setCSS(campaignCTA, 'padding-top', padding, false);
    setCSS(campaignCTA, 'padding-bottom', padding);
  }).change(); // ------------------------------------------------------------
  // Toggle block separator visibility.
  // ------------------------------------------------------------

  $('input[name=block_separator_visibility]').on('change', function () {
    var self = $(this);

    if (!self.is(':checked')) {
      timerSepPreviewRef.addClass('hidden');
      return;
    }

    timerBlockPreviewRef.each(function () {
      if ($(this).hasClass('hidden')) {
        $(this).next().addClass('hidden');
      } else {
        $(this).next().removeClass('hidden');
      }
    });
  }).change(); // ------------------------------------------------------------
  // Toggle "days" block visibility.
  // ------------------------------------------------------------

  $('#hurrytimer-days-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=days]'));
  }).change(); // ------------------------------------------------------------
  // Toggle "hours" block visibility
  // ------------------------------------------------------------

  $('#hurrytimer-hours-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=hours]'));
  }).change(); // ---------------------------------------------------------------
  // Toggle "minutes" block visibility
  // ---------------------------------------------------------------

  $('#hurrytimer-minutes-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=minutes]'));
  }).change(); // ---------------------------------------------------------------
  // Toggle "seconds" block visibility
  // ---------------------------------------------------------------

  $('#hurrytimer-seconds-visibility').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('[data-block=seconds]'));
  }).change(); // ---------------------------------------------------------------
  // Toggle "headline" block visibility
  // ---------------------------------------------------------------

  $('#hurrytimer-headline-visibility').on('change', function () {
    if ($(this).is(':checked')) {
      headlinePreviewRef.removeClass('hidden');
    } else {
      headlinePreviewRef.addClass('hidden');
    }
  }).change(); // ---------------------------------------------------------------
  // Toggle "labels" visibility.
  //----------------------------------------------------------------

  $('#hurrytimer-label-visibility').on('change', function () {
    if ($(this).is(':checked')) {
      timerLabelPreviewRef.removeClass('hidden');
    } else {
      timerLabelPreviewRef.addClass('hidden');
    }
  }).change(); // ---------------------------------------------------------------
  // Toggle CTA visibility.
  // ------------------------------------------------------------

  $('#hurrytimer-cta-enabled').on('change', function () {
    toggleBlockVisibility($(this), campaignInnerPreviewRef.find('.hurrytimer-button-wrap'));
  }).change(); // ---------------------------------------------------------------
  // Input slider.
  // ------------------------------------------------------------

  var blockSizeSliderElement;
  var blockSizeInputElement;
  $('.hurrytimer-input-slider').each(function () {
    var self = $(this);
    var boundInputElement = $('input[name="' + self.data('input-name') + '"]');
    var min = parseInt(boundInputElement.attr('min')) || 0;
    var max = parseInt(boundInputElement.attr('max')) || 100;

    if (boundInputElement.attr('name') === 'block_size') {
      min = parseInt($('input[name=digit_size]').val()) || min;
      blockSizeSliderElement = self;
      blockSizeInputElement = boundInputElement;
    }

    self.slider({
      slide: function slide(_, ui) {
        boundInputElement.val(ui.value);
        boundInputElement.trigger('input');

        if (boundInputElement.attr('name') === 'digit_size') {
          $('input[name=block_size]').attr('min', ui.value);
          blockSizeSliderElement.slider('option', 'min', ui.value);

          if (blockSizeInputElement.val() < ui.value) {
            blockSizeSliderElement.slider('option', 'value', ui.value);
            blockSizeInputElement.val(ui.value);
            blockSizeInputElement.trigger('input');
          }
        }
      },
      max: max,
      min: min,
      value: boundInputElement.val()
    });
  }); // ------------------------------------------------------------

  $('body').on('click', function () {
    $('.iris-picker').hide();
  }); // ------------------------------------------------------------
  // Add new action
  // ------------------------------------------------------------

  $('#hurrytimer-new-action').on('click', function () {
    //removeif(pro)
    if ($('.hurrytimer-action-block').length === 1) {
      $('#hurryt-upgrade-alert-actions-feat').modal({
        closeExisting: true,
        closeClass: 'hurryt-upgrade-alert-close',
        closeText: ''
      });
      return;
    } // endremoveif(pro)


    var action = $('.hurrytimer-action-block').last().clone(true, true);
    action.find('.hurrytimer-action-block-subfields').addClass('hidden');
    var fields = action.find(':input');

    for (var i = 0; i < fields.length; i++) {
      fields[i].name = fields[i].name.replace(/actions\[(\d+)\]\[(\w+)\]/, function (fm, i, name) {
        return 'actions[' + ++i + '][' + name + ']';
      });
    }

    $(this).parent().before(action);

    if ($('.hurrytimer-action-block').length === 1) {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').addClass('hidden');
    } else {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').removeClass('hidden');
    }
  }); // ------------------------------------------------------------
  // Handle action selection
  // ------------------------------------------------------------

  $('#hurrytimer-actions').on('change', '.hurrytimer-action-select', function () {
    handleActionChange($(this));
  });
  $('.hurrytimer-action-select').each(function () {
    handleActionChange($(this));
  });

  function handleActionChange(element) {
    var action = element.find('option:selected');

    if (+action.val() === 4 && +$('.hurrytimer-mode:checked').val() === 2) {
      element.next('.hurryt-compat-info').removeClass('hidden');
    } else {
      element.next('.hurryt-compat-info').addClass('hidden');
    }

    var block = element.closest('.hurrytimer-action-block');
    block.find('.hurrytimer-action-block-subfields').addClass('hidden');
    block.find('.' + action.data('subfields')).removeClass('hidden');
  } // ------------------------------------------------------------
  // Handle action deletion
  // ------------------------------------------------------------


  $('#hurrytimer-actions').on('click', '.hurrytimer-delete-action', function () {
    if ($('.hurrytimer-action-block').length === 1) return;
    $(this).closest('.hurrytimer-action-block').remove();

    if ($('.hurrytimer-action-block').length === 1) {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').addClass('hidden');
    } else {
      $('.hurrytimer-action-block').find('.hurrytimer-delete-action').removeClass('hidden');
    }
  }); // ------------------------------------------------------------
  // Set "days" label
  // ------------------------------------------------------------

  $('input[name="labels[days]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=days] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Set "hours" label
  // ------------------------------------------------------------

  $('input[name="labels[hours]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=hours] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Set "minutes" label
  // ------------------------------------------------------------

  $('input[name="labels[minutes]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=minutes] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Set "seconds" label
  // ------------------------------------------------------------

  $('input[name="labels[seconds]"]').on('input keyup paste', function () {
    campaignInnerPreviewRef.find('[data-block=seconds] .hurrytimer-timer-label').text($(this).val());
  }).trigger('input'); // ------------------------------------------------------------
  // Compaign display
  // ------------------------------------------------------------

  $('select[name=campaign_display]').on('change', function () {
    var blockMarginBottom = timerBlockPreviewRef.css('margin-bottom');

    if ($(this).val() === 'inline') {
      campaignInnerPreviewRef.addClass('hurrytimer-inline');
      setCSS(timerBlockPreviewRef, 'margin-bottom', '0');
    } else {
      campaignInnerPreviewRef.removeClass('hurrytimer-inline');
      setCSS(timerBlockPreviewRef, 'margin-bottom', blockMarginBottom);
    }

    $('input[name="call_to_action[spacing]"]').change();
    $('input[name="headline_spacing"]').change();
  }).change(); // ------------------------------------------------------------
  // Compaign alignment
  // ------------------------------------------------------------

  $('select[name=campaign_align]').on('change', function () {
    var value = $(this).val();
    setCSS(campaignInnerPreviewRef, 'text-align', value);

    if ($('select[name=campaign_display]').val() === 'inline') {
      return false;
    }

    switch (value) {
      case 'left':
        setCSS(timerPreviewRef, 'justify-content', 'flex-start');
        break;

      case 'right':
        setCSS(timerPreviewRef, 'justify-content', 'flex-end');
        break;

      case 'center':
        setCSS(timerPreviewRef, 'justify-content', 'center');
        break;
    }
  }).change();
  $('.hurryt-fullscreen').on('click', function (e) {
    e.preventDefault();

    if ($(this).hasClass('on')) {
      campaignPreviewRef.removeClass('hurryt-preview-fullscreen');
      $(this).removeClass('on');

      if (campaignPreviewRef.hasClass('hurrytimer-sticky')) {
        setCSS(campaignPreviewRef, 'position', 'relative');
      }
    } else {
      campaignPreviewRef.addClass('hurryt-preview-fullscreen');
      $(this).addClass('on');
    }
  });
  $('select[name="sticky_bar_pages[]"]').select2({
    placeholder: 'Search...'
  });
  $('select[name="sticky_bar_pages[]"]').on('change', function () {
    if ($(this).val() === null) {
      $('input[name="sticky_bar_pages[]"]').val([]);
    } else {
      $('input[name="sticky_bar_pages[]"]').val("[".concat($(this).val(), "]"));
    }
  });
  $('input[type="checkbox"][name="sticky_bar_show_on_all_pages"]').on('change', function () {
    if ($('input[type="hidden"][name="sticky_bar_show_on_all_pages"]').length === 0) {
      $(this).after('<input type="hidden" name="sticky_bar_show_on_all_pages" value="yes" />');
    }

    if ($(this).is(':checked')) {
      $('input[type="hidden"][name="sticky_bar_show_on_all_pages"]').val('yes');
      $('select[name="sticky_bar_pages[]"').attr('disabled', true);
    } else {
      $('input[type="hidden"][name="sticky_bar_show_on_all_pages"]').val('no');
      $('select[name="sticky_bar_pages[]"').attr('disabled', false);
    }
  }).change();
  $('.js-hurrytimer-restart-coundown'); //  Display tooltips

  $('#hurrytimer-settings').tooltip({
    tooltipClass: 'hurryt-tooltip',
    content: function content() {
      return $(this).prop('title');
    },
    position: {
      my: 'center bottom-20',
      at: 'center top',
      using: function using(position, feedback) {
        $(this).css(position);
        $('<div>').addClass('arrow').addClass(feedback.vertical).addClass(feedback.horizontal).appendTo(this);
      }
    }
  }); // Toggle display

  $('.hurryt-sticky-bar-display-on').on('change', function () {
    if ($(this).val() === 'specific_pages') {
      $('.hurryt_sticky_bar_pages').removeClass('hidden');
    } else {
      $('.hurryt_sticky_bar_pages').addClass('hidden');
    }
  });

  if ($('.hurryt-sticky-bar-display-on:checked').val() === 'specific_pages') {
    $('.hurryt_sticky_bar_pages').removeClass('hidden');
  } else {
    $('.hurryt_sticky_bar_pages').addClass('hidden');
  }
  /**
   * Reset evergreen countdown timers for all visitors
   */


  var resetAllButton = document.getElementById('hurrytResetAll');

  if (resetAllButton) {
    resetAllButton.addEventListener('click', function (e) {
      e.preventDefault();

      var _confirm = confirm('Are you sure?');

      if (_confirm) {
        window.location.href = resetAllButton.getAttribute('data-url');
      }
    });
  }

  var resetCurrentButton = document.getElementById('hurrytResetCurrent');

  if (resetCurrentButton) {
    resetCurrentButton.addEventListener('click', function (e) {
      e.preventDefault();
      Cookies.remove(resetCurrentButton.getAttribute('data-cookie'));
      window.location.href = resetCurrentButton.getAttribute('data-url');
    });
  }

  var resetAllEvergreenCampaignsButtons = document.querySelectorAll('.hurrytResetAllEvergreenCampaigns');

  if (resetAllEvergreenCampaignsButtons) {
    var _iteratorNormalCompletion = true;
    var _didIteratorError = false;
    var _iteratorError = undefined;

    try {
      var _loop = function _loop() {
        var button = _step.value;
        button.addEventListener('click', function (e) {
          e.preventDefault();

          if (confirm('Are you sure?')) {
            var cookies = Cookies.get();

            for (var name in cookies) {
              if (name.startsWith(button.getAttribute('data-cookie-prefix'))) {
                Cookies.remove(name);
              }
            }

            window.location.href = button.getAttribute('data-url');
          }
        });
      };

      for (var _iterator = resetAllEvergreenCampaignsButtons[Symbol.iterator](), _step; !(_iteratorNormalCompletion = (_step = _iterator.next()).done); _iteratorNormalCompletion = true) {
        _loop();
      }
    } catch (err) {
      _didIteratorError = true;
      _iteratorError = err;
    } finally {
      try {
        if (!_iteratorNormalCompletion && _iterator.return != null) {
          _iterator.return();
        }
      } finally {
        if (_didIteratorError) {
          throw _iteratorError;
        }
      }
    }
  }

  var recurringFrequencyElement = document.getElementById('hurrytRecurringFrequency');
  var maxDurationElement = document.getElementById('hurrytRecurringDurationMax');
  var recurringIntervalElement = document.getElementById('hurrytRecurringInterval');

  if (recurringFrequencyElement) {
    recurringFrequencyElement.onchange = function (e) {
      return toggleRecurringDuration(e.target.value);
    };

    toggleRecurringDuration(recurringFrequencyElement.value);
  }

  function toggleRecurringDuration(value) {
    console.log(value);
    var hoursInputElement = document.getElementById('hurrytRecurringHours');
    var daysInputElement = document.getElementById('hurrytRecurringDays');
    var minutesInputElement = document.getElementById('hurrytRecurringMinutes');

    switch (value) {
      case 'minutely':
        daysInputElement.value = 0;
        hoursInputElement.value = 0;
        hoursInputElement.parentNode.classList.add('hidden');
        daysInputElement.parentNode.classList.add('hidden');
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " minute(s)");
        break;

      case 'hourly':
        daysInputElement.value = 0;
        daysInputElement.parentNode.classList.add('hidden');
        hoursInputElement.parentNode.classList.remove('hidden');
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " hour(s)");
        break;

      case 'daily':
        daysInputElement.parentNode.classList.remove('hidden');
        hoursInputElement.parentNode.classList.remove('hidden');
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " day(s)");
        break;

      case 'weekly':
        daysInputElement.parentNode.classList.remove('hidden');
        hoursInputElement.parentNode.classList.remove('hidden');
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " week(s)");
        break;
    }
  }

  if (recurringIntervalElement) {
    recurringIntervalElement.addEventListener('change', validateRecurringDurationEntry);
    recurringIntervalElement.addEventListener('keyup', validateRecurringDurationEntry);
    recurringIntervalElement.addEventListener('paste', validateRecurringDurationEntry);
    validateRecurringDurationEntry();
  }

  function validateRecurringDurationEntry() {
    switch (recurringFrequencyElement.value) {
      case 'minutely':
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " minute(s)");
        break;

      case 'hourly':
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " hour(s)");
        break;

      case 'daily':
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " day(s)");
        break;

      case 'weekly':
        maxDurationElement.textContent = "".concat(recurringIntervalElement.value, " week(s)");
        break;
    }

    maxDurationElement.parentNode.classList.remove('hidden');
  }

  $('body').on('click', '.hurryt-add-wc-condition-group', function () {
    var _self = $(this);

    _self.prop('disabled', true);

    _self.next('.spinner').addClass('is-active');

    $.get(hurrytimer_ajax_object.ajax_url, {
      action: 'add_wc_condition_group',
      nonce: hurrytimer_ajax_object.ajax_nonce
    }, function (html) {
      _self.before(html);
    }).always(function () {
      _self.prop('disabled', false);

      _self.next('.spinner').removeClass('is-active');
    });
  });
  $('body').on('click', '.hurryt-add-wc-condition', function () {
    var _self = $(this);

    _self.prop('disabled', true);

    $.get(hurrytimer_ajax_object.ajax_url, {
      action: 'add_wc_condition',
      nonce: hurrytimer_ajax_object.ajax_nonce,
      group_id: $(this).closest('.hurryt-wc-condition-group').data('group-id')
    }, function (html) {
      _self.parent().after(html);
    }).always(function () {
      _self.prop('disabled', false);
    });
  });
  $('body').on('click', '.hurryt-delete-wc-condition', function () {
    console.log('deleting condition');

    var _self = $(this);

    if (_self.closest('.hurryt-wc-condition-group').find('.hurryt-wc-condition').length === 1) {
      _self.closest('.hurryt-wc-condition-group').remove();
    } else {
      $(this).parent().remove();
    }
  });
  $('body').on('change', '.hurryt-wc-condition-key', function () {
    var _self = $(this);

    var $value = _self.parent().find('.hurryt-wc-condition-value');

    var $operator = _self.parent().find('.hurryt-wc-condition-operator');

    $value.prop('disabled', true);
    $operator.prop('disabled', true);
    $.get(hurrytimer_ajax_object.ajax_url, {
      action: 'load_wc_condition',
      nonce: hurrytimer_ajax_object.ajax_nonce,
      condition_key: _self.val(),
      group_id: _self.closest('.hurryt-wc-condition-group').data('group-id')
    }, function (html) {
      _self.parent().replaceWith(html);
    }, 'html').always(function () {
      $value.prop('disabled', false);
      $operator.prop('disabled', false);
    });
  });
})(jQuery);
//# sourceMappingURL=admin.js.map
