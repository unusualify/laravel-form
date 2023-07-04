<form class="form-group row">
    @isset($formTitle)
       @if(isset($formTitle['type']))
        <{{ $formTitle['type'] }} 
        class="{{ isset($formTitle['class']) ? $formTitle['class'] : '' }}">
           {{ $formTitle['content'] }}
        </{{ $formTitle['type'] }}>

       @else
        <h2 class="{{ isset($formTitle['class']) ? $formTitle['class'] : '' }}">
            {{ $formTitle['content'] }}
        </h2>
       @endisset
    @endisset
    @foreach($inputs as $arr)
    {{-- @if($arr['type'] == 'checkbox')
        @dd('unusual_form::inputs._' . $arr['type'])
    @endif --}}
            @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
    @endforeach
</form>
