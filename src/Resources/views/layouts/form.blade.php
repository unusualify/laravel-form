@extends('dashboard.base')

@push('postcss')
    <style>
        .card > .card-header {
            background-color: lightgrey;
        }
    </style>
@endpush
@push('postscript')
    <script src="{{ asset('assets/extra-libs/jqbootstrapvalidation/validation.js') }}"></script>
    <script>
        var InitialFormState;
        var FormValidationObject = {};
        var $Form;

        function validationInit(object = {}, $form = null){
            let selector = "input[name],select[name],textarea[name]";
            if($form){
                $form.find(selector)
                    .not("[type=hidden],[type=submit]")
                    .jqBootstrapValidation(object)
            }else{
                $(selector)
                    .not("[type=hidden],[type=submit]")
                    .jqBootstrapValidation(object);
            }
        }
        function validationReset( $form = null){
            let selector = "input[name],select[name],textarea[name]";
            if($form){
                $form.find(selector)
                    .jqBootstrapValidation('destroy')
            }else{
                $(selector)
                    .jqBootstrapValidation('destroy')
            }
        }
        function formReloadControl(){
            InitialFormState = $('form').serialize();

            $(window).bind('beforeunload', function(e) {
                var form_state = $('form').serialize();
                if(InitialFormState != form_state){
                    var message = "You have unsaved changes on this page. Do you want to leave this page and discard your changes or stay on this page?";
                    e.returnValue = message; // Cross-browser compatibility (src: MDN)
                    return message;
                }
            });
        }
    </script>
@endpush

@push('lastscript')
    <script>
        $(function () {
            validationInit(FormValidationObject, $Form);
            setTimeout(() => {

                // formReloadControl()
            }, 5000);
        });

    </script>
@endpush
