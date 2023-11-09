@extends('unusual_form::layouts._input-template')

@section('input')
@php
    $checked = (isset($model) ? $model->getFormInputValue($input_name) : '') == '1' ? 'checked' : '';
@endphp
<label class="switch {{ $class ? $class :'' }}">

  <input 
        type="checkbox"
        class="custom-switcher-input"
        name="{{ $input_name }}"
        id="{{ $input_name }}_checkbox"
        value="1"
        {{ $checked }}
        {{$props ?? ''}}>
  <span class="slider"></span>
  @foreach ($labels as $label)
      <span class="p-absolute {{ $loop->first ? 'left-label' : 'right-label'}}">{{ $label }}</span>
  @endforeach
</label>
@overwrite