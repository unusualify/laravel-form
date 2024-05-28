@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')
    <span class="help-block {{$class ?? '' }} "> {{ $label }}</span>
@overwrite