<div class="input-group  {{ $layout ? $layout : 'row' }} {{ $class ? $class : '' }}">
    @foreach($inputs as $arr)
        <div class="input-container col-6 col-md-6 col-lg-6 col-xl-6">
            @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
        </div>
    @endforeach
</div>
