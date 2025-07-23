<header id="header" class="header fixed-top">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{asset('/assets/img/logo-main.webp')}}" alt="Brand" />
            </a>
            <button class="btn btn__toggler navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Button Menu">
                <span></span><span></span><span></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel" data-bs-backdrop="static">
                <div class="offcanvas-body">

                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        @foreach ($menus as $menu)
                            @if ($menu->children->isNotEmpty())
                                <li class="nav-item dropdown">
                                    <a class="nav-link dropdown-toggle" href="#" role="button"
                                        data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                        {{ $menu->name }}
                                    </a>
                                    <ul class="dropdown-menu">
                                        @foreach ($menu->children as $child)
                                            <li>
                                                <a class="nav-link dropdown-item" href="{{ url($child->link) }}">
                                                    {{ $child->name }}
                                                </a>
                                            </li>
                                        @endforeach
                                    </ul>
                                </li>
                            @else
                                @if ($menu->link)
                                    <a class="nav-link" href="{{ url($menu->link) }}">
                                        {{ $menu->name }}
                                    </a>
                                @else
                                    <span class="nav-link disabled">{{ $menu->name }}</span>
                                @endif
                            @endif
                        @endforeach

                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>
