@extends('layouts._template')

@section('input')
@php
    $checked = (isset($model) ? $model->getFormInputValue($input_name) : '') == '1' ? 'checked' : '';
@endphp

<label></label>
<div class="custom-control custom-switch">
    <input type="hidden" name="{{ $input_name }}" value="0">
    <input
        type="checkbox"
        class="custom-control-input"
        name="{{ $input_name }}"
        id="{{ $input_name }}_checkbox"
        value="1"
        {{ $checked }}
        {{$props ?? ''}}
        >
    <span class="help-block"></span>
    <label class="custom-control-label" for="{{ $input_name }}_checkbox"> {{ $label }}</label>
</div>

@endsection
