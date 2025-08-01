<div>

    <div class="max-w-4xl mx-auto">
        <!-- Scanner Interface -->
        <div class="bg-white rounded-lg shadow-md p-6 mb-6">
            <div class="text-center mb-6">
                <div class="inline-flex items-center justify-center w-16 h-16 bg-blue-100 rounded-full mb-4">
                    <i class="fas fa-qrcode text-2xl text-blue-600"></i>
                </div>
                <h2 class="text-xl font-semibold text-gray-900">Scan Booking QR Code</h2>
                <p class="text-gray-600 mt-2">Enter the booking code manually or scan the QR code</p>
            </div>

            <!-- Manual Input -->
            <div class="max-w-md mx-auto">
                <div class="flex gap-2">
                    <input type="text" 
                           wire:model="bookingCode" 
                           wire:keydown.enter="scanBooking"
                           placeholder="Enter booking code"
                           class="flex-1 px-4 py-3 border border-gray-300 rounded-lg focus:outline-none focus:ring-2 focus:ring-blue-500 text-center font-mono text-lg"
                           autofocus>
                    <button wire:click="scanBooking" 
                            class="px-6 py-3 bg-blue-600 text-white rounded-lg hover:bg-blue-700 transition-colors">
                        <i class="fas fa-search"></i>
                    </button>
                </div>
                
                @if($bookingCode)
                    <button wire:click="resetScan" 
                            class="w-full mt-2 px-4 py-2 text-gray-600 hover:text-gray-800 text-sm">
                        <i class="fas fa-times mr-1"></i>
                        Clear
                    </button>
                @endif
            </div>
        </div>

        <!-- Messages -->
        @if($message)
            <div class="mb-6">
                <div class="rounded-lg p-4 
                    @if($messageType === 'success') bg-green-100 border border-green-400 text-green-700
                    @elseif($messageType === 'warning') bg-yellow-100 border border-yellow-400 text-yellow-700
                    @else bg-red-100 border border-red-400 text-red-700 @endif">
                    <div class="flex items-center">
                        <i class="fas 
                            @if($messageType === 'success') fa-check-circle
                            @elseif($messageType === 'warning') fa-exclamation-triangle
                            @else fa-times-circle @endif mr-2"></i>
                        {{ $message }}
                    </div>
                </div>
            </div>
        @endif

        <!-- Scan Result -->
        @if($scanResult)
            <div class="bg-white rounded-lg shadow-md p-6 mb-6">
                <div class="text-center mb-6">
                    <div class="inline-flex items-center justify-center w-16 h-16 bg-green-100 rounded-full mb-4">
                        <i class="fas fa-user-check text-2xl text-green-600"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-900">Booking Details</h3>
                </div>

                <div class="max-w-md mx-auto space-y-4">
                    <div class="bg-gray-50 rounded-lg p-4">
                        <div class="grid grid-cols-2 gap-4 text-sm">
                            <div>
                                <span class="font-medium text-gray-600">Member:</span>
                                <div class="font-semibold text-gray-900">{{ $scanResult['user_name'] }}</div>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Booking Code:</span>
                                <div class="font-mono text-gray-900">{{ $scanResult['booking_code'] }}</div>
                                @if($scanResult['qr_verified'])
                                    <small class="text-green-600"><i class="fas fa-check-circle"></i> QR Verified</small>
                                @endif
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Class:</span>
                                <div class="font-semibold text-gray-900">{{ $scanResult['class_name'] }}</div>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Time:</span>
                                <div class="font-semibold text-gray-900">{{ $scanResult['class_time'] }}</div>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Type:</span>
                                <div class="font-semibold text-gray-900">{{ $scanResult['group_class'] }}</div>
                            </div>
                            <div>
                                <span class="font-medium text-gray-600">Trainer:</span>
                                <div class="font-semibold text-gray-900">{{ $scanResult['trainer_name'] }}</div>
                            </div>
                        </div>
                    </div>

                    <div class="flex gap-3">
                        <button wire:click="confirmCheckIn" 
                                class="flex-1 px-6 py-3 bg-green-600 text-white rounded-lg hover:bg-green-700 transition-colors font-semibold">
                            <i class="fas fa-check mr-2"></i>
                            Confirm Check-In
                        </button>
                        <button wire:click="resetScan" 
                                class="px-6 py-3 bg-gray-600 text-white rounded-lg hover:bg-gray-700 transition-colors">
                            <i class="fas fa-times mr-2"></i>
                            Cancel
                        </button>
                    </div>
                </div>
            </div>
        @endif

        <!-- Instructions -->
        <div class="bg-blue-50 rounded-lg p-6">
            <h4 class="font-semibold text-blue-900 mb-3">
                <i class="fas fa-info-circle mr-2"></i>
                Instructions
            </h4>
            <ul class="text-blue-800 text-sm space-y-2">
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5 text-xs"></i>
                    Members can show their QR code from the "My Bookings" page
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5 text-xs"></i>
                    Check-in window: 30 minutes before to 15 minutes after class starts
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5 text-xs"></i>
                    Each booking code can only be used once per class
                </li>
                <li class="flex items-start">
                    <i class="fas fa-check text-blue-600 mr-2 mt-0.5 text-xs"></i>
                    Cancelled bookings cannot be checked in
                </li>
            </ul>
        </div>
    </div>
</div>
