<style>
    .sub_menu {
        position: relative;
        display: block;
        width: 100%;
    }
    .menu_dropdown .sub_sub_dropdown {
        position: absolute;
        width: 250px;
        left: -200px;
        background: #fafafa;
        list-style: none;
        padding: 10px;
        box-shadow: 0px 0px 15px 0px rgba(0, 0, 0, 0.1);
        opacity: 0;
        visibility: hidden !important;
        transition: all .4s ease-in-out;
    }

    .sub_menu:hover .sub_sub_dropdown {
        opacity: 1;
        visibility: visible !important;
        top: 15px !important;
    }
</style>
<li class="nav-item menu_dropdown">
    <a class="nav-link menu_dropdown-toggle {{$mp=='help'?'active':''}}" href="#" id="helpmenu_dropdown" role="button"
        data-toggle="menu_dropdown" aria-haspopup="true" aria-expanded="false">Help<i class="fa-solid fa-angle-down"></i></a>
    <div class="menu_dropdown-menu " aria-labelledby="helpmenu_dropdown">
        @forelse ($helps as $help)
        @if ($help->subCategories->count() > 0)
        <ul class="navbar-nav">
            <li class="nav-item menu_dropdown sub_menu">
                <a class="menu_dropdown-toggle" href="#" id="sub_{{$help->id}}" role="button" data-toggle="menu_dropdown"
                    aria-haspopup="true" aria-expanded="false">{{$help->name}} {!! $help->subCategories->count()>0?'<i class="fa-solid fa-angle-right"></i>':'' !!}</a>
                <div class="menu_dropdown-menu sub_sub_dropdown" aria-labelledby="sub_{{$help->id}}">
                    {{-- <a class="menu_dropdown-item {{$p==$help->slug?'active':''}}" href="{{route('helpdesk.byCat', $help->slug)}}">{{$help->name}}</a> --}}
                    @forelse ($help->subCategories as $sub)
                    <a class="menu_dropdown-item {{$p==$sub->slug?'active':''}}"
                        href="{{route('helpdesk.byCat', $sub->slug)}}">
                        {{$sub->name}}
                    </a>
                    @empty
                    <a class="menu_dropdown-item" href="#">{{$help->name}} Sub Menu not found!</a>
                    @endforelse
                </div>
            </li>
        </ul>
        @else
        <a class="menu_dropdown-item {{$p==$help->slug?'active':''}}"
            href="{{route('helpdesk.byCat', $help->slug)}}">{{$help->name}}</a>
        @endif
        @empty
        <p class="text-center">Menu not found!</p>
        @endforelse
    </div>
</li>
