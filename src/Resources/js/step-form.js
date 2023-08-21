// import intlTelInput from "intl-tel-input";
// import intlUtils from "intl-tel-input/build/js/utils.js";
// window.intlTelInput = intlTelInput;
// window.intlUtils = intlUtils;
class StepForm{
  constructor(formSelector,stepSelector, nextBtn, prevBtn, skipBtn, completeBtn){
    this.formSelector = formSelector;
    this.stepSelector = stepSelector;
    this.currentStep = 0;
    this.nextBtn = nextBtn;
    this.prevBtn = prevBtn;
    this.skipBtn = skipBtn;
    this.completeBtn = completeBtn;
    this.phones = [];
    this.listeners = {
      'next.start': function (currentStep){
        return true;},
    }
    this.initialize();
  }
  initialize() {
    this.initializePhoneInputs()
    this.initializeStepper();
    this.initializeListeners();

  }
  initializeStepper(){
    $('.' + this.stepSelector);
  }
  addListener(listnerName, callback){
    this.listeners[listnerName] = callback;
  }
  getListener(listnerName){
    return this.listeners[listnerName];
  }
  initializeListeners(){
    var self = this;

    $('.' + this.nextBtn).on('click',function(e){
      e.preventDefault();
      self.next();
    });
    $('.' + this.prevBtn).on('click', function (e) {
      self.prev();
    });
    $('.' + this.skipBtn).on('click', function (e) {
      self.skip()
    });
    $('.' + this.completeBtn).on('click', function (e) {
      console.log('completed')
    });

  }
  getForm(){
    return $('.' + this.formSelector + ' .' +this.stepSelector + this.currentStep);
  }
  getFirstStep(){
    return $('.' + this.formSelector + '.' + this.stepSelector);
  }
  getAllSteps(){
    return $('.' + this.stepSelector)
  }
  getLastStep(){
    return this.getAllSteps().length-1;
  }
  getCurrentStep(){
    return $('.' + this.formSelector + ' ' + '.'
      + this.stepSelector
      + this.currentStep)
  }
  getCurrentStepSelector(){
    return '.' + this.formSelector + '.'
      + this.stepSelector
      + this.currentStep;
  }
  setCurrentStep(currentStep){
    this.currentStep = currentStep;
  }
  setNextStep(currentStep){
    if(this.getLastStep() == currentStep)
      return this.currentStep;
    else
      return this.currentStep = this.currentStep + 1;
  }
  setPrevStep(currentStep){
    if (currentStep == 0)
      return this.currentStep;
    else
      return this.currentStep = this.currentStep - 1;
  }
  skip(){
    $('.' + this.stepSelector + this.currentStep).toggleClass('active');
    this.setCurrentStep(this.setNextStep(this.currentStep));
    $('.' + this.stepSelector + this.currentStep).toggleClass('active')
    return;
  }
  next(){
    let flag = this.listeners["next.start"](this.getCurrentStep());
    if(flag){
      //Form Control for current step
      $('.' + this.stepSelector + this.currentStep).toggleClass('active');
      this.setCurrentStep(this.setNextStep(this.currentStep));
      $('.' + this.stepSelector + this.currentStep).toggleClass('active');

      this.listeners["next.end"];
    }

    return;
  }
  prev(){
    $('.' + this.stepSelector + this.currentStep).toggleClass('active');
    this.setCurrentStep(this.setPrevStep(this.currentStep));
    $('.' + this.stepSelector + this.currentStep).toggleClass('active')
    return;

  }
  complete(){
    //Form Controls
    //Ajax request
    //Form complete 
  }
  initializePhoneInputs(){
    var self = this;
    $('.' + this.formSelector).find('input[type="tel"]').each(function (index) {
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
  addPhoneInput(intlTelInput){
    this.phones.push(intlTelInput);
  }

}

// export default StepForm;