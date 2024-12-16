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
    @if(isset($custom) && $custom)
        <div class="custom-select">
            <input type="text"
                {{ $multi ? 'multiple' : '' }}
                class="{{ $class ?? '' }}"
                name="{{ $input_name }}{{ $multi ? '[]' : '' }}"
                id="{{ $id }}"
                {{$props}}
            >
            <div class="selected">
                {{ isset($filler) ?  __($filler) : 'Choose One' }}
            </div>
            <div class="items">
                <ul>
                    @foreach($items as $key => $item)
                        <li value="{{ $key }}">
                            {{ __($item) }}
                        </li>
                    @endforeach
                </ul>
            </div>
            <span class="help-block" for="{{ $input_name }}"> {{ $help_label ?? '' }} </span>

        </div>
    @pushOnce('custom-last-script')
        <script defer>
            $(document).ready( function(e) {
                $('.custom-select .selected').on('click', function() {
                    $(this).parent().toggleClass('show');
                });
                $('.custom-select .items li').on('click', function() {
                    var value = $(this).attr('value');
                    $parent = $(this).parents('.custom-select');
                    $parent.removeClass('show');
                    $parent.find('input').val(value);
                    $parent.find('.selected').text($(this).text());
                });
                $('.custom-select .items').on('mouseleave', function() {
                    $(this).parent().removeClass('show');
                });
            });
        </script>
    @endPushOnce
    @else
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
    @endif
</div>




@overwrite

