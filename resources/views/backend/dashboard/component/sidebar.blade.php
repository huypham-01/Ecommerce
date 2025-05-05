@php
    $segment = request()->segment(1);
@endphp
<nav class="navbar-default navbar-static-side" role="navigation">
    <div class="sidebar-collapse">
        <ul class="nav metismenu" id="side-menu">
            <li class="nav-header">
                <div class="dropdown profile-element"> <span>
                        
                         </span>
                    <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                        <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold">QUANG HUY</strong>
                         </span> <span class="text-muted text-xs block">Cấu hình<b class="caret"></b></span> </span> </a>
                    <ul class="dropdown-menu animated fadeInRight m-t-xs">
                        <li><a href="">Thông tin các nhân</a></li>
                        <li><a href="">Liên hệ</a></li>
                        <li class="divider"></li>
                        <li><a href="{{ route('auth.logout') }}">Đăng xuất</a></li>
                    </ul>
                </div>
                <div class="logo-element">
                    IN+
                </div>
            </li>
            @foreach(__('sidebar.module') as $key => $val)
                <li class="{{ (in_array($segment, $val['name'])) ? 'active' : '' }}">
                    <a href=""><i class="{{ $val['icon'] }}"></i> <span class="nav-label">{{ $val['title'] }}</span> <span class="fa arrow"></span></a>
                    @if (isset($val['subModule']))
                        <ul class="nav nav-second-level">
                            @foreach ($val['subModule'] as $module)
                                <li><a href="{{ $module['route'] }}">{{ $module['title'] }}</a></li>
                            @endforeach
                        </ul>
                    @endif
                </li>    
            @endforeach
        </ul>
    </div>
</nav>