<!-- BEGIN: SideNav-->

<aside class="sidenav-main nav-expanded nav-lock nav-collapsible sidenav-light sidenav-active-square">
    <div class="brand-sidebar">
        <h1 class="logo-wrapper">
            <a class="brand-logo darken-1" href="{{url('/admin/home')}}">
                <img class="hide-on-med-and-down" src="{{asset('images/logo/materialize-logo-color.png')}}" alt="materialize logo"/>
                <img  class="show-on-medium-and-down hide-on-med-and-up"  src="{{asset('images/logo/materialize-logo.png')}}" alt="materialize logo"/>
                <span class="logo-text hide-on-med-and-down">Materialize</span></a>
            <a class="navbar-toggler" href="#"><i class="material-icons">radio_button_checked</i></a>
        </h1>
    </div>
    <ul class="sidenav sidenav-collapsible leftside-navigation collapsible sidenav-fixed menu-shadow" id="slide-out"
        data-menu="menu-navigation" data-collapsible="menu-accordion">

        {{-- Foreach menu item starts --}}
        @if(!empty($menuData[0]) && isset($menuData[0]))
            @foreach ($menuData[0]->menu_admin as $menu)
                @if(isset($menu->navheader))
                    <li class="navigation-header">
                        <a class="navigation-header-text">{{ __('locale.'.$menu->navheader)}}</a>
                        <i class="navigation-header-icon material-icons">{{$menu->icon }}</i>
                    </li>
                @else
                    @php
                        $custom_classes="";
                        if(isset($menu->class))
                        {
                        $custom_classes = $menu->class;
                        }
                        $translation = "";
                        if(isset($menu->i18n)){
                        $translation = $menu->i18n;
                        }
                    @endphp
                    <li class="{{(request()->is($menu->url.'*')) ? 'active' : '' }} bold">
                        <a class="{{$custom_classes}} {{ (request()->is($menu->url.'*')) ? 'active ': ''}}"
                           @if(!empty($configData['activeMenuColor'])) {{'style=background:none;box-shadow:none;'}} @endif
                           href="@if(($menu->url)==='javascript:void(0)'){{$menu->url}} @else{{url($menu->url)}} @endif"
                            {{isset($menu->newTab) ? 'target="_blank"':''}}>
                            <i class="material-icons">{{$menu->icon}}</i>
                            <span class="menu-title" data-i18n="{{$translation}}">{{ __('locale.'.$menu->name)}}</span>
                            @if(isset($menu->tag))
                                <span class="{{$menu->tagcustom}}">{{$menu->tag}}</span>
                            @endif
                        </a>
                        @if(isset($menu->submenu))
                            @include('admin.panels.submenu', ['menu' => $menu->submenu])
                        @endif
                    </li>
                @endif
            @endforeach
        @endif
    </ul>
    <div class="navigation-background"></div>
    <a class="sidenav-trigger btn-sidenav-toggle btn-floating btn-medium waves-effect waves-light hide-on-large-only"
       href="#" data-target="slide-out"><i class="material-icons">menu</i></a>
</aside>

<!-- END: SideNav-->
