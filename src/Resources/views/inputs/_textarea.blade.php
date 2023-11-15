@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')
@isset($label)
<label>{!! $label !!}</label>

@endisset
<textarea
    type="text"
    name="{{ $input_name }}"
    class="form-control {{$class??''}}"
    placeholder="{{ $placeholder ?? ''}}"
    id=""
    rows="2"
    {{$props}}
    >{{ isset($model) ? getModelInput($model, $input_name) : ''}}
</textarea>
<span class="help-block" for="{{ $input_name }}"> {{ $help_label ?? '' }} </span>
{{-- <p class="help-block"></p> --}}

@overwrite
