<div>
    <!-- Contact Section -->
    <section id="contact" class="contact section">



        <div class="container" style="margin-top: 20px">
            <div class="section-header" data-aos="fade-up">
                <h2>Contact Us</h2>
            </div>

            <div class="row gy-5 gx-lg-5">

                <div class="col-lg-4">

                    <div class="info">
                        <h3>Get in touch</h3>
                        <p>Nestled amidst the vibrant pulse of the city, our premium studio offers a tranquil escape for those seeking to nurture their body and soul.</p>

                        <div class="info-item d-flex">
                            <a href="https://maps.app.goo.gl/LqvFveWeEfxpXuyk8" target="_blank"><i
                                    class="bi bi-geo-alt flex-shrink-0"></i></a>
                            <div>
                                <h4>Location:</h4>
                                <p>Kawasan SCBD, Jl. Jend. Sudirman kav 52-53 Senayan, Kec. Kby.
                                    Baru, Kota Jakarta Selatan, Daerah Khusus Ibukota Jakarta 12190</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex">
                            <a href="mailto:info@alignestudio.id"><i class="bi bi-envelope flex-shrink-0"></i></a>
                            <div>
                                <h4>Email:</h4>
                                <p>info@alignestudio.id</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex">
                            <a href="https://wa.me/+6282299294018"><i class="bi bi-phone flex-shrink-0"></i></a>
                            <div>
                                <h4>Call:</h4>
                                <p>+62 822-9929-4018</p>
                            </div>
                        </div><!-- End Info Item -->

                    </div>

                </div>

                <div class="col-lg-8">
                    <form wire:submit="save" role="form" class="php-email-form">
                        <div class="row">
                            <div class="col-md-6 form-group">
                                <input type="text" name="name" wire:model="name" class="form-control"
                                    id="name" placeholder="Your Name" required="">
                            </div>
                            <div class="col-md-6 form-group mt-3 mt-md-0">
                                <input type="email" wire:model="email" class="form-control" name="email"
                                    id="email" placeholder="Your Email" required="">
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <input type="text" class="form-control" name="subject" wire:model="subject"
                                id="subject" placeholder="Subject" required="">
                        </div>
                        <div class="form-group mt-3">
                            <textarea class="form-control" name="message" wire:model="message" placeholder="Message" required=""></textarea>
                        </div>
                        @if (session()->has('success'))
                            <div class="alert alert-success mb-4">{{ session('success') }}</div>
                        @endif
                        <div class="text-center"><button type="submit">Send Message</button></div>
                    </form>
                </div><!-- End Contact Form -->

            </div>

        </div>

    </section><!-- /Contact Section -->
</div>
