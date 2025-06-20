<div>
    <section id="hero" class="hero banner">
        <div class="grid grid__stack">
            <div class="bg__img section__vh">
                <img class="only__desktop" src="{{asset('/assets/img/banner-home-hero-latest-2a.webp')}}" alt="Banner" />
                <img class="only__mobile" src="{{asset('/assets/img/banner-home-hero-latest-2a.webp')}}" alt="Banner" />
            </div>
            <div class="section__content align__center">
                <div class="container">
                    <div class="row">
                        <div class="col-lg-6">
                            <div class="section__heading">
                                <p>
                                    <span> Welcome to NeutraDC </span>
                                </p>
                                <h1>
                                    YOUR DIGITAL ECOSYSTEM PARTNER IN
                                    SOUTHEAST ASIA’S FASTEST GROWING
                                    ECONOMY
                                </h1>
                            </div>

                            <div class="link__wrapper">
                                <a href="{{ route('about-us') }}" class="btn btn__danger btn--round">
                                    Leverage our capabilities
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="intro" class="intro">
        <div class="container">
            <div class="section__content">
                <div class="row g-4">
                    <div class="col-md-6">
                        <div class="section__heading">
                            <h2>
                                LEVERAGE OUR INFRASTRUCTURE FOR
                                <span> SEAMLESS CONNECTIVITY </span>
                            </h2>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <p>
                            As a subsidiary of Telkom Group, NeutraDC
                            can combine the group’s capabilities to
                            offer tailor-made, comprehensive solutions.
                            We offer end-to-end solutions through a
                            digital ecosystem and connectivity hub for
                            connectivity, which includes peering,
                            transit, point-to-point and submarine cables
                            for seamless connectivity to the rest of the
                            world.
                        </p>
                        <p>Discover Our Data Centers</p>

                        <div class="link__wrapper">
                            <a href="{{ route('data-center.jakarta-hq') }}" class="btn btn__danger btn--round">
                                Jakarta
                            </a>
                            <a href="{{ route('data-center.batam') }}" class="btn btn__danger btn--round">
                                Batam
                            </a>
                            <a href="{{ route('data-center.singapore') }}" class="btn btn__danger btn--round">
                                Singapore
                            </a>
                        </div>
                    </div>
                </div>

                <div class="row row-cols-1 row-cols-md-2 g-4 row--space">
                    <div class="col">
                        <div class="card intro__card">
                            <div class="row g-0">
                                <div class="col-lg-4">
                                    <div class="card__img">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-infrastructure"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            Best-in-Class Infrastructure
                                        </h3>
                                        <p class="card-text">
                                            Distributed redundant N+1
                                            power and cooling
                                            configuration to ensure
                                            continuous operations.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card intro__card">
                            <div class="row g-0">
                                <div class="col-lg-4">
                                    <div class="card__img">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-data-center"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            All data center locations
                                            are secured
                                        </h3>
                                        <p class="card-text">
                                            With server room access
                                            restricted by up to 8 layers
                                            of security.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card intro__card">
                            <div class="row g-0">
                                <div class="col-lg-4">
                                    <div class="card__img">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-certifications"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            Enterprise-Grade
                                            Certifications
                                        </h3>
                                        <p class="card-text">
                                            Achieved Uptime Institute
                                            Tier III Design
                                            certification (TCCD) to
                                            ensure concurrent
                                            maintainability.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col">
                        <div class="card intro__card">
                            <div class="row g-0">
                                <div class="col-lg-4">
                                    <div class="card__img">
                                        <svg class="icon" aria-hidden="true">
                                            <use xlink:href="#svg-facilities"></use>
                                        </svg>
                                    </div>
                                </div>
                                <div class="col-lg-8">
                                    <div class="card-body">
                                        <h3 class="card-title">
                                            Best-Practice-Based
                                            Facilities
                                        </h3>
                                        <p class="card-text">
                                            Space, power, network,
                                            personnel and internal
                                            infrastructure are optimised
                                            for seamless enterprise
                                            computing.
                                        </p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="growth" class="growth">
        <div class="container container__full">
            <div class="section__content">
                <div class="grid grid__growth">
                    <div class="grid__growth--item">
                        <div class="container">
                            <div class="section__heading text__xl">
                                <h2>
                                    FUEL YOUR
                                    <span class="text__bg">GROWTH</span>
                                </h2>
                                <p>
                                    Scale with NeutraDC. Our digital
                                    ecosystem helps
                                    <br />
                                    expand your business across the Asia
                                    Pacific.
                                </p>
                            </div>
                            <a href="{{ route('services') }}" class="btn btn__danger btn--round">
                                View Service
                            </a>
                        </div>
                    </div>

                    <div class="grid__growth--item">
                        <div class="growth__line">
                            <img src="{{ asset('assets/img/growth-map-latest-2.webp') }}" alt="map" />
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>

    <section id="news" class="news">
        <div class="container">
            <div class="section__content">
                <div class="section__heading text-center text__lg">
                    <h2>
                        News
                        <a href="{{ route('news') }}" class="btn btn__danger--alt btn--lg">Read our latest news</a>
                    </h2>
                </div>

                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4">
                        @foreach ($news as $item)
                            <div class="col">
                                <div class="card news__card">
                                    <div class="card__img">
                                        <picture>
                                            <source media="(max-width: 600px)"
                                                srcset="{{ Storage::disk('s3')->url($item->image_small) }}">
                                            <source media="(max-width: 1200px)"
                                                srcset="{{ Storage::disk('s3')->url($item->image_medium) }}">
                                            <img src="{{ Storage::disk('s3')->url($item->image_original) }}"
                                                class="card-img-top" alt="{{ $item->title }}">
                                        </picture>
                                        <div class="wrapper__absolute">
                                            <span class="cta cta__light">
                                                View More
                                                <svg class="icon" aria-hidden="true">
                                                    <use xlink:href="#svg-arrow-right"></use>
                                                </svg>
                                            </span>
                                        </div>
                                    </div>
                                    <div class="card-body">
                                        <span class="card__date">
                                            {{ \Carbon\Carbon::parse($item->created_at)->format('d F Y') }}
                                        </span>
                                        <h3 class="card-title">
                                            {{ $item->title }}
                                        </h3>
                                        <a href="/news/{{ $item->slug . '/' . $item->title_slug }}"
                                            class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
            </div>
        </div>
    </section>
</div>
