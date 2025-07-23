<div>
    <!-- Blog Posts Section -->
    <section id="blog-posts" class="blog-posts section">

        <div class="container">

            <div class="section-title" data-aos="fade-up">
                <h2>Class</h2>
                <p>Lorem ipsum dolor sit amet, consectetur adipiscing elit</p>
            </div>
            <div class="row gy-4  justify-content-center">
                @foreach ($classes as $class)
                    <div class="col-lg-4">
                        <article class="position-relative h-100">

                            <div class="post-img position-relative overflow-hidden">
                                <img src="{{ asset('storage/' . $class->image_original) }}" class="img-fluid" alt="">
                            </div>

                            <div class="post-content d-flex flex-column">

                                <h3 class="post-title">{{ $class->name }}</h3>

                                <p>
                                    {!! $class->description !!}
                                </p>

                                <hr>

                                <a href="{{ route('detail-class', ['id' => $class->id, 'date' => date('Y-m-d')]) }}"
                                    class="readmore stretched-link"><span>Select</span><i
                                        class="bi bi-arrow-right"></i></a>

                            </div>

                        </article>
                    </div><!-- End post list item -->
                @endforeach
            </div>
        </div>

    </section><!-- /Blog Posts Section -->
</div>
