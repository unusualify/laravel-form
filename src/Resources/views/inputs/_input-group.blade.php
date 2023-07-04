<div class="input-group  {{ $layout ? $layout : 'row' }} {{ $class ? $class : '' }}">
     @isset($title)
        @if(is_array($title))
            <{{ $title['tag'] }} class="{{ $title['class'] }}"> 
                {{ $title['value'] }}
            </{{ $title['tag'] }}>
        @else
            {!! $title !!}
        @endif
     @endisset
    
    @foreach($inputs as $arr)
        @php
            $arr = array_merge($arr, ['hasParent' => true]);
        @endphp
        
        {{-- @dd($arr) --}}
        <div class="
            {{ $arr['class'] }} 
            col-{{ $arr['col'] ? $arr['col']['default'] : '12' }} 
            col-xs-{{ $arr['col']['xs'] ? $arr['col']['xs'] : '12' }}
            col-sm-{{ $arr['col']['sm'] ? $arr['col']['sm'] : '12' }}
            col-xs-{{ $arr['col']['md'] ? $arr['col']['md'] : '12' }}
            col-xs-{{ $arr['col']['lg'] ? $arr['col']['lg'] : '12' }}
            col-xs-{{ $arr['col']['xl'] ? $arr['col']['xl'] : '12' }}
            col-xs-{{ $arr['col']['xxl'] ? $arr['col']['xxl'] : '12' }}
            {{ isset($arr['align-center']) ? 'align-items-center' : '' }}
            {{ isset($arr['justify-center']) ? 'justify-content-center' : '' }}
        ">
            @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
        </div>
    @endforeach
</div>
