{{-- @php
    
@endphp --}}
@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')


{{-- We should have check if it's floating labeled text field --}}
@if(isset($floating) && $floating == true)



@else
    @isset($label)
    <label>{!! __($label) !!}</label>
    @endisset
    <input
        class="form-control {{$class ?? ''}}"
        type="email"
        placeholder="{{ __($placeholder) ?? ''}}"
        name="{{ $input_name }}"
        value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}"
        {{$props}}
        />
    <span class="help-block" for="{{ $input_name }}">
        {{ $help_label ?? '' }}
    </span>
    {{-- <p class="help-block"></p> --}}
@endif

@overwrite
