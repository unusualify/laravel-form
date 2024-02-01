class ValidateForm {

  constructor(form = null, step = null, messages, countryCode) {
    this.form = form;
    this.step = step;
    this.messages = messages;
    this.phones = [];
    this.countryCode = countryCode;
    this.rules = {
      'isEmail': function (email) {
        let regex = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
        return regex.test(email);
      },
      'isSelectValid': function (option) {
        if (option == '')
          return false;
        else
          return true;
      },
      'isTextValid': function (text) {
        if (text == '')
          return false;
      },
      'isCheckboxValid': function (checkbox) {
        return $(checkbox).is(':checked')

      },
      'isPhoneValid': function (intlInputObj) {
        if (intlInputObj[0].isPossibleNumber()) {
          // Valid
          return true;
        } else {
          // Invalid
          return [false, intlInputObj[0].getValidationError()];
          // errorMsg.innerHTML = errorMap[errorCode];
          // errorMsg.classList.remove("hide");
        }
      }
    }
    this.initializePhoneInputs()

  }
  initializePhoneInputs() {
    var self = this;
    $(self.form).find('input[type="tel"]').each(function (index) {
      let input = $(this)[0];
      let iti = window.intlTelInput(input, {
        utilsScript: 'https://cdn.jsdelivr.net/npm/intl-tel-input@18.2.1/build/js/utils.js', // just for formatting/placeholders etc
        nationalMode: true,
        placeholderNumberType: 'MOBILE',
        initialCountry: "auto",
        formatOnDisplay: true,
        // separateDialCode: true,
        geoIpLookup: function (callback) {
          fetch("https://ipapi.co/json")
            .then(function (res) { return res.json(); })
            .then(function (data) { callback(data.country_code); })
            .catch(function () { callback("us"); });
        }
      });
      self.addPhoneInput(iti);
    })
  }
  validate(intlInputObj = null) {
    let item = this.form;
    if (item == null)
      item = this.step;
    let isValidForm = true;

    $(item).find('input:not([type="submit"])[required], textarea[required], select[required]').each((i, el) => {
      if (el.hasAttribute('required')
      ) {
        let _valid = this.validateInput(el, intlInputObj);
        if (!_valid && isValidForm)
          isValidForm = false;
      }
    })

    return isValidForm;
  }
  validateInput(el, intlInputObj, showMessage = true) {
    let input = $(el);
    let val = input.val();
    let name = input.attr('name');

    let valid = true;
    switch (input.attr('type')) {
      case 'text':
        if (val == '') {
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'filled', el)
        }
        break;
      case 'email':
        if (!this.rules['isEmail'](val)) {
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'filled', el)
        }
        break;
      case 'tel':
        let phoneCheck = this.rules['isPhoneValid'](intlInputObj);
        if (Array.isArray(phoneCheck)) {
          if (!phoneCheck[0]) {
            valid = false;
            if (showMessage)
              this.showErrorMessage(name, 'phone', el)
          }
        } else if (phoneCheck) {
          if (this.countryCode) {
            let countryCodeInput = $("<input>")
              .attr("type", "hidden")
              .attr("name", "country_name").val(intlInputObj[0].getSelectedCountryData().name);
            $(this.form).append(countryCodeInput);
            valid = true;
            // intlInputObj[0].getSelectedCountryData().name
          }
        }

        break;
      case 'checkbox':
        if (!this.rules['isCheckboxValid'](el)) {
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'accepted', el);
        }
        break;
      case 'select':
        if (!this.rules['isSelectValid']($(el).val())) {
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'accepted', el);
        }
        break;
      default:
        break;
    }

    return valid;
  }
  disableSubmit(form) {
    $(form).find('button[type=submit]').prop('disabled', true)
  }

  enableSubmit(form) {
    $(form).find('input[type=submit]').removeAttr('disabled')
  }
  showErrorMessage(name, messageKey = "", el) {

    const message = this.getErrorMessage(name, messageKey);
    if (message != '') {
      $(this.form).find(`.help-block[for="${name}"]`).html(message);
    }
    $(`.help-block[for="${name}"]`).show();
  }
  clearErrorMessages(form) {
    form.find('.help-block').each((i, item) => {
      $(item).hide();
    })
  }
  clearErrorMessage(name) {
    $(`.help-block[for="${name}"]`).hide();
  }
  setForm(form) {
    this.form = form;
  }
  setStep(step) {
    this.step = step;
  }
  getErrorMessage(name, messageKey) {

    let message = this.messages[messageKey];
    if (messageKey == 'phone') {
      message = this.messages['custom'][messageKey]
    }
    if (this.messages['attributes'][name] != null) {
      name = this.messages['attributes'][name];
    }
    message = message.replace(':attribute', name)

    return message;
  }
  addPhoneInput(intlTelInput) {
    this.phones.push(intlTelInput);
  }

}

