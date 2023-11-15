@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')
{{-- We should have check if it's floating labeled text field --}}
    @if(isset($floating) && $floating == true)

    @else
        @isset($label)
        <label for="{{ $input_name }}">{!! $label !!}</label>
        @endisset
        <div class="position-relative">
            <input
                class="form-control {{$class ?? ''}} {{ $confirm ? 'confirm-control' : '' }}"
                type="password"
                placeholder="{{ $placeholder ?? ''}}"
                name="{{ $input_name }}"
                id="password_id"
                maxlength="20"
                {{-- value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}" --}}
                value=""
                {{$props}}
                
            />
            <i class="ue-eye"></i>
        </div>

            @if($meter) 
            <div class="pass-meter">
                <div class="percentage"></div>
            </div>
            @endif
            <span class="help-block">
                {{ $help_label ?? '' }}
            </span>
        @if($confirm == true)
            <input
                class="form-control {{$class ?? ''}} {{ $confirm ? 'confirm-control' : '' }}"
                type="password"
                placeholder="{{ $placeholder ?? ''}}"
                name="{{ $input_name }}"
                id="password_confirm"
                maxlength="20"
                {{-- value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}" --}}
                value=""
                {{$props}}
            />
        @endif
        {{-- <p class="help-block"></p> --}}
    @endif
    <script>
        $(document).ready(function(){
            let pass_meter = new PassMeter("complexity_id","password_id",".pass-meter .percentage");
            let confirm ={{ $confirm ? 'true' : 'false' }};
            let message = 'Passwords do not match !';
            let className= '.confirm-control';
            if(confirm){
                $(className).on('keyup',function(){
                if($(className)[0].value != $(className)[1].value){
                    console.log('here123')
                    $($(className)[0]).closest('.col').children('.help-block').html(message);

                }
                else
                    $($(className)[0]).closest('.col').children('.help-block').html('');
                })
            }
            $('.ue-eye').on('click',function(){
                $(this).toggleClass('showPw');
                if($('.confirm-control').prop('type') == 'password'){
                    $('.confirm-control').prop('type','text')   
                }
                else{
                    $('.confirm-control').prop('type','password')   
                }
            });   
        });
    </script>
@overwrite