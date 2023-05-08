<label>{{$label}}</label>
<textarea
    type="text"
    name="{{ $input_name }}"
    class="form-control {{$class??''}}"
    placeholder="{{ $placeholder ?? ''}}"
    id=""
    rows="2"
    {{$props}}
    >{{ isset($model) ? getModelInput($model, $input_name) : ''}}
</textarea>
<span class="help-block"> {{ $help_label ?? '' }} </span>
{{-- <p class="help-block"></p> --}}
