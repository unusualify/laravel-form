@if(isset($arr['col']) && !$arr['hasParent'])

<div class="  col
              col-{{ isset($arr['col']) ? $arr['col']['default'] : '12' }} 
              col-xs-{{ isset($arr['col']) ? $arr['col']['xs'] : '12' }} 
              col-sm-{{ isset($arr['col']) ? $arr['col']['sm'] : '12' }} 
              col-md-{{ isset($arr['col']) ? $arr['col']['md'] : '12' }} 
              col-lg-{{ isset($arr['col']) ? $arr['col']['lg'] : '12' }} 
              col-xl-{{ isset($arr['col']) ? $arr['col']['xl'] : '12' }}
              col-xxl-{{ isset($arr['col']) ? $arr['col']['xxl'] : '12' }}
              {{ isset($arr['align-center']) ? 'align-items-center' : '' }}
              {{ isset($arr['justify-center']) ? 'justify-content-center' : '' }}

              ">
              {{-- 'xs' => 6, //required if col is used
                    'sm' => 6, //required if col is used
                    'md' => 6, //required if col is used
                    'lg' => 6, //required if col is used
                    'xl' => 6, //required if col is used
                    'xxl' => 6, --}}
    @yield('input')
  </div>

@else

  {{-- <div class="col
              col-12
              col-xs-12
              col-sm-12
              col-md-12
              col-lg-12
              col-xl-12
              col-xxl-12
              {{ isset($arr['align-center']) ? 'align-items-center' : '' }}
              {{ isset($arr['justify-center']) ? 'justify-content-center' : '' }}
              ">
              'xs' => 6, //required if col is used
                    'sm' => 6, //required if col is used
                    'md' => 6, //required if col is used
                    'lg' => 6, //required if col is used
                    'xl' => 6, //required if col is used
                    'xxl' => 6, 
    @yield('input')

  </div> --}}
      @yield('input')


@endif


