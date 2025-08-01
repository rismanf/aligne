<div>
    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-3">
                    <div class="services-list">
                        <a href="{{ route('user.profile') }}">Profile</a>
                        <a href="{{ route('user.booking') }}">My Booking</a>
                        <a href="{{ route('user.order') }}" class="active">My Order</a>
                        <a href="{{ route('logout') }}">Logout</a>
                    </div>
                </div>

                <div class="col-lg-9">
                    @if (session()->has('success'))
                        <div class="alert alert-success mb-4">{{ session('success') }}</div>
                    @endif
                    @if (session()->has('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif
                    @if ($errors->any())
                        <div class="alert alert-danger">
                            <strong>Whoops! Ada beberapa masalah:</strong>
                            <ul>
                                @foreach ($errors->all() as $error)
                                    <li>{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif
                    
                    <h3>My Membership Orders</h3>
                    
                    @if($orders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-striped">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Invoice ID</th>
                                        <th>Membership Package</th>
                                        <th>Price</th>
                                        <th>Payment Status</th>
                                        <th>Membership Status</th>
                                        <th>Date</th>
                                        <th>Expires</th>
                                        <th>Actions</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($orders as $order)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>
                                                <a href="{{ route('invoice', $order->invoice_number) }}" class="text-primary">
                                                    {{ $order->invoice_number }}
                                                </a>
                                            </td>
                                            <td>
                                                <strong>{{ $order->membership->name }}</strong>
                                                @if($order->membership->description)
                                                    <br><small class="text-muted">{{ $order->membership->description }}</small>
                                                @endif
                                            </td>
                                            <td>IDR {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                            <td>
                                                <span class="badge badge-{{ $order->payment_status === 'paid' ? 'success' : ($order->payment_status === 'pending' ? 'warning' : 'danger') }}">
                                                    {{ ucfirst($order->payment_status) }}
                                                </span>
                                            </td>
                                            <td>
                                                <span class="badge badge-{{ $order->status === 'active' ? 'success' : ($order->status === 'pending' ? 'warning' : 'secondary') }}">
                                                    {{ ucfirst($order->status) }}
                                                </span>
                                            </td>
                                            <td>{{ $order->created_at->format('d M Y') }}</td>
                                            <td>
                                                @if($order->expires_at)
                                                    {{ $order->expires_at->format('d M Y') }}
                                                    @if($order->expires_at->isPast())
                                                        <br><small class="text-danger">Expired</small>
                                                    @endif
                                                @else
                                                    <span class="text-muted">-</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if ($order->payment_status == 'unpaid')
                                                    <button
                                                        wire:click="showModal({{ $order->id }}, '{{ $order->invoice_number }}')"
                                                        class="btn btn-primary btn-sm">
                                                        Pay Now
                                                    </button>
                                                @elseif($order->payment_status == 'pending')
                                                    <span class="text-warning">
                                                        <i class="bi bi-clock"></i> Waiting Confirmation
                                                    </span>
                                                @elseif($order->payment_status == 'paid')
                                                    <a href="{{ route('classes') }}" class="btn btn-success btn-sm">
                                                        Book Classes
                                                    </a>
                                                @endif
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5">
                            <div class="mb-3">
                                <i class="bi bi-cart-x" style="font-size: 3rem; color: #ccc;"></i>
                            </div>
                            <h5>No Orders Found</h5>
                            <p class="text-muted">You haven't made any membership purchases yet.</p>
                            <a href="{{ route('membership') }}" class="btn btn-primary">Browse Memberships</a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section>

    <!-- Payment Modal -->
    <div x-data="{ showModal: false }" x-on:open-payment-modal.window="showModal = true">
        <div class="modal fade" id="paymentModal" tabindex="-1" aria-hidden="true" x-show="showModal"
            x-on:keydown.escape.window="showModal = false" x-init="() => {
                let modal = new bootstrap.Modal($el);
                $watch('showModal', value => {
                    if (value) {
                        modal.show();
                    } else {
                        modal.hide();
                    }
                });
            }" style="display: none;" wire:ignore.self>
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit="save">
                        <input type="hidden" wire:model="order_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Upload Payment Proof</h5>
                            <button type="button" class="btn-close" @click="showModal = false"></button>
                        </div>
                        <div class="modal-body">
                            <div class="mb-3">
                                <label class="form-label">Invoice Number</label>
                                <input type="text" class="form-control" value="{{ $invoice_number }}" readonly>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Bank Transfer Method</label>
                                <input type="text" class="form-control" wire:model="bank" name="bank" 
                                       placeholder="e.g., BCA, Mandiri, BNI">
                                @error('bank') <span class="text-danger">{{ $message }}</span> @enderror
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Proof (Image)</label>
                                <input type="file" class="form-control" wire:model.lazy="payment_proof"
                                    name="payment_proof" accept="image/*">
                                @error('payment_proof') <span class="text-danger">{{ $message }}</span> @enderror
                                <small class="text-muted">Max file size: 5MB. Accepted formats: JPG, PNG, GIF</small>
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="button" class="btn btn-secondary" @click="showModal = false">Cancel</button>
                            <button type="submit" class="btn btn-primary">Upload Payment Proof</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            window.addEventListener('reload-page', () => {
                location.reload();
            });
        </script>
    @endpush

    <!-- Additional Styles -->
    <style>
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

        .badge-secondary {
            background-color: #6c757d;
            color: white;
        }

        .badge {
            padding: 4px 8px;
            border-radius: 4px;
            font-size: 12px;
            font-weight: bold;
        }

        .services-list a {
            display: block;
            padding: 12px 18px;
            margin-bottom: 8px;
            background: #f8f9fa;
            color: #333;
            text-decoration: none;
            border-radius: 12px;
            transition: all 0.3s ease;
            font-weight: 500;
            border: 1px solid #e9ecef;
        }

        .services-list a:hover,
        .services-list a.active {
            background: #4b2e2e !important;
            color: white !important;
            transform: translateY(-2px);
            box-shadow: 0 4px 12px rgba(75, 46, 46, 0.3);
            border-color: #4b2e2e;
        }

        .services-list {
            background: white;
            padding: 20px;
            border-radius: 15px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            border: 1px solid #e9ecef;
        }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
        }
    </style>
</div>
