@extends('unusual_form::layouts._input-template', ['arr' => $arr])


@section('input')


@php
    $select_id =  mt_rand(100000,999999) . "_selectID";
    $item_key = $item_key ?? 'name';
    $item_value = $item_value ?? 'id';

    $value = isset($model) ? $model->getFormInputValue($input_name) : ''
@endphp

<label>{!! __($label) !!}</label>
<div class="input-group">
    @isset($prepend_label)
        <div class="input-group-prepend">
            <label class="input-group-text" for="{{ $select_id }}">
                {{ __($prepend_label) }}
            </label>
        </div>
    @endisset
    <select
        {{ $multi ? 'multiple' : '' }}
        class="custom-select {{ $class ?? '' }}"
        name="{{ $input_name }}{{ $multi ? '[]' : '' }}"
        id="{{ $id }}"
        {{$props}}
        type="select"
        >
        @if(!$multi)
        <option value="">{{ isset($filler) ?  $filler : 'Choose One' }}</option>
        @endif
        @foreach($items as $key => $item)
        
            <option
                value="{{ $key }}"
                {{-- @if($item->{$item_value} == $value) selected @endif--}}
                > 
                {{-- @dd($item) --}}
                {{ __($item) }}
            </option>
        @endforeach
    </select>
    <span class="help-block" for="{{ $input_name }}"> {{ $help_label ?? '' }} </span>
    @if($multi)
        <script>
            $(function () {
                var selector = '#' + "{{ $id }}" ;
                $(selector).filterMultiSelect(
                    {
                        'selectionLimit' : {{ $selectionLimit }},
                        'placeholderText' : 'Choose at least one'
                    }
                );
                $(selector).on('optionselected', function(e) {
                    //Code here for multiselect listener
                });
            });
        </script>
    @endif
</div>




@overwrite

