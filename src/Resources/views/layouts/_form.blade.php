<form class="form-group row">
    @foreach($inputs as $arr)
    {{-- @if($arr['type'] == 'checkbox')
        @dd('unusual_form::inputs._' . $arr['type'])
    @endif --}}
            @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
    @endforeach
</form>
