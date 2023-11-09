class ValidateForm{

  constructor(form = null, step = null, messages){
    this.form = form;
    this.step = step;
    this.messages = messages;
    this.rules = {
      'isEmail' : function(email){
        let regex = /(?:[a-z0-9!#$%&'*+/=?^_`{|}~-]+(?:\.[a-z0-9!#$%&'*+/=?^_`{|}~-]+)*|"(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21\x23-\x5b\x5d-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])*")@(?:(?:[a-z0-9](?:[a-z0-9-]*[a-z0-9])?\.)+[a-z0-9](?:[a-z0-9-]*[a-z0-9])?|\[(?:(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?)\.){3}(?:25[0-5]|2[0-4][0-9]|[01]?[0-9][0-9]?|[a-z0-9-]*[a-z0-9]:(?:[\x01-\x08\x0b\x0c\x0e-\x1f\x21-\x5a\x53-\x7f]|\\[\x01-\x09\x0b\x0c\x0e-\x7f])+)\])/;
        return regex.test(email);
      },
      'isTextValid' : function(text){
        if (val == '') 
          return false;
      },
      'isCheckboxValid' : function(checkbox){
        if (!$(checkbox).is(':checked')) 
          return false;
      },
      'isPhoneValid' : function(intlInputObj){
        console.log(intlInputObj[0].getNumber())
        console.log(intlInputObj[0].isPossibleNumber())
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
  }
  validate(intlInputObj){
    let item = this.form;
    if(item == null)
      item = this.step;
    let isValidForm = true;

    $(item).find('input:not([type="submit"])[required], textarea[required]').each((i, el) => {
      if (el.hasAttribute('required')
      ) {
        let _valid = this.validateInput(el, intlInputObj);
        if (!_valid && isValidForm)
          isValidForm = false;
      }
    })

    return isValidForm;
  }
  validateInput(el, intlInputObj ,showMessage = true){
    let input = $(el);
    let val = input.val();
    let name = input.attr('name');

    let valid = true;
    switch (input.attr('type')) {
      case 'text':
        if (val == '') {
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'filled')
        }
        break;
      case 'email':
        if (!this.rules['isEmail'](val)) {
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'email')
        }
        break;
      case 'tel':
        let phoneCheck = this.rules['isPhoneValid'](intlInputObj);
        if (phoneCheck[0]) {
          // if (phoneCheck[1] == intlTelInputUtils.validationError.TOO_SHORT){
          //   console.log('test')
          // }

          // console.log('Phone is not valid')
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'phone')
        }
        break;
      case 'checkbox':
        if (!this.rules['isCheckboxValid'](el)) {
          valid = false;
          if (showMessage)
            this.showErrorMessage(name, 'accepted');
        }
        break;
      default:
        break;
    }

    return valid;
  }
  disableSubmit (form) {
    $(form).find('button[type=submit]').prop('disabled', true)
  }

  enableSubmit (form) {
    $(form).find('input[type=submit]').removeAttr('disabled')
  }
  showErrorMessage (name, messageKey = ""){
    const message = this.getErrorMessage(name, messageKey);
    if(message != '') {
    $(`.help-block[for="${name}"]`).html(message);
    }
    console.log(name)
    $(`.help-block[for="${name}"]`).show();
  }
  clearErrorMessages(form){
    form.find('.help-block').each((i, item) => {
      $(item).hide();
    })
  }
  clearErrorMessage(name){
    $(`.help-block[for="${name}"]`).hide();
  }
  setForm(form){
    this.form = form;
  }
  setStep(step){
    this.step = step;
  }
  getErrorMessage(name,messageKey){
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

}
