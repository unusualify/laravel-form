@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')
@once
    @php
        $tag_route = route('tags.index');
    @endphp
@endonce
@php
    $tag_id =  mt_rand(100000,999999) . "_tagID";
    $max_tag = $max_tag ?? 3;
    $max_tag_length = $max_tag_length ?? 8;

    $tags = $model->getFormInputValue($input_name);

    $all_tags = $model
        ->allTags()
        ->get()
        ->map(function($item){
            return $item->name;
        });
        // ->implode(',');

@endphp

<label>{!! __($label) !!}</label>
<div class="input-group mb-3 {{ $class ?? '' }}">
    <input class="{{$class ?? ''}}"
        type="text"
        placeholder="{{ __($placeholder) ?? '' }}"
        name="{{ $input_name }}"
        id="{{ $tag_id }}"
        data-role="tagsinput"
        {{$props}}
        />
    {{-- <p class="help-block"></p> --}}
    {{-- <span class="help-block"> {{ $help_label ?? '' }} </span> --}}
</div>

@once
    @push('postcss')
        <!-- Bootstrap CSS -->
        {{-- <link href="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet"> --}}
        <link href="{{ asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.css') }}" rel="stylesheet">
        {{-- <link href="{{ asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput-typeahead.css') }}" rel="stylesheet"> --}}
    @endpush
    @push('postscript')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-3-typeahead/4.0.2/bootstrap3-typeahead.min.js" integrity="sha512-HWlJyU4ut5HkEj0QsK/IxBCY55n5ZpskyjVlAoV9Z7XQwwkqXoYdCIC93/htL3Gu5H3R4an/S0h2NXfbZk3g7w==" crossorigin="anonymous" referrerpolicy="no-referrer"></script>
        {{-- <script src="{{ asset('assets/plugins/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}" ></script> --}}
        <script src="{{ asset('assets/libs/bootstrap-tagsinput/dist/bootstrap-tagsinput.min.js') }}" ></script>
        <script>
            function slugify(str) {
                str = str.replace(/^\s+|\s+$/g, ''); // trim
                str = str.toLowerCase();

                // remove accents, swap ñ for n, etc
                var from = "àáäâèéëêìíïîòóöôùúüûñç·/_,:;";
                var to   = "aaaaeeeeiiiioooouuuunc------";
                for (var i=0, l=from.length ; i<l ; i++) {
                    str = str.replace(new RegExp(from.charAt(i), 'g'), to.charAt(i));
                }

                str = str.replace(/[^a-z0-9 -]/g, '') // remove invalid chars
                    .replace(/\s+/g, '-') // collapse whitespace and replace by -
                    .replace(/-+/g, '-'); // collapse dashes

                return str;
            }

            var substringMatcher = function(strs, q) {
                var matches;
                matches = [];
                substrRegex = new RegExp( slugify(q), 'i');
                $.each(strs, function(i, str) {
                    if (substrRegex.test( slugify(str) )) {
                        matches.push(str);
                    }
                });
                return matches;
            };
        </script>
    @endpush
@endonce
@push('componentscript')
    <script>
        // _id = @json($tag_id);
        // console.log(_id)
        let tags = @json($all_tags);
        $('#' + @json($tag_id)).tagsinput({
            maxTags: @json($max_tag),
            maxChars: @json($max_tag_length),
            trimValue: true,

            tagClass: function(item) {
                // console.log(item)
                let cl = "badge badge-primary ";
                return cl + (item.length > 10 ? 'big' : 'small');
            },

            typeahead: {
                // source: @json( $all_tags ),
                source: function(query) {
                    let tags = @json( $all_tags );
                    return substringMatcher(tags, query);
                }
            },
            onTagExists: function(item, $tag) {
                $tag.hide().fadeIn();
            },
            freeInput: true

        });
        $('#' + @json($tag_id)).tagsinput('add', '{{ $tags }}');
  </script>
@endpush

@endsection

