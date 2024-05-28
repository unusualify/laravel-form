@extends('unusual_form::layouts._input-template')

@section('input')
{{-- @dd($config->form) --}}
{{-- <div class="custom-submit"> --}}
    {{-- <input type="hidden" name="{{ $input_name }}" value="0"> --}}
    <button
        type="{{ isset($button_type) ? $button_type : '' }}"
        class="custom-submit {{ $class ? $class :'' }}"
        name="{{ $input_name }}"
        id="{{ $input_name }}_checkbox"
        value="1"
        {{ $checked ?? '' }}
        {{$props ?? ''}}
        >
        {{ __($text) }}
    </button>

{{-- </div> --}}

@overwrite