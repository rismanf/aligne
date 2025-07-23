<div>
    <section id="news" class="news">
        <div class="container">
            <div class="section__content">
                <div class="row">
                    <div class="col-12">
                        <div class="card news__card news__card--lg">
                            <div class="row g-4">
                                <div class="col-md-6">
                                    <div class="card__img">
                                        <picture>
                                            <source media="(max-width: 600px)"
                                                srcset="{{ Storage::disk('s3')->url($news->first()->image_small) }}">
                                            <source media="(max-width: 1200px)"
                                                srcset="{{ Storage::disk('s3')->url($news->first()->image_medium) }}">
                                            <img src="{{ Storage::disk('s3')->url($news->first()->image_original) }}"
                                                class="card-img-top" alt="{{ $news->first()->title }}">
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
                                </div>
                                <div class="col-md-6">
                                    <div class="card-body">
                                        <span class="card__date">
                                            {{ \Carbon\Carbon::parse($news->first()->created_at)->format('d F Y') }}
                                        </span>
                                        <h3 class="card-title">
                                            {{ $news->first()->title }}
                                        </h3>
                                        <a href="/news/{{ $news->first()->slug . '/' . $news->first()->title_slug }}"
                                            class="stretched-link"></a>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="row row-cols-1 row-cols-md-2 row-cols-lg-3 g-4 row--space">
                    @foreach ($news as $value)
                        <div class="col">
                            <div class="card news__card">
                                <div class="card__img">
                                    <picture>
                                        <source media="(max-width: 600px)"
                                            srcset="{{ Storage::disk('s3')->url($value->image_small) }}">
                                        <source media="(max-width: 1200px)"
                                            srcset="{{ Storage::disk('s3')->url($value->image_medium) }}">
                                        <img src="{{ Storage::disk('s3')->url($value->image_original) }}"
                                            class="card-img-top" alt="{{ $value->title }}">
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
                                        {{ \Carbon\Carbon::parse($value->created_at)->format('d F Y') }}
                                    </span>
                                    <h3 class="card-title">
                                        {{ $value->title }}
                                    </h3>
                                    <a href="/news/{{ $value->slug . '/' . $value->title_slug }}"
                                        class="stretched-link"></a>
                                </div>
                            </div>

                        </div>
                    @endforeach
                </div>

                {{ $news->links('livewire.public.news-pagination') }}

            </div>
        </div>
    </section>
</div>
