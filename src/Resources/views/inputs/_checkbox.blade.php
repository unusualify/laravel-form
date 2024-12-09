@extends('unusual_form::layouts._input-template')

@section('input')
@php
    $checked = (isset($model) ? $model->getFormInputValue($input_name) : '') == '1' ? 'checked' : '';
@endphp
<div class="custom-control custom-switch">
    {{-- <input type="hidden" name="{{ $input_name }}" value="0"> --}}
    <input
        type="checkbox"
        class="custom-control-input {{ $class ? $class :'' }}"
        name="{{ $input_name }}"
        id="{{ $input_name }}_checkbox"
        value="1"
        {{ $checked }}
        {{$props ?? ''}}
        >
    <label class="custom-control-label" for="{{ $input_name }}_checkbox"> <span>{!! __($label) !!}</span></label>
    <span class="help-block" for="{{ $input_name }}"></span>
</div>

@overwrite
