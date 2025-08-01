<div>
    <!-- Pricing Section -->
    <section id="pricing" class="pricing section">
        <div class="container">
            <div class="row gy-4 justify-content-center text-center">
                @if (session()->has('success'))
                    <div class="alert alert-success mb-4">{{ session('success') }}</div>
                @endif
                
                <div class="col-lg-8">
                    <div class="pricing-item">
                        @if (!empty($invoice))
                            <!-- Invoice Header -->
                            <div class="invoice-header mb-4">
                                <h3>Invoice #{{ $invoice->invoice_number }}</h3>
                                <p class="text-muted">Thank you for your membership purchase!</p>
                            </div>

                            <!-- Invoice Details -->
                            <div class="border p-4 mb-4 text-left">
                                <div class="row mb-3">
                                    <div class="col-6">
                                        <strong>Customer:</strong><br>
                                        {{ $invoice->user->name }}<br>
                                        {{ $invoice->user->email }}
                                    </div>
                                    <div class="col-6 text-right">
                                        <strong>Invoice Date:</strong><br>
                                        {{ $invoice->created_at->format('M d, Y') }}<br>
                                        <span class="badge badge-{{ $invoice->payment_status === 'paid' ? 'success' : ($invoice->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                            {{ ucfirst($invoice->payment_status) }}
                                        </span>
                                    </div>
                                </div>

                                <hr>

                                <div class="d-flex justify-content-between mt-3">
                                    <span>Membership Package:</span>
                                    <strong>{{ $invoice->membership->name }}</strong>
                                </div>
                                
                                @if($invoice->membership->description)
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Description:</span>
                                        <span class="text-muted">{{ $invoice->membership->description }}</span>
                                    </div>
                                @endif

                                <div class="d-flex justify-content-between mt-3">
                                    <span>Package Price:</span>
                                    <span>IDR {{ number_format($invoice->price, 0, ',', '.') }}</span>
                                </div>

                                @if($invoice->unique_code)
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Unique Code:</span>
                                        <span>IDR {{ number_format($invoice->unique_code, 0, ',', '.') }}</span>
                                    </div>
                                @endif

                                <hr>

                                <div class="d-flex justify-content-between mt-3">
                                    <strong>Total Amount:</strong>
                                    <strong>IDR {{ number_format($invoice->total_price, 0, ',', '.') }}</strong>
                                </div>

                                <div class="d-flex justify-content-between mt-3">
                                    <span>Validity Period:</span>
                                    @if ($invoice->membership->valid_until == 0 || $invoice->membership->valid_until == null)
                                        <strong>Lifetime</strong>
                                    @else
                                        <strong>{{ $invoice->membership->valid_until }} days</strong>
                                    @endif
                                </div>

                                @if($invoice->expires_at)
                                    <div class="d-flex justify-content-between mt-2">
                                        <span>Expires On:</span>
                                        <strong>{{ $invoice->expires_at->format('M d, Y') }}</strong>
                                    </div>
                                @endif
                            </div>

                            <!-- Included Classes -->
                            @if($invoice->membership->classes && $invoice->membership->classes->count() > 0)
                                <div class="border p-4 mb-4 text-left">
                                    <h5 class="mb-3">Included Classes:</h5>
                                    <div class="row">
                                        @foreach($invoice->membership->groupClasses as $class)
                                            <div class="col-md-6 mb-2">
                                                <div class="d-flex justify-content-between">
                                                    <span>{{ $class->name }}</span>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                            @endif

                            <!-- Payment Status & Actions -->
                            <div class="p-3">
                                @if($invoice->payment_status === 'paid')
                                    <div class="alert alert-success">
                                        <strong>Payment Confirmed!</strong> Your membership is now active.
                                    </div>
                                    <a href="{{ route('user.dashboard') }}" class="cta-btn">Go to Dashboard</a>
                                @elseif($invoice->payment_status === 'pending')
                                    <div class="alert alert-warning">
                                        <strong>Payment Pending</strong> Please wait for admin confirmation.
                                    </div>
                                    <a href="{{ route('user.order') }}" class="cta-btn">Check My Orders</a>
                                @else
                                    <div class="alert alert-info">
                                        <strong>Payment Required</strong> Please complete your payment to activate membership.
                                    </div>
                                    <a href="{{ route('user.order') }}" class="cta-btn">View My Orders</a>
                                @endif
                            </div>
                        @else
                            <div class="text-center py-5">
                                <h5>Invoice Not Found</h5>
                                <p>The requested invoice could not be found or you don't have permission to view it.</p>
                                <a href="{{ route('membership') }}" class="cta-btn">Browse Memberships</a>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
        </div>
    </section>

    <!-- Additional Styles -->
    <style>
        .invoice-header {
            text-align: center;
            padding: 20px 0;
            border-bottom: 2px solid #f0f0f0;
        }

        .badge-success {
            background-color: #28a745;
            color: white;
        }

        .badge-warning {
            background-color: #ffc107;
            color: #000;
        }

        .badge-danger {
            background-color: #dc3545;
            color: white;
        }

        .badge-primary {
            background-color: #007bff;
            color: white;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .cta-btn {
            display: inline-block;
            padding: 12px 30px;
            background: #007bff;
            color: white;
            text-decoration: none;
            border-radius: 25px;
            font-weight: bold;
            transition: all 0.3s ease;
        }

        .cta-btn:hover {
            background: #0056b3;
            transform: translateY(-2px);
            color: white;
            text-decoration: none;
        }
    </style>
</div>
