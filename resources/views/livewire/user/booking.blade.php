<div>
    <!-- Service Details Section -->
    <section id="service-details" class="service-details section">

        <div class="container">

            <div class="row gy-4">

                <div class="col-lg-3">
                    <div class="services-list">
                        <a href="{{ route('user.profile') }}">Profile</a>
                        <a href="{{ route('user.booking') }}" class="active">My Booking</a>
                        <a href="{{ route('user.order') }}">My Order</a>
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
                    <h3>My Booking Details</h3>
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Date</th>
                                    <th>Time</th>
                                    <th>Class</th>
                                    <th>Level</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($userschedule as $order)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $order->schedule->schedule_date }}</td>
                                        <td>{{ $order->schedule->time }}</td>
                                        <td>{{ $order->schedule->classes->name }}</td>
                                        <td>{{ $order->schedule->level_class }}</td>
                                        <td>
                                            <img src="{{ $order->url_code }}" alt="" width="100px"
                                                height="100px">
                                            @php
                                                $scheduleDateTime = \Carbon\Carbon::parse(
                                                    $order->schedule->schedule_date . ' ' . $order->schedule->time,
                                                );
                                                $now = \Carbon\Carbon::now();
                                                $diffInHours = $now->diffInHours($scheduleDateTime, false); // false untuk bisa hasil negatif
                                            @endphp

                                            @if ($diffInHours > 0 && $diffInHours <= 12)
                                                <a wire:click="showModal({{ $order->id }})"
                                                    class="btn btn-primary ml-2">Cancel</a>
                                            @endif
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
