<script>
    let validationMsg = {!! $validation ?? 'null' !!};
    
</script>
{{-- @dd(json_decode($validation)) --}}
{{-- @dd($formData) --}}
@if(key_exists('inputs', $formData))
    <form class="form-group row {{ isset($formData['class']) ? $formData['class'] : ''  }}" id="{{ isset($formData['id']) ? $formData['id'] : ''  }}">
        @isset($formData['title'])
            @if(isset($formData['title']['type']))
                <{{ $formData['title']['type'] }} 
                class="{{ isset($formData['title']['class']) ? $formData['title']['class'] : '' }}">
                {{ $formData['title']['content'] }}
                </{{ $formData['title']['type'] }}>

            @else
                <h2 class="{{ isset($formData['title']['class']) ? $formData['title']['class'] : '' }}">
                    {{ $formData['title']['content'] }}
                </h2>
        
            @endif
        @endisset
        @foreach($formData['inputs'] as $arr)
        {{-- @if($arr['type'] == 'checkbox')
            @dd('unusual_form::inputs._' . $arr['type'])
        @endif --}}
                @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
        @endforeach
    </form>

@elseif(key_exists('steps',$formData))
        <form class="form-group row {{ isset($formData['class']) ? $formData['class'] : ''  }}" id="{{ isset($formData['id']) ? $formData['id'] : ''  }}">
            @foreach($formData['steps'] as $forms)
                <div class="form-step step-{{ $loop->index }} {{ $loop->index === 0 ? 'active' : '' }}">
                @isset($forms['title'])
                    @if(isset($forms['title']['type']))
                        <{{ $forms['title']['type'] }} 
                        class="{{ isset($forms['title']['class']) ? $forms['title']['class'] : '' }}">
                        {{ $forms['title']['content'] }}
                        </{{ $forms['title']['type'] }}>

                    @else
                        <h2 class="{{ isset($title['class']) ? $forms['title']['class'] : '' }}">
                            {{ $forms['title']['content'] }}
                        </h2>
                    
                    @endif
                @endisset
                    {{-- @dd($forms['inputs']) --}}
                    @foreach($forms['inputs'] as $arr)
                        @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
                    @endforeach
                </div>
            @endforeach

        </form>

@else

    @foreach ($formData as $forms)
        <form class="form-group row form-step step-{{ $loop->index }}" id="{{ isset($formData['id']) ? $formData['id'] : ''  }}">
            @isset($forms['title'])
            @if(isset($forms['title']['type']))
                <{{ $forms['title']['type'] }} 
                class="{{ isset($forms['title']['class']) ? $forms['title']['class'] : '' }}">
                {{ $forms['title']['content'] }}
                </{{ $forms['title']['type'] }}>

            @else
                <h2 class="{{ isset($title['class']) ? $forms['title']['class'] : '' }}">
                    {{ $forms['title']['content'] }}
                </h2>
            @endisset
            @endisset
            @foreach($forms['inputs'] as $arr)
                    @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
            @endforeach
        </form>
    @endforeach

@endif

