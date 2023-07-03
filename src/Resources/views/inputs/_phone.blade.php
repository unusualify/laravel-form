@extends('unusual_form::layouts._input-template', ['arr' => $arr])



@php
    $file_id =  "fileID_" . mt_rand(100000,999999);
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
        id="{{ $file_id }}"
        placeholder="{{ $placeholder ?? ''}}"
        name="{{ $input_name }}"
        value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}"
        {{$props}}
        />
    <span class="help-block">
        {{ $help_label ?? '' }}
    </span>
    {{-- <p class="help-block"></p> --}}
    <script>
        $( document ).ready(function() {
            var input = $('#'+'{{ $file_id }}')[0];
            // window.intlTelInput(input);
            // window.intlTelInput(input, {
            //     utilsScript: window.intlUtils, // just for formatting/placeholders etc
            //     nationalMode: true,
            //     placeholderNumberType: 'MOBILE',
            //     initialCountry: "auto",
            //     nationalMode: true,
            //     formatOnDisplay: true,

            // });
            var input = '{{ $file_id }}';
            var test = new CustomIntTelInput(input,  ['tr']);
        });

    </script>
@endif

@overwrite



