@extends('unusual_form::layouts._input-template', ['arr' => $arr])


@section('input')

<div class="radio-container {{ $class }}">

  @foreach ($buttons as $button)
    <div class="single-radio-container">
        <label for="{{ $button['id']}}" class="custom-radio-container">
          {{ __($button['label']) }}
          <input type="radio" name="{{ $input_name }}" id="{{ $button['id']}}" value="{{ $button['value'] }}" {{ $button['checked'] === true ? "checked=checked" : '' }}>
          <span class="custom-radio">

          </span>
        </label>
        <span class="help-block" for="{{ $input_name }}"></span>
    </div>
    
  @endforeach

</div>

@overwrite