@extends('unusual_form::layouts._input-template', ['arr' => $arr])

@section('input')


@once
    @php
        $preview_route = route('unusual_form.filepond.preview', ['id' => ':id'], false);
        $process_route = route('unusual_form.filepond.upload', [], false);
        $revert_route = route('unusual_form.filepond.delete', [], false);
    @endphp
@endonce
@php
    $file_id =  "fileID_" . mt_rand(100000,999999);
    $value = $model->getFormInputValue( $input_name);
    $generate = $generate ?? true;

    $table_name = $model->getTable();

@endphp

<label>{!! $label !!}</label>
<div
    class="mb-3 {{ $class ?? ''}}"
    >
    <div class="">
        <input
            type="file"
            name="{{ $input_name }}"
            value="{{ $generate ? $value : '' }}"
            data-type="local"
            class="filepond"
            @if($generate)
                id="{{$file_id}}"
            @endif
            data-max-file-size="2MB"
            {{$props ?? ''}}

            {{-- aria-describedby="{{$input_name}}Addon"  --}}
            >
        <span class="help-block" for="{{ $input_name }}"></span>

        {{-- <label class="custom-file-label" for="{{$input_name}}ID">
            {{ $choose_label ?? 'Choose file' }}
        </label> --}}
    </div>
</div>

