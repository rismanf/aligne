<header id="header" class="header fixed-top">
    <nav class="navbar navbar-expand-lg">
        <div class="container">
            <a class="navbar-brand" href="/">
                <img src="{{ asset('assets/img/logo-main.webp') }}" alt="Brand" />
            </a>
            <button class="btn btn__toggler navbar-toggler" type="button" data-bs-toggle="offcanvas"
                data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Button Menu">
                <span></span><span></span><span></span>
            </button>
            <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar"
                aria-labelledby="offcanvasNavbarLabel" data-bs-backdrop="static">
                <div class="offcanvas-body">
                    <ul class="navbar-nav justify-content-end flex-grow-1">
                        <li class="nav-item">
                            <a class="nav-link" href="/two-hands-hub/">Two Hands Hub</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/neutradc-summit/">NeutraDC Summit</a>
                        </li>
                        <li class="nav-item dropdown">
                            <a class="nav-link dropdown-toggle" href="/data-center/" role="button"
                                data-bs-toggle="dropdown" aria-expanded="false" data-bs-auto-close="outside">
                                Data Center
                            </a>
                            <ul class="dropdown-menu">
                                <li>
                                    <a class="nav-link dropdown-item" href="/data-center/jakarta-hq/">NeutraDC
                                        Jakarta</a>
                                </li>
                                <li>
                                    <a class="nav-link dropdown-item" href="/data-center/batam/">NeutraDC
                                        Batam</a>
                                </li>
                                <li>
                                    <a class="nav-link dropdown-item" href="/data-center/singapore/">NeutraDC
                                        Singapore</a>
                                </li>
                            </ul>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/services/">Services</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/about-us/">About us</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/news/">News</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link" href="/contact-us/">Contact Us</a>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </nav>
</header>