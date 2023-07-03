
@extends('unusual_form::layouts._input-template')


@section('input')
<div class="{{ $class ? $class : '' }}">
  <a href="{{ $link ? $link : ''}}">
    {{ $text ? $text : '' }}
  </a>
</div>

@overwrite