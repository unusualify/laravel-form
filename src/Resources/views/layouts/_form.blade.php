<form class="form-group row">
    @foreach($inputs as $arr)
        <div class="input-group col-12 col-md-6 col-lg-6 col-xl-12">
            @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
        </div>
    @endforeach
</form>
