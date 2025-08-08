<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Confirmation - {{ $booking['class_name'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
            background-color: #f8f9fa;
        }
        .email-container {
            background-color: white;
            border-radius: 8px;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
            overflow: hidden;
        }
        .header {
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .content {
            padding: 30px 20px;
        }
        .class-badge {
            display: inline-block;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
            text-transform: uppercase;
            margin-bottom: 20px;
        }
        .badge-reformer { background-color: #d1ecf1; color: #0c5460; }
        .badge-chair { background-color: #d4edda; color: #155724; }
        .badge-other { background-color: #fff3cd; color: #856404; }
        .class-title {
            font-size: 28px;
            font-weight: bold;
            text-align: center;
            margin-bottom: 30px;
            color: #2c3e50;
        }
        .details-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
            margin-bottom: 30px;
        }
        .detail-item {
            text-align: center;
            padding: 15px;
            background-color: #f8f9fa;
            border-radius: 6px;
        }
        .detail-label {
            font-size: 12px;
            color: #6c757d;
            text-transform: uppercase;
            margin-bottom: 5px;
        }
        .detail-value {
            font-weight: bold;
            font-size: 16px;
            color: #2c3e50;
        }
        .reformer-position {
            background: linear-gradient(135deg, #f4f4f4 0%, #dbafaf 100%);
            color: white;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            margin-bottom: 30px;
        }
        .reformer-position h3 {
            margin: 0 0 10px 0;
            font-size: 18px;
        }
        .position-number {
            font-size: 24px;
            font-weight: bold;
        }
        .booking-code {
            background-color: #e9ecef;
            padding: 15px;
            border-radius: 6px;
            text-align: center;
            margin-bottom: 30px;
        }
        .booking-code-label {
            font-size: 12px;
            color: #6c757d;
            margin-bottom: 5px;
        }
        .booking-code-value {
            font-family: 'Courier New', monospace;
            font-size: 18px;
            font-weight: bold;
            color: #2c3e50;
        }
        .qr-code {
            text-align: center;
            margin-bottom: 30px;
        }
        .qr-code img {
            max-width: 200px;
            height: auto;
            border: 1px solid #dee2e6;
            border-radius: 6px;
        }
        .important-info {
            background-color: #fff3cd;
            border-left: 4px solid #ffc107;
            padding: 20px;
            margin-top: 30px;
        }
        .important-info h3 {
            margin-top: 0;
            color: #856404;
        }
        .important-info ul {
            margin-bottom: 0;
            padding-left: 20px;
        }
        .important-info li {
            margin-bottom: 8px;
            color: #856404;
        }
        .footer {
            background-color: #2c3e50;
            color: white;
            padding: 20px;
            text-align: center;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 24px;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
            text-decoration: none;
            border-radius: 6px;
            font-weight: bold;
            margin: 10px 5px;
        }
        @media (max-width: 600px) {
            .details-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <div class="email-container">
        <!-- Header -->
        <div class="header">
            <h1>🎉 Booking Confirmed!</h1>
            <p>Your class has been successfully booked</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Class Type Badge -->
            <div style="text-align: center;">
                <span class="class-badge 
                    @if($booking['group_class'] == 'REFORMER CLASS') badge-reformer
                    @elseif($booking['group_class'] == 'CHAIR CLASS') badge-chair
                    @else badge-other @endif">
                    {{ $booking['group_class'] }}
                </span>
            </div>

            <!-- Class Name -->
            <h2 class="class-title">{{ $booking['class_name'] }}</h2>

            <!-- Class Details -->
            <div class="details-grid">
                <div class="detail-item">
                    <div class="detail-label">Date</div>
                    <div class="detail-value">{{ \Carbon\Carbon::parse($booking['date'])->format('D, M j, Y') }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Time</div>
                    <div class="detail-value">{{ $booking['start_time'] }} - {{ $booking['end_time'] }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Trainer</div>
                    <div class="detail-value">{{ $booking['trainer_name'] }}</div>
                </div>
                <div class="detail-item">
                    <div class="detail-label">Membership</div>
                    <div class="detail-value">{{ $booking['membership_name'] }}</div>
                </div>
            </div>

            @if($booking['group_class'] == 'REFORMER CLASS' && $booking['reformer_position'])
                <!-- Reformer Position -->
                <div class="reformer-position">
                    <h3>🎯 Your Reformer Position</h3>
                    <div class="position-number">Position {{ $booking['reformer_position'] }}</div>
                    <p style="margin: 10px 0 0 0; font-size: 14px;">Please find your assigned Reformer machine</p>
                </div>
            @endif

            <!-- Booking Code -->
            <div class="booking-code">
                <div class="booking-code-label">BOOKING CODE</div>
                <div class="booking-code-value">{{ $booking['booking_code'] }}</div>
            </div>

            <!-- QR Code -->
            <div class="qr-code">
                <p style="margin-bottom: 15px; color: #6c757d;">Show this QR code at the studio</p>
                @if(isset($booking['qr_code']) && !empty($booking['qr_code']))
                    @if(str_starts_with($booking['qr_code'], 'data:image'))
                        <!-- Data URI QR Code -->
                        <img src="{{ $booking['qr_code'] }}" alt="QR Code for {{ $booking['booking_code'] }}" style="max-width: 200px; height: auto; border: 1px solid #dee2e6; border-radius: 6px;">
                    @else
                        <!-- Fallback: Use online QR code service -->
                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($booking['booking_code']) }}" alt="QR Code for {{ $booking['booking_code'] }}" style="max-width: 200px; height: auto; border: 1px solid #dee2e6; border-radius: 6px;">
                    @endif
                @else
                    <!-- Fallback: Use online QR code service -->
                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($booking['booking_code']) }}" alt="QR Code for {{ $booking['booking_code'] }}" style="max-width: 200px; height: auto; border: 1px solid #dee2e6; border-radius: 6px;">
                @endif
                <p style="margin-top: 10px; font-size: 12px; color: #6c757d;">
                    If QR code doesn't display, use booking code: <strong>{{ $booking['booking_code'] }}</strong>
                </p>
            </div>

            <!-- Action Buttons -->
            <div style="text-align: center; margin: 30px 0;">
                <a href="{{ route('user.my-bookings') }}" class="btn">View All Bookings</a>
                <a href="{{ route('classes') }}" class="btn">Book Another Class</a>
            </div>
        </div>

        <!-- Important Information -->
        <div class="important-info">
            <h3>📋 Important Information</h3>
            <ul>
                <li>Please arrive 10 minutes before class starts</li>
                <li>Bring your own water bottle and towel</li>
                <li>Cancellations must be made at least 12 hours before class</li>
                <li>Show your QR code to the instructor for attendance</li>
                <li>Keep this email for your records</li>
            </ul>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p><strong>Thank you for choosing our fitness studio!</strong></p>
            <p>If you have any questions, please contact us at support@yourstudio.com</p>
            <p style="margin-top: 15px; font-size: 12px; opacity: 0.8;">
                This is an automated email. Please do not reply to this message.
            </p>
        </div>
    </div>
</body>
</html>
