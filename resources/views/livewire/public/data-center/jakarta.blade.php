<div>
    <header id="header" class="header fixed-top">
        <nav class="navbar navbar-expand-lg">
            <div class="container">
                <a class="navbar-brand" href="/">
                    <img src="{{ asset('/assets/img/logo-main.webp') }}" alt="Brand" />
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
                                        <a class="nav-link dropdown-item" href="/data-center/batam/">NeutraDC Batam</a>
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
                                <a class="nav-link" href="/about-us/">About Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/news/">News</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/contact-us/">Contact Us</a>
                            </li>
                            <li class="nav-item">
                                <a class="nav-link" href="/two-hands-hub/">Two Hands Hub</a>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </nav>
    </header>

    <section id="hero" class="hero banner wrapper__relative">
        <div class="bg__img wrapper__absolute">
            <img src="{{ asset('/assets/img/banner-bg-coverage.webp') }}" alt="Banner" />
        </div>
        <div class="container">
            <div class="section__content">
                <div class="section__heading text-center">
                    <p>
                        <span> Digital Ecosystem Hub </span>
                    </p>
                    <h1>
                        NeutraDC Has the Most Reliable Data Center
                        Network in Indonesia.
                    </h1>
                </div>
            </div>
        </div>
    </section>

    <section id="intro" class="intro">
        <div class="container">
            <div class="section__content">
                <div class="section__heading text-center">
                    <h2><span>NeutraDC At A Glance</span></h2>
                    <p>
                        As a direct subsidiary of Telkom Group, NeutraDC
                        leverages the group’s extensive infrastructure
                        to deliver seamless, end-to-end connectivity
                        solutions. Our carrier-neutral data centers
                        provide direct access to a robust digital
                        ecosystem, including peering, transit,
                        point-to-point, and submarine cable networks,
                        ensuring global reach. Designed for scalability
                        and flexibility, we support hyperscale and
                        enterprise needs, connecting businesses to over
                        171 million digital users across Indonesia.
                    </p>
                </div>

                <div class="row__dc row row-cols-1 g-4">
                    <!--  -->
                    <div class="col">
                        <div class="card dc__card dc__card--hz">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card__img">
                                        <img src="{{ asset('/assets/img/jakarta-1.webp') }}"
                                            class="img-fluid rounded-start" alt="Cikarang" />
                                        <h4 class="card-title">
                                            Cikarang
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <ul>
                                                <li>
                                                    Strategic location
                                                    at GIIC Deltamas,
                                                    Cikarang (40 km from
                                                    Airport)
                                                </li>
                                                <li>
                                                    IT Load Capacity up
                                                    to 120 MW
                                                </li>
                                                <li>
                                                    Suitable for
                                                    Hyperscalers & Large
                                                    Enterprises
                                                </li>
                                                <li>
                                                    AI-ready Data Center
                                                </li>
                                                <li>
                                                    Diverse incoming
                                                    power cable paths to
                                                    2N power houses
                                                    within campus
                                                </li>
                                                <li>
                                                    Purpose-built
                                                    4-storey campus
                                                    facility (3 x DC
                                                    building & 1 x
                                                    Office building)
                                                </li>
                                                <li>Carrier Neutral</li>
                                                <li>
                                                    3 routes network
                                                    entry point into the
                                                    campus.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-footer text-center">
                                        <a href="#contact" class="btn btn__primary">
                                            Request Fact Sheet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="col">
                        <div class="card dc__card dc__card--hz">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card__img">
                                        <img src="{{ asset('/assets/img/jakarta-2.webp') }}"
                                            class="img-fluid rounded-start" alt="Serpong" />
                                        <h4 class="card-title">
                                            Serpong
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <ul>
                                                <li>
                                                    Strategic location
                                                    at Serpong, South
                                                    Tangerang
                                                </li>
                                                <li>
                                                    IT Load Capacity up
                                                    to 14 MW
                                                </li>
                                                <li>
                                                    Suitable for
                                                    Enterprises
                                                </li>
                                                <li>
                                                    Diverse incoming
                                                    power cable paths to
                                                    2N power houses
                                                    within data center
                                                </li>
                                                <li>
                                                    Purpose-built
                                                    7-storey (1 x DC
                                                    Building, 1 x Power
                                                    House, 1 x Office
                                                    Building) Carrier
                                                    Neutral
                                                </li>
                                                <li>
                                                    2 routes network
                                                    entry point into the
                                                    building.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-footer text-center">
                                        <a href="#contact" class="btn btn__primary">
                                            Request Fact Sheet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="col">
                        <div class="card dc__card dc__card--hz">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card__img">
                                        <img src="{{ asset('/assets/img/jakarta-3.webp') }}"
                                            class="img-fluid rounded-start" alt="Sentul" />
                                        <h4 class="card-title">
                                            Sentul
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <ul>
                                                <li>
                                                    Strategic location
                                                    at Sentul City,
                                                    Bogor.
                                                </li>
                                                <li>
                                                    IT Load Capacity up
                                                    to 5.5 MW
                                                </li>
                                                <li>
                                                    Suitable for
                                                    Enterprises
                                                </li>
                                                <li>
                                                    Diverse incoming
                                                    power cable paths to
                                                    2N power houses
                                                    within data center
                                                </li>
                                                <li>
                                                    Purpose built
                                                    4-storey (1 x DC
                                                    Building, 1 x Power
                                                    House, 1 x Office
                                                    Building)
                                                </li>
                                                <li>Carrier Neutral</li>
                                                <li>
                                                    2 routes network
                                                    entry point into the
                                                    building.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-footer text-center">
                                        <a href="#contact" class="btn btn__primary">
                                            Request Fact Sheet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="col">
                        <div class="card dc__card dc__card--hz">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card__img">
                                        <img src="{{ asset('/assets/img/jakarta-4.webp') }}"
                                            class="img-fluid rounded-start" alt="Surabaya" />
                                        <h4 class="card-title">
                                            Surabaya
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <ul>
                                                <li>
                                                    Strategic location
                                                    at Citraland,
                                                    Lakarsantri,
                                                    Surabaya.
                                                </li>
                                                <li>
                                                    IT Load Capacity up
                                                    to 3 MW
                                                </li>
                                                <li>
                                                    Suitable for
                                                    Enterprises
                                                </li>
                                                <li>
                                                    Diverse incoming
                                                    power cable paths to
                                                    2N power houses
                                                    within data center
                                                </li>
                                                <li>
                                                    Purpose built
                                                    4-storey (1 x DC
                                                    Building, 1 x Power
                                                    House, 1 x Office
                                                    Building)
                                                </li>
                                                <li>Carrier Neutral</li>
                                                <li>
                                                    2 routes network
                                                    entry point into the
                                                    building.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-footer text-center">
                                        <a href="#contact" class="btn btn__primary">
                                            Request Fact Sheet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <!--  -->
                    <div class="col">
                        <div class="card dc__card dc__card--hz">
                            <div class="row g-4">
                                <div class="col-md-4">
                                    <div class="card__img">
                                        <img src="{{ asset('/assets/img/edge-dc.webp') }}"
                                            class="img-fluid rounded-start" alt="EdgeDC" />
                                        <h4 class="card-title">
                                            EdgeDC
                                        </h4>
                                    </div>
                                </div>
                                <div class="col-md-8">
                                    <div class="card-body">
                                        <div class="card-text">
                                            <ul>
                                                <li>
                                                    Edge Data Center is
                                                    brought to you by
                                                    Telkom Group’s
                                                    NeuCentrIX. Located
                                                    in 20 major cities
                                                    in Indonesia,
                                                    NeuCentrIX provides
                                                    a total of 6 MW
                                                    capacity with wide
                                                    coverage and
                                                    seamless
                                                    connectivity.
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="card-footer text-center">
                                        <a href="#contact" class="btn btn__primary">
                                            Request Fact Sheet
                                        </a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="presence" class="presence bg__primary--alt">
        <div class="container">
            <div class="section__content">
                <div class="section__heading text-center">
                    <h2>Our Presence in Indonesia</h2>
                    <p>
                        NeutraDC Jakarta (HQ) serves as the cornerstone
                        of our presence in Indonesia, delivering
                        scalable and resilient infrastructure to meet
                        diverse business needs. HDC Cikarang is
                        purpose-built for hyperscalers, offering
                        high-density capacity and robust connectivity.
                        Our 3 SDC facilities in Serpong, Sentul, and
                        Surabaya cater to enterprises seeking secure,
                        high-performance data center solutions.
                        Additionally, our nationwide Edge DC network
                        spans 20 major cities, enhancing connectivity
                        and enabling low-latency access to digital
                        services across Indonesia.
                    </p>
                </div>
                <img class="cover" src="{{ asset('/assets/img/jakarta-map-latest-2.webp') }}" alt="Presence" />
            </div>
        </div>
    </section>

    <section id="certified" class="certified text-center">
        <div class="container">
            <div class="section__content">
                <div class="certified__wrapper bg__danger--linear">
                    <h2>Certified for Global Standards</h2>
                    <ul>
                        <li>UTI Tier IV Design</li>
                        <li>UTI Tier IV Facility</li>
                        <li>UTI Tier III Design</li>
                        <li>UTI Tier III Facility</li>
                        <li>UTI Tier III Operation Rating Gold</li>
                        <li>ISO 27001 ISMS</li>
                        <li>ISO 9001 QMS</li>
                        <li>ISO 14001 EMS</li>
                        <li>ISO 45001 HSE</li>
                        <li>ISO 37001 Anti-Bribery SOC-2 Type 2</li>
                        <li>TVRA PCI DSS</li>
                    </ul>
                </div>
            </div>
        </div>
    </section>

    <section id="info" class="info">
        <div class="container">
            <div class="section__content">
                <div class="section__heading text-center">
                    <h2>Why NeutraDC?</h2>
                </div>

                <div class="accordion__services">
                    <div class="accordion accordion-flush" id="accordionServices">
                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapseOne" aria-expanded="true"
                                    aria-controls="flush-collapseOne">
                                    <svg class="bi" aria-hidden="true">
                                        <use xlink:href="#svg-regional-footprint"></use>
                                    </svg>
                                    Regional Footprint
                                </button>
                            </h2>
                            <div id="flush-collapseOne" class="accordion-collapse collapse show"
                                data-bs-parent="#accordionServices">
                                <div class="accordion-body">
                                    <p>
                                        Expanding in key locations with
                                        flexible data center solutions,
                                        we empower customers to scale
                                        with 500MW of planned capacity
                                        over the next 5 years. As part
                                        of Telkom Group and being
                                        carrier-neutral, our rich
                                        carrier ecosystem enables
                                        seamless connectivity. Our data
                                        centers provide innovative,
                                        customized solutions for unique
                                        needs, while our strategic
                                        growth supports easy market
                                        entry and rapid expansion. We
                                        are committed to delivering
                                        reliable, scalable, and
                                        sustainable infrastructure to
                                        drive our customers' success.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse2" aria-expanded="false"
                                    aria-controls="flush-collapse2">
                                    <svg class="bi" aria-hidden="true">
                                        <use xlink:href="#svg-built-for-excellence"></use>
                                    </svg>
                                    Built for Excellence
                                </button>
                            </h2>
                            <div id="flush-collapse2" class="accordion-collapse collapse"
                                data-bs-parent="#accordionServices">
                                <div class="accordion-body">
                                    <p>
                                        Our data centers are designed
                                        for at least UTI Tier III
                                        availability, combining
                                        state-of-the-art civil,
                                        mechanical, and electrical
                                        design with robust security and
                                        a thriving connectivity
                                        ecosystem. Designed to support
                                        any workload, from hyperscale to
                                        AI-intensive applications, we
                                        offer carrier-neutral solutions
                                        with diverse network options to
                                        meet every connectivity demand.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse3" aria-expanded="false"
                                    aria-controls="flush-collapse3">
                                    <svg class="bi" aria-hidden="true">
                                        <use xlink:href="#svg-sustainability"></use>
                                    </svg>
                                    Sustainability
                                </button>
                            </h2>
                            <div id="flush-collapse3" class="accordion-collapse collapse"
                                data-bs-parent="#accordionServices">
                                <div class="accordion-body">
                                    <p>
                                        With certified green designs and
                                        a commitment to achieving
                                        net-zero emissions by 2030, our
                                        data centers set a benchmark in
                                        environmental responsibility. By
                                        maintaining industry-leading PUE
                                        and WUE metrics, we deliver
                                        operational excellence while
                                        reducing our environmental
                                        impact.
                                    </p>
                                </div>
                            </div>
                        </div>

                        <div class="accordion-item">
                            <h2 class="accordion-header">
                                <button class="accordion-button collapsed" type="button" data-bs-toggle="collapse"
                                    data-bs-target="#flush-collapse4" aria-expanded="false"
                                    aria-controls="flush-collapse4">
                                    <svg class="bi" aria-hidden="true">
                                        <use xlink:href="#svg-future-ready-tech"></use>
                                    </svg>
                                    Future-ready Tech
                                </button>
                            </h2>
                            <div id="flush-collapse4" class="accordion-collapse collapse"
                                data-bs-parent="#accordionServices">
                                <div class="accordion-body">
                                    <p>
                                        Leveraging advanced telemetry
                                        and AI-powered operations, our
                                        data centers ensure precise
                                        control, monitoring, and
                                        optimization. Predictive and
                                        preventive maintenance through
                                        AI enhances efficiency and
                                        reliability, keeping operations
                                        ahead of evolving technological
                                        needs.
                                    </p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="grid grid__stack">
            <div class="container">
                <div class="row">
                    <div class="col-md-7">
                        <div class="section__content">
                            <div class="section__heading text--danger text__xl">
                                <h1>Get in Touch</h1>

                                <div class="link__wrapper vstack">
                                    <a href="mailto:sales.admin@neutradc.com" class="cta cta__alt">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-email"></use>
                                        </svg>
                                        sales.admin@neutradc.com
                                    </a>
                                    <a href="#" class="cta cta__alt" target="_blank">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-pin"></use>
                                        </svg>
                                        Telkom Landmark Tower, 5th
                                        Floor, Jl. Gatot Subroto South
                                        Jakarta, Indonesia
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="contact" class="contact">
        <div class="container">

            <div class="section__content">

                <div class="form__wrapper bg__danger bg__danger--linear bg__danger">

                    <form class="row g-4" wire:submit="save">
                        <div class="col-md-6">
                            <input id="contactNameFirst" type="text" class="form-control"
                                placeholder="First Name" aria-label="First Name" wire:model.defer="first_name"
                                required />
                            @error('first_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactNameLast" type="text" class="form-control" placeholder="Last Name"
                                aria-label="Last Name" wire:model.defer="last_name" required />
                            @error('last_name')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCompany" type="text" class="form-control"
                                placeholder="Company Name" aria-label="Company Name" wire:model.defer="company"
                                required />
                            @error('company')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactJob" type="text" class="form-control" placeholder="Job Title"
                                aria-label="Job Title" wire:model.defer="job" required />
                            @error('job')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactCountry" type="text" class="form-control" placeholder="Country"
                                aria-label="Country" wire:model.defer="country" required />
                            @error('country')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-md-6">
                            <input id="contactPhone" type="text" inputmode="numeric" pattern="[0-9]+"
                                class="form-control" placeholder="Phone Number" aria-label="Phone Number"
                                wire:model.defer="phone" required />
                            @error('phone')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <input id="contactEmail" type="email" class="form-control" placeholder="Email"
                                aria-label="Email" wire:model.defer="email" required />
                            @error('email')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>
                        <div class="col-12">
                            <textarea id="contactMessage" class="form-control" placeholder="Message" aria-label="Message" required
                                rows="4" wire:model.defer="message"></textarea>
                            @error('message')
                                <span class="text">*{{ $message }}</span>
                            @enderror
                        </div>

                        <div class="col-12">
                            <div class="form-check">
                                @error('terms')
                                    <span class="text">*{{ $message }}</span>
                                @enderror
                                <input class="form-check-input" type="checkbox" value="" id="contactTerms"
                                    aria-describedby="contactTermsFeedback" wire:model.defer="terms" required />
                                <label class="form-check-label" for="contactTerms">
                                    I By submitting this Contact Us
                                    form, you consent to receiving
                                    information related to NeutraDC
                                    products and services. (you must
                                    agree before submitting) NeutraDC
                                    Privacy Policy
                                </label>
                            </div>
                        </div>

                        <div class="col-12">
                            <button label="save" type="submit" class="btn btn__primary" spinner="save">
                                Submit
                            </button>
                        </div>
                        @if (session('success'))
                            <div x-data="{ show: true }" x-show="show" x-cloak
                                style="background-color:#d1fae5; border:1px solid #34d399; color:#065f46; padding: 0.75rem 1rem; border-radius: 6px; margin-bottom: 1rem; display: flex; justify-content: space-between; align-items: center;width: 100%;">

                                <span style="flex-grow: 1;">
                                    {{ session('success') }}

                                </span>

                                <button @click="show = false"
                                    style="background: none; border: none; font-weight: bold;
                   font-size: 1.25rem; color: #065f46; cursor: pointer;
                   margin-left: 1rem; line-height: 1;">
                                    &times;
                                </button>
                            </div>
                        @endif
                    </form>
                </div>
            </div>
        </div>
    </section>
</div>
