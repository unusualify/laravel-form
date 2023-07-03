@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')


@php
    $date_input_id =  "dateID_" . mt_rand(100000,999999);

@endphp
<label>{{$label}}</label>
<input
    type="date"
    class="form-control {{$class ?? ''}}"
    {{-- pattern="\d{4}-\d{2}-\d{2}" --}}
    id="{{ $date_input_id }}"
    name="{{ $input_name }}"
    value="{{ isset($model) ? $model->getFormInputValue($input_name) : ''}}"
    placeholder="{{ $placeholder ?? ''}}"
    {{$props}}
    >
<span class="help-block"> {{ $help_label ?? '' }} </span>
{{-- <p class="help-block"></p> --}}

@once
    @push('postcss')

    @endpush
    @push('postscript')

        <script>

            function createDateInputObserver(selector){

                // The node to be monitored
                var parent = $( selector ).closest('form')[0];

                // Create an observer instance
                var filefond_observer = new MutationObserver(function( mutations ) {
                    let elements = mutations.filter( function(mutation){
                        return $(mutation.target).filter('input[type=date]').length > 0;
                    });

                    if(elements.length){
                        elements.forEach(element => {
                            // console.log($(element.target)[0])
                            // createFileFond( $(element.target)[0], '' )

                        });
                    }
                    // mutations.forEach(function( mutation ) {
                    //     var newNodes = mutation.addedNodes; // DOM NodeList
                    //     if( newNodes !== null ) { // If there are new nodes added
                    //         var $nodes = $( newNodes ); // jQuery set
                    //         $nodes.each(function() {
                    //             var $node = $( this );
                    //             if( $node.hasClass( "message" ) ) {
                    //                 // do something
                    //             }
                    //         });
                    //     }
                    // });
                });
                // Configuration of the observer:
                var config = {
                    attributes: true,
                    childList: true,
                    characterData: true,
                    subtree:true
                };

                // Pass in the target node, as well as the observer options
                filefond_observer.observe(parent, config)
            }

            $(document).ready(function(){
                createDateInputObserver('#'+ '{{ $date_input_id }}')
            })

        </script>
    @endpush
@endonce


@endsection