@once
    @push('postcss')
        <!-- Filepond CSS -->
        <link href="https://unpkg.com/filepond@^4/dist/filepond.css" rel="stylesheet" />
        <link
            href="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.css"
            rel="stylesheet"
        />
        <link
            href="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.css"
            rel="stylesheet"
        />
        <link
            href="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.css"
            rel="stylesheet"
        />

    @endpush
    @push('postscript')
        <script src="https://unpkg.com/filepond-plugin-image-edit/dist/filepond-plugin-image-edit.js"></script>
        <script src="https://unpkg.com/filepond-plugin-image-preview/dist/filepond-plugin-image-preview.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-metadata/dist/filepond-plugin-file-metadata.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-poster/dist/filepond-plugin-file-poster.js"></script>
        <script src="https://unpkg.com/filepond-plugin-file-validate-type/dist/filepond-plugin-file-validate-type.js"></script>

        {{-- <script src="https://unpkg.com/filepond/dist/filepond.min.js"></script> --}}
        <script src="https://unpkg.com/filepond@^4/dist/filepond.js"></script>

        <script src="https://unpkg.com/jquery-filepond/filepond.jquery.js"></script>

        <script>


            FilePond.registerPlugin(
                FilePondPluginImagePreview,
                FilePondPluginFileMetadata,
                FilePondPluginFilePoster,
                FilePondPluginFileValidateType,
                // FilePondPluginImageExifOrientation,
                // FilePondPluginFileValidateSize,
                // FilePondPluginImageEdit
            );
            // let url = @json(url('/'));
            // console.log(url)
            FilePond.setOptions({
                // allowDrop: false,
                // allowReplace: false,
                // instantUpload: false,

                server: {
                    // url: '{{ url("/") }}',
                    // process: './process.php',
                    // revert: './revert.php',
                    // restore: './restore.php?id=',
                    // fetch: './fetch.php?data=',
                    // fetch: 'assets/images/',
                    // load: 'assets/images/',

                    process: {
                        url: '{{ $process_route }}',
                        ondata: (formData) => {
                            formData.append('model', '{{ $table_name }}');
                            return formData;
                        }

                    },
                    revert: '{{ $revert_route }}' + '?model=' +  '{{ $table_name }}',
                    headers: {
                        'X-CSRF-TOKEN': '{{ csrf_token() }}'
                    },
                    // process: (fieldName, file, metadata, load, error, progress, abort, transfer, options) => {
                    //     const formData = new FormData()
                    //     formData.append(fieldName, file, file.name)
                    //     if(metadata) formData.append('meta', JSON.stringify(metadata))

                    //     const request = new XMLHttpRequest()
                    //     request.open('POST', '{{ $process_route }}')

                    //     request.upload.onprogress = (e) => {
                    //         progress(e.lengthComputable, e.loaded, e.total)
                    //     }
                    //     formData['model'] = '{{ $model }}'

                    //     request.onload = async() => {
                    //         if(request.status >= 200 && request.status < 300) {
                    //             load(request.responseText)
                    //         } else {
                    //             error()
                    //         }
                    //     }

                    //     request.send(formData)

                    //     return {
                    //         abort: () => {
                    //             request.abort()
                    //             abort()
                    //         }
                    //     }
                    // },
                    load: (source, load, error, progress, abort, headers) => {
                        // now load it using XMLHttpRequest as a blob then load it.
                        // console.log(source)
                        let request = new XMLHttpRequest();
                        request.open('GET', source);
                        request.responseType = "blob";
                        request.onreadystatechange = () => request.readyState === 4 && load(request.response);
                        request.send();
                    }
                },
            });

            $(document).ready(function(){
                createFilePondObserver('#'+ '{{ $file_id }}')
            })

            function createFileFond(element, uploaded_file_id){
                let files = uploaded_file_id != '' ? [
                        (
                            uploaded_file_id != ''
                                ? {
                                    source: '{{ $preview_route }}'.replace(':id', uploaded_file_id),
                                    options: {type: 'local'},
                                }
                            : {}
                        )
                        // {
                        //     source: @json(url('/filepond/63bc28a22fc905.24587859')),
                        //     options: {type: 'local'},
                        // }
                ] : [];

                try {
                    var _p = FilePond.create( element, {
                        // acceptedFileTypes: ['image/*'],
                        files: files,
                        // files: [
                        //     {
                        //         // the server file reference
                        //         // source: '',

                        //         // set type to local to indicate an already uploaded file
                        //         options: {
                        //             // type: 'local',

                        //             // optional stub file information
                        //             // file: {
                        //             //     name: 'B2_dikey_renk.png',
                        //             //     // size: 3001025,
                        //             //     type: 'image/png',
                        //             // },

                        //             // pass poster property
                        //             metadata: {
                        //                 poster: @json(url('/')) + '/assets/images/B2_dikey_renk.png',
                        //             },
                        //         },
                        //     },
                        // ],
                    });

                    _p.on('addfile', (error, data) => {
                        console.log('onaddfile', data)

                        if (error) {
                            console.log('Oh no');
                            return;
                        }

                        try {
                            var repeater = $(`input[value="/filepond/${data.filename}"]`).closest('details.repeater-details');
                            if( repeater.length > 0){
                                setTimeout((el) => {
                                    el.attr('open', false)
                                }, 300, repeater);
                            }

                        } catch (error) {
                            console.log('onaddfile repeater autoclose error with filename', data)
                        }


                    });
                    _p.on('processfile', (error,data) => {
                        console.log('onprocessfile', data, data.serverId, data.filename)
                        if (error) {
                            console.log(error);
                            return;
                        }

                        try {
                            var $form = $(`input[value="${data.serverId}"]`).closest('form');
                            validationReset($form);
                            validationInit(FormValidationObject, $form);

                        } catch (error) {
                            console.log('onProcessFile selecting form with serverId error', data)
                        }
                    });


                } catch (error) {
                    // console.log(error, element);
                }
            }

            function createFilePondObserver(selector){

                // The node to be monitored
                var parent = $( selector ).closest('form')[0];

                // Create an observer instance
                var filefond_observer = new MutationObserver(function( mutations ) {
                    let elements = mutations.filter( function(mutation){
                        return $(mutation.target).filter('input.filepond[type=file]').length > 0;
                    });

                    if(elements.length){
                        elements.forEach(element => {
                            // console.log($(element.target)[0]);
                            let val = $($(element.target)[0]).attr('value') ?? '';
                            createFileFond( $(element.target)[0], val )

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



        </script>
    @endpush
@endonce

@push('componentscript')
    <script>
        if(@json($generate)){
            createFileFond(document.querySelector('#'+ '{{ $file_id }}'), '{{ $value ?? "" }}');
        }
    </script>
@endpush


@endsection
