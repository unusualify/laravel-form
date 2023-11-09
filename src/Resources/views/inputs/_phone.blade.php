@extends('unusual_form::layouts._input-template', ['arr' => $arr])



@php
    $input_id =  "inputID_" . mt_rand(100000,999999);
    if(isset($model))
        $value = $model->getFormInputValue( $input_name);
@endphp


@section('input')


{{-- We should have check if it's floating labeled text field --}}
@if(isset($floating) && $floating == true)



@else
    @isset($label)
    <label>{{$label}}</label>
    @endisset
    <input
        class="form-control {{$class ?? ''}}"
        type="tel"
        id="{{ $input_id }}"
        placeholder="{{ $placeholder ?? ''}}"
        name="{{ $input_name }}"
        value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}"
        {{$props}}
        />
    <span class="help-block" for="{{ $input_name }}">
        {{ $help_label ?? '' }}
    </span>
    {{-- <p class="help-block"></p> --}}
    {{-- <script>
        $( document ).ready(function() {
            var input = $('#'+'{{ $input_id }}')[0];

            var input = '{{ $input_id }}';
            window[input] = new CustomIntTelInput(input,  {
                utilsScript: window.intlUtils, // just for formatting/placeholders etc
                nationalMode: true,
                placeholderNumberType: 'MOBILE',
                initialCountry: "auto",
                nationalMode: true,
                formatOnDisplay: true,

            });
        });

    </script> --}}
@endif

@overwrite



