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
                    
                    @if (session()->has('error'))
                        <div class="alert alert-danger mb-4">{{ session('error') }}</div>
                    @endif

                    <h3>My Booking Details</h3>

                    <!-- Active Bookings -->
                    @if(count($activeBookings) > 0)
                        <div class="mb-5">
                            <h4 class="text-success mb-3">
                                <i class="bi bi-calendar-check"></i> Upcoming Classes
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Booking Code</th>
                                            <th>Class</th>
                                            <th>Date & Time</th>
                                            <th>Trainer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($activeBookings as $booking)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <code>{{ $booking['booking_code'] }}</code>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $booking['class_name'] }}</strong>
                                                        <br>
                                                        <small class="badge 
                                                            @if($booking['group_class'] == 'REFORMER CLASS') bg-info
                                                            @elseif($booking['group_class'] == 'CHAIR CLASS') bg-success
                                                            @else bg-warning @endif">
                                                            {{ $booking['group_class'] }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ \Carbon\Carbon::parse($booking['date'])->format('D, M j, Y') }}
                                                        <br>
                                                         <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($booking['start_time'])->format('h:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($booking['end_time'])->format('h:i A') }}
                                                        </small>                                                       
                                                    </div>
                                                </td>
                                                <td>{{ $booking['trainer_name'] }}</td>
                                                <td>
                                                    <span class="badge bg-primary">{{ ucfirst($booking['booking_status']) }}</span>
                                                </td>
                                                <td>
                                                    <div class="btn-group-vertical btn-group-sm">
                                                        <a href="{{ route('user.my-bookings', $booking['id']) }}" 
                                                           class="btn btn-outline-primary btn-sm mb-1">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        @if($booking['can_cancel'])
                                                            <button wire:click="cancelBooking({{ $booking['id'] }})"
                                                                    onclick="return confirm('Are you sure you want to cancel this booking?')"
                                                                    class="btn btn-outline-danger btn-sm">
                                                                <i class="bi bi-x-circle"></i> Cancel
                                                            </button>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- Past Bookings -->
                    @if(count($pastBookings) > 0)
                        <div class="mb-5">
                            <h4 class="text-muted mb-3">
                                <i class="bi bi-clock-history"></i> Past Classes
                            </h4>
                            <div class="table-responsive">
                                <table class="table table-striped">
                                    <thead>
                                        <tr>
                                            <th>No</th>
                                            <th>Booking Code</th>
                                            <th>Class</th>
                                            <th>Date & Time</th>
                                            <th>Trainer</th>
                                            <th>Status</th>
                                            <th>Action</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($pastBookings as $booking)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>
                                                    <code>{{ $booking['booking_code'] }}</code>
                                                </td>
                                                <td>
                                                    <div>
                                                        <strong>{{ $booking['class_name'] }}</strong>
                                                        <br>
                                                        <small class="badge 
                                                            @if($booking['group_class'] == 'REFORMER CLASS') bg-info
                                                            @elseif($booking['group_class'] == 'CHAIR CLASS') bg-success
                                                            @else bg-warning @endif">
                                                            {{ $booking['group_class'] }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>
                                                    <div>
                                                        {{ \Carbon\Carbon::parse($booking['date'])->format('D, M j, Y') }}
                                                        <br>
                                                       <small class="text-muted">
                                                            {{ \Carbon\Carbon::parse($booking['start_time'])->format('h:i A') }}
                                                            -
                                                            {{ \Carbon\Carbon::parse($booking['end_time'])->format('h:i A') }}
                                                        </small>
                                                    </div>
                                                </td>
                                                <td>{{ $booking['trainer_name'] }}</td>
                                                <td>
                                                    @if($booking['visit_status'] == 'visited')
                                                        <span class="badge bg-success">Attended</span>
                                                    @else
                                                        <span class="badge bg-secondary">{{ ucfirst($booking['booking_status']) }}</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    <div class="btn-group-vertical btn-group-sm">
                                                        <a href="{{ route('user.my-bookings', $booking['id']) }}" 
                                                           class="btn btn-outline-primary btn-sm mb-1">
                                                            <i class="bi bi-eye"></i> View
                                                        </a>
                                                        @if($booking['visit_status'] == 'visited')
                                                            <a href="{{ route('user.feedback', $booking['id']) }}" 
                                                               class="btn btn-outline-warning btn-sm">
                                                                <i class="bi bi-star"></i> Feedback
                                                            </a>
                                                        @endif
                                                    </div>
                                                </td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    @endif

                    <!-- No Bookings -->
                    @if(count($bookings) == 0)
                        <div class="text-center py-5">
                            <div class="mb-4">
                                <i class="bi bi-calendar-x display-1 text-muted"></i>
                            </div>
                            <h4>No Bookings Found</h4>
                            <p class="text-muted mb-4">You haven't made any class bookings yet.</p>
                            <a href="{{ route('classes') }}" class="btn btn-primary">
                                <i class="bi bi-plus-circle"></i> Book Your First Class
                            </a>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </section><!-- /Service Details Section -->

    <!-- Additional Styles -->
    <style>
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

        .badge {
            padding: 4px 8px;
            border-radius: 6px;
            font-size: 12px;
            font-weight: bold;
        }

        .bg-info { background-color: #17a2b8 !important; }
        .bg-success { background-color: #28a745 !important; }
        .bg-warning { background-color: #ffc107 !important; color: #000; }
        .bg-primary { background-color: #007bff !important; }
        .bg-secondary { background-color: #6c757d !important; }

        .table th {
            background-color: #f8f9fa;
            font-weight: 600;
            border-top: none;
        }

        .btn-sm {
            padding: 4px 8px;
            font-size: 12px;
            border-radius: 6px;
        }

        .table-responsive {
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 8px rgba(0, 0, 0, 0.1);
        }
    </style>
</div>
