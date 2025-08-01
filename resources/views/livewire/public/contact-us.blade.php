<div>
    <!-- Contact Section -->
    <section id="contact" class="contact section">

        <div class="mb-5">
            <iframe style="width: 100%; height: 400px;"
                src="https://www.google.com/maps/embed?pb=!1m14!1m8!1m3!1d12097.433213460943!2d-74.0062269!3d40.7101282!3m2!1i1024!2i768!4f13.1!3m3!1m2!1s0x0%3A0xb89d1fe6bc499443!2sDowntown+Conference+Center!5e0!3m2!1smk!2sbg!4v1539943755621"
                frameborder="0" allowfullscreen=""></iframe>
        </div><!-- End Google Maps -->

        <div class="container">

            <div class="row gy-5 gx-lg-5">

                <div class="col-lg-4">

                    <div class="info">
                        <h3>Get in touch</h3>
                        <p>Et id eius voluptates atque nihil voluptatem enim in tempore minima sit ad mollitia commodi
                            minus.</p>

                        <div class="info-item d-flex">
                            <i class="bi bi-geo-alt flex-shrink-0"></i>
                            <div>
                                <h4>Location:</h4>
                                <p>SCDB, Tower, Level, Jakarta</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex">
                            <i class="bi bi-envelope flex-shrink-0"></i>
                            <div>
                                <h4>Email:</h4>
                                <p>info@alignestudio.id</p>
                            </div>
                        </div><!-- End Info Item -->

                        <div class="info-item d-flex">
                            <i class="bi bi-phone flex-shrink-0"></i>
                            <div>
                                <h4>Call:</h4>
                                <p>+62</p>
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
