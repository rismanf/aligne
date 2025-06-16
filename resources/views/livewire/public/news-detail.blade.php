<div>
    <section id="news" class="news">
        <div class="container">
            <div class="section__content">
                <div class="section__heading text--danger">
                    <h1>
                        {{ $data_news->title }}
                    </h1>
                    <figcaption class="figure-caption">
                        {{ \Carbon\Carbon::parse($data_news->created_at)->format('d F Y') }}
                        |<span class="news__author">

                            {{ $data_news->author }}
                        </span>
                    </figcaption>
                </div>

                <div class="news__row row g-4">
                    <div class="col-12 news__content">
                        {!! $data_news->body !!}

                        <ul role="list" class="news__content--tag">
                            <li>
                                <span> Tags: </span>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                            <li>
                                <a href="#" class="cta cta__secondary">
                                    #hashtag
                                </a>
                            </li>
                        </ul>
                        <ul role="list" class="news__content--share">
                            <li>
                                <span> Share </span>
                            </li>
                            <li>
                                <button class="btn btn__share btn--lg" data-sharer="x"
                                    data-title="Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC Provides AI Technology Training for SMEs in Mandalika"
                                    data-hashtags="NeutraDCnews"
                                    data-url="https://neutradc.internalpreview.com/news/news-1/">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#svg-twitter"></use>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button class="btn btn__share btn--lg" data-sharer="facebook"
                                    data-hashtag="NeutraDC, NeutraDCnews"
                                    data-url="https://neutradc.internalpreview.com/news/news-1/">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#svg-facebook"></use>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button class="btn btn__share btn--lg" data-sharer="whatsapp"
                                    data-title="Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC Provides AI Technology Training for SMEs in Mandalika"
                                    data-url="https://neutradc.internalpreview.com/news/news-1/">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#svg-whatsapp"></use>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button class="btn btn__share btn--lg" data-sharer="linkedin"
                                    data-url="https://neutradc.internalpreview.com/news/news-1/">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#svg-linkedin-alt"></use>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button class="btn btn__share btn--lg" data-sharer="email"
                                    data-title="Supporting Local Entrepreneurs to Go Global, Telkom Through NeutraDC Provides AI Technology Training for SMEs in Mandalika"
                                    data-url="https://neutradc.internalpreview.com/news/news-1/"
                                    data-subject="Neutra DC News">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#svg-email"></use>
                                    </svg>
                                </button>
                            </li>
                            <li>
                                <button class="btn btn__share btn--lg url__copy"
                                    data-url="https://neutradc.internalpreview.com/news/news-1/"
                                    data-bs-toggle="tooltip" data-bs-original-title="Copy Url"
                                    data-bs-placement="right">
                                    <svg class="icon" aria-hidden="true">
                                        <use xlink:href="#svg-link"></use>
                                    </svg>
                                </button>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="news__related">
                    <h3>Related Articles</h3>
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
        </div>
    </section>
</div>
