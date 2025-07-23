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
                        @if (!empty($invoice))
                            <div class="border p-3 mb-3">
                                <h5>Invoice #{{ $invoice->invoice_number }}</h5>
                                <div class="d-flex justify-content-between mt-3">
                                    <span>Paket membership</span>
                                    <strong>{{ $invoice->product->name }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <span>Total</span>
                                    <strong>IDR {{ number_format($invoice->total_price, 0, ',', '.') }}</strong>
                                </div>
                                <div class="d-flex justify-content-between mt-3">
                                    <span>Valid Until</span>
                                    @if ($invoice->product->valid_until == 0 || $invoice->product->valid_until == null)
                                        <strong>one time</strong>
                                    @else
                                        <strong>{{ $invoice->product->valid_until }} days</strong>
                                    @endif
                                </div>
                            </div>
                            <div class="p-3">

                                <a href="{{ route('user.order') }}" class="cta-btn">Cek My Invoice</a>

                            </div>
                        @else
                            <h5>Shomething went wrong</h5>
                        @endif



                    </div>
                </div><!-- End Pricing Item -->

            </div>

        </div>

    </section><!-- /Pricing Section -->

</div>
