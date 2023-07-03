@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')


@php
    $repeater_id =  mt_rand(100000,999999) . "_repeaterID";

    // $default_values = collect($inputs)->reduce();
    // if($input_name == 'details[online_coverage][prominents]' && isset($model)){
    //     dd($input_name, $model->getFormInputValue($input_name) );
    // }
    $default_values = [];
    if(isset($model)){
        $default_values = $model->getFormInputValue($input_name) ?? [];
    }
    // dd($default_values);
@endphp

<div class="card">
    <div class="card-header">
        <strong>{{ $label }}</strong> <small>Form</small>
    </div>
    <div class="card-body" id="{{ $repeater_id }}">
        <div class="container">
            <div class="d-flex justify-content-end">
                <input data-repeater-create type="button" class="btn btn-primary float-right" value="{{ 'Add ' . $name }}"/>
            </div>
        </div>
        <input type="hidden" name="{{ $input_name }}" value="[]">

        <div data-repeater-list="{{ $input_name }}">
            <details class="repeater-details" data-repeater-item open>

                <summary>
                    <div class="float-right w-100 px-4">
                        <label class="repeater-title text-primary font-weight-bold">
                            {{ $key ?? 'Default' }}
                        </label>
                        <button data-repeater-delete type="button" class="btn btn-danger float-right"> Delete </button>
                    </div>
                </summary>

                <div class="form-group row m-1">
                    @foreach($inputs as $key => $object)
                        <div class="form-group {{ (isset($object['col']) ? $object['col'] : 'col-12 col-md-6 col-lg-4 col-xl-3') }}">
                            @include('components.form.'.$object['type'], array_merge($object, [
                                    'input_name' => $input_name . "[]" . "[{$object['input_name']}]",
                                    'model' => $model
                                ])
                            )
                        </div>
                    @endforeach
                </div>
            </details>
        </div>
    </div>


</div>

@once
    @push('postscript')
        {{-- @production
            <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.min.js') }}" ></script>
        @endproduction
        @env('local')
        @endenv --}}
        <script src="{{ asset('assets/libs/jquery.repeater/jquery.repeater.js') }}" ></script>

        <script>
            function createRepeater(selector, defaultValues = null){
                let repeater = $(selector ).repeater({

                    // constructorOverride: {
                    //     file: createNewInputFile,
                    // },
                    // (Optional)
                    // start with an empty list of repeaters. Set your first (and only)
                    // "data-repeater-item" with style="display:none;" and pass the
                    // following configuration flag
                    initEmpty: true,
                    // (Optional)
                    // You can use this if you need to manually re-index the list
                    // for example if you are using a drag and drop library to reorder
                    // list items.
                    // ready: function (setIndexes) {
                    //     $dragAndDrop.on('drop', setIndexes);
                    // },
                    // (Optional)
                    // Removes the delete button from the first list item,
                    // defaults to false.
                    isFirstItemUndeletable: false,
                    // (Optional)
                    // "defaultValues" sets the values of added items.  The keys of
                    // defaultValues refer to the value of the input's name attribute.
                    // If a default value is not specified for an input, then it will
                    // have its value cleared.
                    // defaultValues: {
                    //     'text-input': 'foo'
                    // },
                    // (Optional)
                    // "show" is called just after an item is added.  The item is hidden
                    // at this point.  If a show callback is not given the item will
                    // have $(this).show() called on it.
                    show: function () {
                        validationInit();

                        if($(this).find('.filepond') < 1){
                            $(this).attr('open', false)
                        }

                        var first_input = $(this).find('input,select').first();
                        var title;
                        if(first_input.is('select')){
                            var option = first_input.find(":selected");
                            if(!!option.val()){
                                title = option.text().trim();
                            }

                            first_input.on('change', function() {
                                var option = $(this).find(':selected');
                                if(!!option.val()){
                                    $(this)
                                        .closest('.repeater-details')
                                        .find('.repeater-title')
                                        .html(option.text().trim())
                                }
                            });

                        }else if(first_input.is('input[type=text]')) {
                            title = first_input.val().trim();

                            first_input.on('input',function(e){
                                $(this)
                                    .closest('.repeater-details')
                                    .find('.repeater-title')
                                    .html( $(this).val().trim() )
                            });
                        }
                        first_input.closest('.repeater-details').find('.repeater-title').html(title)

                        $(this).slideDown();
                    },
                    // (Optional)
                    // "hide" is called when a user clicks on a data-repeater-delete
                    // element.  The item is still visible.  "hide" is passed a function
                    // as its first argument which will properly remove the item.
                    // "hide" allows for a confirmation step, to send a delete request
                    // to the server, etc.  If a hide callback is not given the item
                    // will be deleted.
                    hide: function (deleteElement) {
                        if(confirm('Are you sure you want to delete this element?')) {
                            $(this).slideUp(deleteElement);
                        }
                    },

                });
                // console.log(repeater)
                if(defaultValues.length > 0){
                    repeater.setList(defaultValues)
                }
            }

        </script>
    @endpush
@endonce
@push('componentscript')
    <script>
        $(document).ready(function () {
            createRepeater('#' + '{{ $repeater_id }}', @json($default_values));
        });
  </script>
@endpush


@endsection
