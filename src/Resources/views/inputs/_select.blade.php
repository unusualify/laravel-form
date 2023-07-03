@extends('unusual_form::layouts._input-template', ['arr' => $arr])


@section('input')


@php
    $select_id =  mt_rand(100000,999999) . "_selectID";
    $item_key = $item_key ?? 'name';
    $item_value = $item_value ?? 'id';

    $value = isset($model) ? $model->getFormInputValue($input_name) : ''
@endphp

<label>{{$label}}</label>
<div class="input-group mb-3 {{ $class ?? '' }}">
    @isset($prepend_label)
        <div class="input-group-prepend">
            <label class="input-group-text" for="{{ $select_id }}">
                {{ $prepend_label }}
            </label>
        </div>
    @endisset
    <select
        class="custom-select {{ $select_class ?? '' }}"
        name="{{ $input_name }}"
        id="{{ $select_id }}"
        {{$props}}
        >
        <option value="">Choose One</option>
        @foreach($items as $key => $item)
            <option
                value="{{ $item->{$item_value} }}"
                @if($item->{$item_value} == $value) selected @endif
                >
                {{ $item->{$item_key} }}
            </option>
        @endforeach
    </select>
    <span class="help-block"> {{ $help_label ?? '' }} </span>
</div>




@overwrite
