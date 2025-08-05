<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">

        <div class="container">

            <div class="row gy-4 justify-content-center text-center ">
                @if (session()->has('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                <div class="col-lg-6">
                    <div class="pricing-item">
                        <div class="border p-3 mb-3">
                            <h5>Order summary</h5>
                            <div class="d-flex justify-content-between mt-3">
                                <strong>{{ $product->name }}</strong>
                                <strong>IDR {{ number_format($product->price, 0, ',', '.') }}</strong>

                            </div>
                            {{-- <div class=" text-muted small mt-2">
                                <p class="mb-1">Class: Until canceled</p>
                                <p class="mb-0">Sessions: {{ $product->kuota }}</p>
                            </div> --}}
                            <div class=" text-muted small mt-2 ml-2 text-left">
                                @foreach ($product->groupClasses as $class)
                                    <div class="col-md-6 mb-2">
                                        <div class="d-flex justify-content-between">
                                            <span>{{ $class->name }}</span>
                                        </div>
                                    </div>
                                @endforeach

                            </div>
                            <div class="d-flex justify-content-between mt-3">
                                <strong>Code unique</strong>

                                <strong>IDR {{ $uniqueCode }}</strong>
                            </div>

                            <hr>
                            <div class="d-flex justify-content-between">
                                <strong>Total</strong>
                                @php
                                    $total = $product->price + $uniqueCode;
                                @endphp
                                <strong>IDR {{ number_format($total, 0, ',', '.') }}</strong>
                            </div>
                        </div>
                        <div class="p-3">
                            @guest
                                <h3>Sign Up</h3>
                                <p>To purchase this plan and use its benefits in the future, log in to your account or
                                    sign
                                    up.
                                </p>
                                <a href="{{ route('login') }}" class="cta-btn">Log In</a>
                                <a href="{{ route('register') }}" class="cta-btn">Sign Up</a>
                            @else
                                <form wire:submit="save" role="form" class="php-email-form">
                                    <button type="submit" class="cta-btn">Check Out</button>
                                </form>
                            @endguest
                        </div>
                    </div>
                </div><!-- End Pricing Item -->

            </div>

        </div>

    </section><!-- /Pricing Section -->

</div>
