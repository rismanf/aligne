<div>
    <!-- Hero Section -->
    <section class="hero section" style="background-color: #F6F4EB;">
        <div class="container">
            <div class="row gy-4">
                <div class="col-lg-12 text-center">
                    <h1 class="mb-2" style="color: #4B2E2E;;">Member Check-In</h1>
                </div>
            </div>
        </div>
    </section>

    <!-- Check-in Section -->
    <section style="background-color: #F6F4EB;">
        <div class="container">
            <div class="row justify-content-center">
                <div class="col-lg-8">
                    <!-- Scanner Interface -->
                    <div class="card shadow-lg border-0 mb-4">
                        <div class="card-body p-5">
                            <div class="text-center mb-4">
                                <div class="d-inline-flex align-items-center justify-content-center rounded-circle mb-3"
                                    style="width: 80px; height: 80px; background-color: #A6A58A; background-opacity: 0.2;">
                                    <i class="bi bi-qr-code" style="font-size: 2rem; color: #4B2E2E;"></i>
                                </div>
                                <h3 class="card-title" style="color: #4B2E2E;">Check-In for Your Class</h3>
                                <p style="color: #A6A58A;">Scan your QR code or enter your booking code manually</p>
                            </div>

                            <!-- Camera Scanner -->
                            <div class="text-center mb-4">
                                <button wire:click="toggleCamera"
                                    class="btn btn-lg px-4" 
                                    style="background-color: {{ $showCamera ? '#dc3545' : '#4B2E2E' }}; border-color: {{ $showCamera ? '#dc3545' : '#4B2E2E' }}; color: white;">
                                    <i class="bi {{ $showCamera ? 'bi-camera-video-off' : 'bi-camera' }} me-2"></i>
                                    {{ $showCamera ? 'Stop Camera' : 'Scan QR Code' }}
                                </button>
                            </div>

                            <!-- Camera Preview -->
                            @if ($showCamera)
                                <div class="text-center mb-4">
                                    <div class="position-relative d-inline-block">
                                        <video id="qr-video" class="border rounded"
                                            style="max-width: 100%; width: 400px; height: 300px; border-color: #D4BDA5 !important;" autoplay playsinline muted></video>
                                        <div class="position-absolute top-50 start-50 translate-middle border border-2 rounded"
                                            style="width: 200px; height: 200px; pointer-events: none; border-color: #4B2E2E !important;">
                                        </div>
                                    </div>
                                    <p class="mt-2 small" style="color: #A6A58A;">Position your QR code within the frame</p>
                                    <div id="camera-status" class="small mt-2" style="color: #A6A58A;"></div>
                                </div>
                            @endif

                            <!-- Manual Input -->
                            <div class="row justify-content-center">
                                <div class="col-md-8">
                                    <div class="input-group input-group-lg">
                                        <input type="text" wire:model="bookingCode" wire:keydown.enter="scanBooking"
                                            placeholder="Enter booking code"
                                            class="form-control text-center font-monospace" 
                                            style="font-size: 1.1rem; border-color: #D4BDA5; color: #4B2E2E;"
                                            autofocus>
                                        <button wire:click="scanBooking" class="btn px-4" 
                                            style="background-color: #4B2E2E; border-color: #4B2E2E; color: white;">
                                            <i class="bi bi-search me-1"></i>
                                            Check
                                        </button>
                                    </div>

                                    @if ($bookingCode)
                                        <div class="text-center mt-2">
                                            <button wire:click="resetScan" class="btn btn-link btn-sm" style="color: #A6A58A;">
                                                <i class="bi bi-x-circle me-1"></i>
                                                Clear
                                            </button>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Messages -->
                    @if ($message)
                        <div class="alert 
                            @if ($messageType === 'success') alert-success
                            @elseif($messageType === 'warning') alert-warning
                            @else alert-danger @endif 
                            alert-dismissible fade show"
                            role="alert">
                            <div class="d-flex align-items-center">
                                <i
                                    class="bi 
                                    @if ($messageType === 'success') bi-check-circle-fill
                                    @elseif($messageType === 'warning') bi-exclamation-triangle-fill
                                    @else bi-x-circle-fill @endif me-2"></i>
                                {{ $message }}
                            </div>
                        </div>
                    @endif

                    <!-- Instructions -->
                    <div class="card" style="border-color: #D4BDA5; border-opacity: 0.5;">
                        <div class="card-body p-4">
                            <h5 class="card-title mb-3" style="color: #4B2E2E;">
                                <i class="bi bi-info-circle me-2"></i>
                                How to Check-In
                            </h5>
                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle p-2 me-3 flex-shrink-0" style="background-color: #D4BDA5; background-opacity: 0.3;">
                                            <i class="bi bi-qr-code" style="color: #4B2E2E;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1" style="color: #4B2E2E;">QR Code Scanning</h6>
                                            <small style="color: #A6A58A;">Click "Scan QR Code" and position your booking QR
                                                code within the camera frame</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle p-2 me-3 flex-shrink-0" style="background-color: #D4BDA5; background-opacity: 0.3;">
                                            <i class="bi bi-keyboard" style="color: #4B2E2E;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1" style="color: #4B2E2E;">Manual Entry</h6>
                                            <small style="color: #A6A58A;">Type your booking code in the input field and
                                                click "Check"</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle p-2 me-3 flex-shrink-0" style="background-color: #A6A58A; background-opacity: 0.3;">
                                            <i class="bi bi-clock" style="color: #4B2E2E;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1" style="color: #4B2E2E;">Check-in Window</h6>
                                            <small style="color: #A6A58A;">Available 30 minutes before to 10 minutes after
                                                class starts</small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-md-6">
                                    <div class="d-flex align-items-start">
                                        <div class="rounded-circle p-2 me-3 flex-shrink-0" style="background-color: #CBB4A0; background-opacity: 0.3;">
                                            <i class="bi bi-exclamation-triangle" style="color: #4B2E2E;"></i>
                                        </div>
                                        <div>
                                            <h6 class="mb-1" style="color: #4B2E2E;">One-Time Use</h6>
                                            <small style="color: #A6A58A;">Each booking code can only be used once per
                                                class</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </section>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/jsqr@1.4.0/dist/jsQR.js"></script>
