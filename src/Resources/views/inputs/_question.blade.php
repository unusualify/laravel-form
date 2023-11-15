@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')

<div class="question container">
  <label class="" for="{{ $input_name }}"> {!! $label !!}</label>
  <input type="text" class="" placeholder="{{ $placeholder }}" name="{{ $input_name }}">
  <span class="help-block" for="{{ $input_name }}"></span>
</div>

<script>
  $(document).ready(function(){
    $('.question label').on('click',function(e){
      $(this).parent().toggleClass('active');
      $(this).parent().children('input')[0].focus()
    })
  })
</script>
@overwrite