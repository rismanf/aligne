<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <h1 class="text-3xl font-bold text-center mb-8">Class Schedules</h1>

        <!-- Filters -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-8">
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <!-- Date Filter -->
                <div>
                    <label for="selectedDate" class="block text-sm font-medium text-gray-700 mb-2">Select Date</label>
                    <input type="date" 
                           id="selectedDate" 
                           wire:model.live="selectedDate"
                           class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                </div>

                <!-- Group Class Filter -->
                <div>
                    <label for="selectedGroupClass" class="block text-sm font-medium text-gray-700 mb-2">Filter by Class Type</label>
                    <select id="selectedGroupClass" 
                            wire:model.live="selectedGroupClass"
                            class="w-full px-3 py-2 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                        <option value="">All Classes</option>
                        @foreach($groupClasses as $groupClass)
                            <option value="{{ $groupClass->name }}">{{ $groupClass->name }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
        </div>

        <!-- Flash Messages -->
        @if (session()->has('success'))
            <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded mb-4">
                {{ session('success') }}
            </div>
        @endif

        @if (session()->has('error'))
            <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded mb-4">
                {{ session('error') }}
            </div>
        @endif

        <!-- Available Schedules -->
        <div class="bg-white rounded-lg shadow-md mb-8">
            <div class="px-6 py-4 border-b border-gray-200">
                <h2 class="text-xl font-semibold">Available Classes - {{ \Carbon\Carbon::parse($selectedDate)->format('l, F j, Y') }}</h2>
                <p class="text-sm text-gray-600 mt-1">
                    <i class="fas fa-info-circle"></i> 
                    Classes can be booked up to 1 hour before start time
                </p>
            </div>

            <div class="p-6">
                @if(empty($availableSchedules))
                    <div class="text-center py-8">
                        <div class="text-gray-400 mb-4">
                            <i class="fas fa-calendar-times text-4xl"></i>
                        </div>
                        <p class="text-gray-600">No classes available for the selected date.</p>
                        <p class="text-sm text-gray-500 mt-2">Try selecting a different date or check back later.</p>
                    </div>
                @else
                    <div class="grid gap-4">
                        @foreach($availableSchedules as $schedule)
                            <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition-shadow">
                                <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                    <div class="flex-1">
                                        <div class="flex items-center mb-2">
                                            <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                                @if($schedule['group_class'] == 'REFORMER CLASS') bg-blue-100 text-blue-800
                                                @elseif($schedule['group_class'] == 'CHAIR CLASS') bg-green-100 text-green-800
                                                @else bg-purple-100 text-purple-800 @endif">
                                                {{ $schedule['group_class'] }}
                                            </span>
                                            <span class="ml-2 px-2 py-1 text-xs bg-gray-100 text-gray-700 rounded-full">
                                                {{ ucfirst($schedule['level']) }}
                                            </span>
                                        </div>
                                        
                                        <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $schedule['class_name'] }}</h3>
                                        
                                        <div class="flex flex-wrap items-center text-sm text-gray-600 gap-4">
                                            <div class="flex items-center">
                                                <i class="fas fa-clock mr-1"></i>
                                                {{ $schedule['start_time'] }} - {{ $schedule['end_time'] }}
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-user-tie mr-1"></i>
                                                {{ $schedule['trainer_name'] }}
                                            </div>
                                            <div class="flex items-center">
                                                <i class="fas fa-users mr-1"></i>
                                                {{ $schedule['available_spots'] }}/{{ $schedule['max_participants'] }} spots
                                            </div>
                                        </div>
                                        
                                        <div class="mt-2 text-xs text-gray-500">
                                            <i class="fas fa-exclamation-triangle mr-1"></i>
                                            Booking closes at {{ $schedule['booking_deadline'] }}
                                        </div>
                                    </div>
                                    
                                    <div class="mt-4 md:mt-0 md:ml-4">
                                        @auth
                                            @if($schedule['can_book'] && $schedule['user_has_quota'])
                                                <button wire:click="bookClass({{ $schedule['id'] }})"
                                                        class="w-full md:w-auto px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                                                    <i class="fas fa-calendar-plus mr-1"></i>
                                                    Book Class
                                                </button>
                                            @elseif(!$schedule['user_has_quota'])
                                                <div class="text-center">
                                                    <p class="text-sm text-red-600 mb-2">No quota available</p>
                                                    <a href="{{ route('membership') }}" 
                                                       class="inline-block px-4 py-2 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors text-sm">
                                                        Buy Membership
                                                    </a>
                                                </div>
                                            @else
                                                <button disabled 
                                                        class="w-full md:w-auto px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                                                    <i class="fas fa-ban mr-1"></i>
                                                    Cannot Book
                                                </button>
                                            @endif
                                        @else
                                            <div class="text-center">
                                                <p class="text-sm text-gray-600 mb-2">Login to book</p>
                                                <a href="{{ route('login') }}" 
                                                   class="inline-block px-4 py-2 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors text-sm">
                                                    Login
                                                </a>
                                            </div>
                                        @endauth
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>

        <!-- User's Bookings -->
        @auth
            <div class="bg-white rounded-lg shadow-md">
                <div class="px-6 py-4 border-b border-gray-200">
                    <h2 class="text-xl font-semibold">My Upcoming Bookings</h2>
                    <p class="text-sm text-gray-600 mt-1">
                        <i class="fas fa-info-circle"></i> 
                        You can cancel bookings up to 12 hours before class starts
                    </p>
                </div>

                <div class="p-6">
                    @if(empty($userBookings))
                        <div class="text-center py-8">
                            <div class="text-gray-400 mb-4">
                                <i class="fas fa-calendar-check text-4xl"></i>
                            </div>
                            <p class="text-gray-600">You have no upcoming bookings.</p>
                            <p class="text-sm text-gray-500 mt-2">Book a class above to get started!</p>
                        </div>
                    @else
                        <div class="grid gap-4">
                            @foreach($userBookings as $booking)
                                <div class="border border-gray-200 rounded-lg p-4 
                                    @if($booking['can_cancel']) border-l-4 border-l-green-500 
                                    @else border-l-4 border-l-red-500 @endif">
                                    <div class="flex flex-col md:flex-row md:items-center md:justify-between">
                                        <div class="flex-1">
                                            <div class="flex items-center mb-2">
                                                <span class="inline-block px-2 py-1 text-xs font-semibold rounded-full
                                                    @if($booking['group_class'] == 'REFORMER CLASS') bg-blue-100 text-blue-800
                                                    @elseif($booking['group_class'] == 'CHAIR CLASS') bg-green-100 text-green-800
                                                    @else bg-purple-100 text-purple-800 @endif">
                                                    {{ $booking['group_class'] }}
                                                </span>
                                                <span class="ml-2 px-2 py-1 text-xs 
                                                    @if($booking['can_cancel']) bg-green-100 text-green-700
                                                    @else bg-red-100 text-red-700 @endif rounded-full">
                                                    @if($booking['can_cancel']) Can Cancel @else Cannot Cancel @endif
                                                </span>
                                            </div>
                                            
                                            <h3 class="text-lg font-semibold text-gray-900 mb-1">{{ $booking['class_name'] }}</h3>
                                            
                                            <div class="flex flex-wrap items-center text-sm text-gray-600 gap-4">
                                                <div class="flex items-center">
                                                    <i class="fas fa-calendar mr-1"></i>
                                                    {{ \Carbon\Carbon::parse($booking['date'])->format('l, M j, Y') }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-clock mr-1"></i>
                                                    {{ $booking['start_time'] }} - {{ $booking['end_time'] }}
                                                </div>
                                                <div class="flex items-center">
                                                    <i class="fas fa-user-tie mr-1"></i>
                                                    {{ $booking['trainer_name'] }}
                                                </div>
                                            </div>
                                            
                                            <div class="mt-2 text-xs text-gray-500">
                                                <i class="fas fa-hourglass-half mr-1"></i>
                                                Class starts in {{ $booking['time_until_class'] }}
                                            </div>
                                        </div>
                                        
                                        <div class="mt-4 md:mt-0 md:ml-4">
                                            @if($booking['can_cancel'])
                                                <button wire:click="cancelBooking({{ $booking['id'] }})"
                                                        onclick="return confirm('Are you sure you want to cancel this booking?')"
                                                        class="w-full md:w-auto px-4 py-2 bg-red-600 text-white rounded-lg hover:bg-red-700 transition-colors">
                                                    <i class="fas fa-times mr-1"></i>
                                                    Cancel Booking
                                                </button>
                                            @else
                                                <div class="text-center">
                                                    <button disabled 
                                                            class="w-full md:w-auto px-4 py-2 bg-gray-400 text-white rounded-lg cursor-not-allowed">
                                                        <i class="fas fa-ban mr-1"></i>
                                                        Cannot Cancel
                                                    </button>
                                                    <p class="text-xs text-gray-500 mt-1">Too close to class time</p>
                                                </div>
                                            @endif
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    @endif
                </div>
            </div>
        @endauth
    </div>
</div>
