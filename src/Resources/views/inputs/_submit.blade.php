@extends('unusual_form::layouts._input-template')

@section('input')

<div class="custom-submit">
    {{-- <input type="hidden" name="{{ $input_name }}" value="0"> --}}
    <button
        type="submit"
        class="custom-submit {{ $class ? $class :'' }}"
        name="{{ $input_name }}"
        id="{{ $input_name }}_checkbox"
        value="1"
        {{ $checked ?? '' }}
        {{$props ?? ''}}
        >
        {{ $text }}
    </button>
    <span class="help-block"></span>

</div>

@overwrite