<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

        <div class="container">

            <div class="row gy-4 justify-content-center">
                @foreach ($products as $product)
                    <div class="col-lg-3" data-aos="zoom-in" data-aos-delay="100">
                        <div class="pricing-item">
                            <h3>{{ $product->name }}</h3>
                            <p class="description">{{ $product->description }}</p>
                            <h4><sup>IDR</sup>{{ number_format($product->price, 0, ',', '.') }}</h4>
                          
                            @foreach ($product->classes as $val )
                                      <span> / {{$val->name .' : '. $val->pivot->quota }}</span><br>
                            @endforeach
                      
                            <a href="{{ route('checkout', $product->id) }}" class="cta-btn">Select</a>                            
                        </div>
                    </div><!-- End Pricing Item -->
                @endforeach


            </div>

        </div>

    </section><!-- /Pricing Section -->
</div>
