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
        {{-- @dd($arr['col']['xs']) --}}
            <div class=" 
                col
                col-{{ isset($arr['col']) && is_array($arr['col']) ? $arr['col']['default'] : 'auto' }} 
                col-xs-{{ isset($arr['col']['xs']) && is_array($arr['col']) ? $arr['col']['xs'] : 'auto' }}
                col-sm-{{ isset($arr['col']['sm']) && is_array($arr['col']) ? $arr['col']['sm'] : 'auto' }}
                col-xs-{{ isset($arr['col']['md']) && is_array($arr['col']) ? $arr['col']['md'] : 'auto' }}
                col-xs-{{ isset($arr['col']['lg']) && is_array($arr['col']) ? $arr['col']['lg'] : 'auto' }}
                col-xs-{{ isset($arr['col']['xl']) && is_array($arr['col']) ? $arr['col']['xl'] : 'auto' }}
                col-xs-{{ isset($arr['col']['xxl']) && is_array($arr['col']) ? $arr['col']['xxl'] : 'auto' }}
                {{ isset($arr['align-items']) ? 'align-items-'.$arr['align-items']: '' }}
                {{ isset($arr['justify-content']) ? 'justify-content-'.$arr['justify-content'] : '' }}
            ">
                @include('unusual_form::inputs._' . $arr['type'], array_diff_key($arr, array_flip(["type"])))
            </div>
    @endforeach
</div>
