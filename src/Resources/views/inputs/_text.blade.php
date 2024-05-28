@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')
{{-- We should have check if it's floating labeled text field --}}
    @if(isset($floating) && $floating == true)

    @elseif(isset($suffix))
        @isset($label)
        <label>{!! __($label) !!}</label>
        @endisset
        @php
            $time = time();
            $suffixVar = '--suffix-'.$time;
        @endphp
        <div class="suffix suffix-{{ $time }}" style="{{ $suffixVar }}:'{{ $suffix }}';">
            <input
                class="form-control {{$class ?? ''}} "
                type="text"
                placeholder="{{ __($placeholder) ?? ''}}"
                name="{{ $input_name }}"
                value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}"
                
                {{$props}}
            />
        <span class="help-block" for="{{ $input_name }}">
            {{ $help_label ?? '' }}
        </span>
        </div>
        <style>
            .suffix-{{ $time }}::after{
                content: var({{ $suffixVar }});
            }
        </style>
    @else
        @isset($label)
        <label>{!! __($label) !!}</label>
        @endisset
        <input
            class="form-control {{$class ?? ''}}"
            type="text"
            placeholder="{{ __($placeholder) ?? ''}}"
            name="{{ $input_name }}"
            value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}"
            {{$props}}
            />
        <span class="help-block" for="{{ $input_name }}">
            {{ $help_label ?? '' }}
        </span>
        {{-- <p class="help-block"></p> --}}
    @endif
@overwrite