<script>
    document.addEventListener('livewire:initialized', () => {
        let video = null;
        let canvas = null;
        let context = null;
        let scanning = false;
        let stream = null;

        // Listen for camera start event
        Livewire.on('start-camera', () => {
            startCamera();
        });

        // Listen for camera stop event
        Livewire.on('stop-camera', () => {
            stopCamera();
        });

        // Listen for reset after delay
        Livewire.on('reset-after-delay', () => {
            setTimeout(() => {
                @this.resetScan();
            }, 3000);
        });

        function updateStatus(message) {
            const statusElement = document.getElementById('camera-status');
            if (statusElement) {
                statusElement.textContent = message;
            }
        }

        function startCamera() {
            console.log('Starting camera...');
            updateStatus('Initializing camera...');
            
            // Add a small delay to allow Livewire to render the video element
            setTimeout(() => {
                video = document.getElementById('qr-video');
                if (!video) {
                    console.error('Video element not found');
                    updateStatus('Error: Video element not found');
                    return;
                }
                
                initializeCamera();
            }, 100);
        }

        function initializeCamera() {

            canvas = document.createElement('canvas');
            context = canvas.getContext('2d');
            scanning = true;

            // Check if getUserMedia is supported
            if (!navigator.mediaDevices || !navigator.mediaDevices.getUserMedia) {
                console.error('getUserMedia not supported');
                updateStatus('Camera not supported on this device');
                alert('Camera access is not supported on this device or browser.');
                @this.toggleCamera();
                return;
            }

            navigator.mediaDevices.getUserMedia({
                    video: {
                        facingMode: 'environment',
                        width: { ideal: 640 },
                        height: { ideal: 480 }
                    }
                })
                .then(mediaStream => {
                    stream = mediaStream;
                    if (video && scanning) {
                        video.srcObject = stream;
                        video.setAttribute('playsinline', true);
                        video.setAttribute('autoplay', true);
                        video.setAttribute('muted', true);
                        
                        video.onloadedmetadata = () => {
                            console.log('Video metadata loaded');
                            updateStatus('Camera ready - Position QR code in frame');
                            canvas.width = video.videoWidth;
                            canvas.height = video.videoHeight;
                            video.play().then(() => {
                                console.log('Video playing');
                                scanQRCode();
                            }).catch(err => {
                                console.error('Error playing video:', err);
                                updateStatus('Error starting video');
                            });
                        };

                        video.onerror = (err) => {
                            console.error('Video error:', err);
                            updateStatus('Video error occurred');
                        };
                    }
                })
                .catch(err => {
                    console.error('Error accessing camera:', err);
                    updateStatus('Camera access denied or unavailable');
                    
                    let errorMessage = 'Unable to access camera. ';
                    if (err.name === 'NotAllowedError') {
                        errorMessage += 'Please allow camera permissions and try again.';
                    } else if (err.name === 'NotFoundError') {
                        errorMessage += 'No camera found on this device.';
                    } else {
                        errorMessage += 'Please check permissions and try again.';
                    }
                    
                    alert(errorMessage);
                    @this.toggleCamera();
                });
        }

        function stopCamera() {
            console.log('Stopping camera...');
            scanning = false;
            updateStatus('');
            
            if (stream) {
                stream.getTracks().forEach(track => {
                    track.stop();
                    console.log('Track stopped:', track.kind);
                });
                stream = null;
            }
            
            if (video) {
                video.srcObject = null;
                video.load();
            }
        }

        function scanQRCode() {
            if (!scanning || !video || !context) {
                return;
            }

            if (video.readyState === video.HAVE_ENOUGH_DATA) {
                try {
                    context.drawImage(video, 0, 0, canvas.width, canvas.height);
                    const imageData = context.getImageData(0, 0, canvas.width, canvas.height);
                    const code = jsQR(imageData.data, imageData.width, imageData.height);

                    if (code) {
                        console.log('QR Code detected:', code.data);
                        updateStatus('QR Code detected! Processing...');
                        @this.call('processQrCode', code.data);
                        return;
                    }
                } catch (err) {
                    console.error('Error scanning QR code:', err);
                }
            }

            // Continue scanning
            if (scanning) {
                requestAnimationFrame(scanQRCode);
            }
        }

        // Clean up when page is unloaded
        window.addEventListener('beforeunload', () => {
            stopCamera();
        });

        // Clean up when component is destroyed
        document.addEventListener('livewire:navigating', () => {
            stopCamera();
        });
    });
</script>
@endpush
