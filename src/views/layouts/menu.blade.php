@foreach($menus as $menu)

  @if(!is_null($menu->datasource))
  @if(Input::get('pds'))
    <li class="truncate {{ ($menu->datasource->id==Input::get('pds') ? 'active' : '') }}">
        <a class="" href="{{ URL::to(Config::get('cms::config.uri').'/ds/'.$menu->datasource->id) }}">
            <i class="fa fa-database"></i>
            <span class="mightOverflow truncate">{{ $menu->name?$menu->name:$menu->datasource->name }}</span>
        </a>
    </li>
    @else
    <li class="truncate @if(count($menu->children)) sub-menu @endif {{ (Request::is(Config::get('cms::config.uri').'/ds/'.$menu->datasource->id.'*') ? 'active' : '') }}">
        <a class="" href="{{ URL::to(Config::get('cms::config.uri').'/ds/'.$menu->datasource->id) }}">
            <i class="fa {{ $menu->icon?$menu->icon:'fa-database' }}"></i>
            <span class="mightOverflow truncate">{{ $menu->name?$menu->name:$menu->datasource->name }}</span>
            @if(count($menu->children))<span class="arrow {{ (Request::is(Config::get('cms::config.uri').'/map/*') ? ' open' : '') }}"></span>@endif
        </a>
        @if(count($menu->children))
        <ul class="sub">
            @foreach($menu->children as $submenu)
              @if(!is_null($submenu->datasource))
              <li class="truncate {{ (Request::is(Config::get('cms::config.uri').'/ds/'.$submenu->datasource->id.'*') ? 'active':'') }}"><a href="{{ URL::to(Config::get('cms::config.uri').'/ds/'.$submenu->datasource->id) }}">{{ $submenu->name?$submenu->name:$submenu->datasource->name }}</a></li>
              @else
              <li class="truncate {{ (Request::is(Config::get('cms::config.uri').$submenu->url.'*') ? 'active':'') }}"><a href="{{ URL::to(Config::get('cms::config.uri').$submenu->url) }}">{{ $submenu->name }}</a></li>
              @endif
            @endforeach
        </ul>
        @endif
    </li>
    @endif
  @else
  <li class="truncate @if(count($menu->children)) sub-menu @endif @if($menu->url){{ (Request::is(Config::get('cms::config.uri').$menu->url.'*') ? ' active' : '') }}@endif">
    <a href="{{ $menu->url?URL::to(Config::get('cms::config.uri').$menu->url):'javascript:;' }}" class="">
        <i class="fa {{ $menu->icon }}"></i>
        <span class="mightOverflow truncate">{{ $menu->name }}</span>
        @if(count($menu->children))<span class="arrow"></span>@endif
    </a>
    @if(count($menu->children))
    <ul class="sub">
        @foreach($menu->children as $submenu)
          @if(!is_null($submenu->datasource))
          <li class="truncate {{ (Request::is(Config::get('cms::config.uri').'/ds/'.$submenu->datasource->id) || Request::is(Config::get('cms::config.uri').'/ds/'.$submenu->datasource->id.'/*') ? 'active':'') }}"><a href="{{ URL::to(Config::get('cms::config.uri').'/ds/'.$submenu->datasource->id) }}">{{ $submenu->name?$submenu->name:$submenu->datasource->name }}</a></li>
          @else
          <li class="truncate {{ (Request::is(Config::get('cms::config.uri').$submenu->url.'*') ? 'active':'') }}"><a href="{{ URL::to(Config::get('cms::config.uri').$submenu->url) }}">{{ $submenu->name }}</a></li>
          @endif
        @endforeach
    </ul>
    @endif
  </li>
  @endif

@endforeach



