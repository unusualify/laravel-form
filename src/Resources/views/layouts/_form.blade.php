<div class="form-group row">
    @foreach($inputs as $arr)
        <div class="form-group col-12 col-md-6 col-lg-6 col-xl-6">
            @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
        </div>
    @endforeach
</div>
