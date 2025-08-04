<div>
    <section id="hero" class="hero section dark-background">

        <div id="hero-carousel" class="carousel slide carousel-fade" data-bs-ride="carousel" data-bs-interval="5000">

            <div class="carousel-item active">
                <img src="{{ asset('assets/img/hero.jpg') }}" alt="">
                <div class="container col-lg-4 col-auto ">
                    <h2>Discover Your Strongest Self</h2>
                    <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit, sed do eiusmod tempor incididunt ut
                        labore et dolore magna aliqua. Ut enim ad minim veniam, quis nostrud exercitation ullamco
                        laboris nisi ut aliquip ex ea commodo consequat.</p>
                    <a href="{{ route('classes') }}" class="btn-get-started rounded-pill">Class</a>
                    <a href="{{ route('membership') }}" class="btn-get-started rounded-pill">Membership</a>
                </div>
            </div><!-- End Carousel Item -->



        </div>

    </section><!-- /Hero Section -->

    <!-- About Section -->
    <section id="about" class="about section">

        <div class="container">

            <div class="row position-relative">

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <h2 class="inner-title">Welcome to Aligné Studio</h2>
                    <p class="subtitle">— your sanctuary of wellness in the heart of SCBD Jakarta</p>
                    <div class="our-story">
                        <p>Nestled amidst the vibrant pulse of the city, our premium studio offers a tranquil escape for those seeking to nurture their body and soul.</p>
                        
                        <p>At Aligné Studio, we believe in harmony and holistic well-being. Explore our diverse range of classes, including Pilates, aerial yoga, yoga, barre, and dance — each designed to elevate your physical strength, flexibility, and inner peace. Our devoted team of professional instructors certified is here to guide you on your personal journey, whether you're a beginner or an experienced enthusiast.</p>

                        <p>Escape the chaos of urban life and discover a warm, inviting space where balance and wellness come together. Join us and experience the transformative power of movement at Aligné Studio — a place where your body and spirit align.</p>
                    </div>
                </div>

                <div class="col-lg-6 about-img" data-aos="zoom-out" data-aos-delay="200">
                    <img src="assets/img/home/image2.webp" alt="Woman using Pilates reformer at Aligné Studio">
                </div>

            </div>

        </div>

    </section><!-- /About Section -->


    <!-- Blog Posts Section -->
    <section id="blog-posts" class="blog-posts section">

        <div class="container">

            <div class="section-header" data-aos="fade-up">
                <h2>Class</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
            </div>
            <div class="row gy-4">

                @foreach ($classes as $class)
                    <div class="col-lg-3">
                        <article class="position-relative h-100">

                            <div class="post-img position-relative overflow-hidden">
                                <img src="{{ asset('assets/img/reformerpilatesstudioadelaide.jpg') }}" class="img-fluid"
                                    alt="">
                            </div>

                            <div class="post-content d-flex flex-column">

                                <h3 class="post-title">{{ $class->name }}</h3>

                                <p>
                                    {!! $class->description !!}
                                </p>

                                <a href="{{ route('detail-class', ['id' => $class->id, 'date' => date('Y-m-d')]) }}"
                                    class="readmore stretched-link"></a>

                            </div>

                        </article>
                    </div><!-- End post list item -->
                @endforeach
            </div>
        </div>

    </section><!-- /Blog Posts Section -->



    <!-- Testimonials Section -->
    {{-- <section id="testimonials" class="testimonials section">

        <!-- Section Title -->
        <div class="container section-title" data-aos="fade-up">
            <h2>Testimonials</h2>
            <p>Real Story. Real Results.</p>
            <p>Hear from our members who've found balance, strength, and confidence through Aligné. Every journey is
                unique - and we celebrate each one</p>
        </div><!-- End Section Title -->

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="100">
                    <div class="testimonial-item rounded-5">
                        <h3>Saul Goodman</h3>
                        <h4>Ceo &amp; Founder</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            <span>Proin iaculis purus consequat sem cure digni ssim donec porttitora entum suscipit
                                rhoncus. Accusantium quam, ultricies eget id, aliquam eget nibh et. Maecen aliquam,
                                risus at semper.</span>
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="200">
                    <div class="testimonial-item rounded-5">
                        <h3>Sara Wilsson</h3>
                        <h4>Designer</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            <span>Export tempor illum tamen malis malis eram quae irure esse labore quem cillum quid
                                cillum eram malis quorum velit fore eram velit sunt aliqua noster fugiat irure amet
                                legam anim culpa.</span>
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="300">
                    <div class="testimonial-item rounded-5">
                        <h3>Jena Karlis</h3>
                        <h4>Store Owner</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            <span>Enim nisi quem export duis labore cillum quae magna enim sint quorum nulla quem veniam
                                duis minim tempor labore quem eram duis noster aute amet eram fore quis sint
                                minim.</span>
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->

                <div class="col-lg-6" data-aos="fade-up" data-aos-delay="400">
                    <div class="testimonial-item rounded-5">
                        <h3>Matt Brandon</h3>
                        <h4>Freelancer</h4>
                        <div class="stars">
                            <i class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i><i class="bi bi-star-fill"></i><i
                                class="bi bi-star-fill"></i>
                        </div>
                        <p>
                            <i class="bi bi-quote quote-icon-left"></i>
                            <span>Fugiat enim eram quae cillum dolore dolor amet nulla culpa multos export minim fugiat
                                minim velit minim dolor enim duis veniam ipsum anim magna sunt elit fore quem dolore
                                labore illum veniam.</span>
                            <i class="bi bi-quote quote-icon-right"></i>
                        </p>
                    </div>
                </div><!-- End testimonial item -->
            </div>

        </div>

    </section><!-- /Testimonials Section --> --}}
</div>
