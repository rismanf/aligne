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
                    <h3>My Order Details</h3>
                    <table class="table table-striped">
                        <thead>
                            <tr>
                                <th>Invoice ID</th>
                                <th>Product Name</th>
                                <th>Price</th>
                                <th>Status</th>
                                <th>Date</th>
                                <th>Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($orders as $order)
                                <tr>
                                    <td>{{ $order->invoice_number }}</td>
                                    <td>{{ $order->product->name }}</td>
                                    <td>IDR {{ number_format($order->total_price, 0, ',', '.') }}</td>
                                    <td>{{ $order->payment_status }}</td>
                                    <td>{{ $order->created_at->format('d-m-Y') }}</td>
                                    <td>
                                        @if ($order->payment_status == 'unpaid')
                                            <button
                                                wire:click="showModal({{ $order->id }}, '{{ $order->invoice_number }}')"
                                                class="btn btn-primary">
                                                Pay Now
                                            </button>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

            </div>

        </div>

    </section><!-- /Service Details Section -->

    <div x-data="{ showModal: false }" x-on:open-payment-modal.window="showModal = true">

        <!-- Modal -->
        <div class="modal fade" id="exampleModal" tabindex="-1" aria-hidden="true" x-show="showModal"
            x-on:keydown.escape.window="showModal = false" x-init="() => {
                let modal = new bootstrap.Modal($el);
                $watch('showModal', value => {
                    if (value) {
                        modal.show();
                    } else {
                        modal.hide();
                    }
                });
            }" style="display: none;">
            <div class="modal-dialog">
                <div class="modal-content">
                    <form wire:submit="save">
                        <input type="hidden" wire:model="order_id">
                        <div class="modal-header">
                            <h5 class="modal-title">Payment for {{ $invoice_number }}</h5>
                            <button type="button" class="btn-close" @click="showModal = false"></button>
                        </div>
                        <div class="modal-body">
                            <!-- Isi form -->
                            <div class="mb-3">
                                <label class="form-label">Transfer with bank</label>
                                <input type="text" class="form-control" wire:model="bank" name="bank">
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Payment Proof</label>
                                <input type="file" class="form-control" wire:model="payment_proof"
                                    name="payment_proof">
                            </div>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary">Confirm</button>
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
</div>
