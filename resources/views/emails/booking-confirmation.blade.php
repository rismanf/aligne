<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Aligné | Booking Confirmation</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
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
    </style>
</head>

<body style="margin: 0; padding: 0; background-color: #f6f4ee; font-family: Arial, sans-serif;">
    
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f6f4ee; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="40" cellspacing="0" border="0"
                    style="background-color: #ffffff; border: 2px solid #5c5329; border-radius: 8px;">
                    <tr>
                        <td>
                            <!-- Invoice Header -->
                            <div
                                style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f0f0f0; margin-bottom: 30px;">
                                <h1 style="color: #2e2e2e; font-size: 28px; margin: 0 0 10px;">🎉 Booking Confirmed!
                                </h1>
                            </div>

                            <!-- Customer Information -->
                            <table width="100%" cellpadding="0" cellspacing="0" border="0"
                                style="margin-bottom: 30px;">
                                <tr>
                                    <td style="width: 50%; vertical-align: top;">
                                        <h3
                                            style="color: #2e2e2e; font-size: 16px; margin: 0 0 10px; font-weight: bold;">
                                            Class:</h3>
                                        <p style="color: #333333; font-size: 14px; margin: 0; line-height: 1.5;">
                                            {{ $booking['group_class'] }}<br>
                                            {{ $booking['class_name'] }}<br>
                                            {{ $booking['trainer_name'] }}
                                        </p>
                                    </td>
                                    <td style="width: 50%; vertical-align: top; text-align: right;">
                                        <h3
                                            style="color: #2e2e2e; font-size: 16px; margin: 0 0 10px; font-weight: bold;">
                                            Booking Date:</h3>
                                        <p style="color: #333333; font-size: 14px; margin: 0 0 10px; line-height: 1.5;">
                                            {{ \Carbon\Carbon::parse($booking['date'])->format('D, M j, Y') }}<br>
                                            {{ \Carbon\Carbon::parse($booking['start_time'])->format('h:i A') }}<br>
                                            </p>
                                    </td>
                                </tr>
                            </table>

                            <!-- Divider -->
                            <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">

                            <!-- Invoice Details -->
                            <div class="qr-code"
                                style="border: 1px solid #e0e0e0; padding: 20px; margin-bottom: 30px; border-radius: 4px;">
                                <p style="margin-bottom: 15px; color: #6c757d;">Show this QR code at the studio</p>
                                @if (isset($booking['qr_code']) && !empty($booking['qr_code']))
                                    @if (str_starts_with($booking['qr_code'], 'data:image'))
                                        <!-- Data URI QR Code -->
                                        <img src="{{ $booking['qr_code'] }}"
                                            alt="QR Code for {{ $booking['booking_code'] }}"
                                            style="max-width: 200px; height: auto; border: 1px solid #dee2e6; border-radius: 6px;">
                                    @else
                                        <!-- Fallback: Use online QR code service -->
                                        <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($booking['booking_code']) }}"
                                            alt="QR Code for {{ $booking['booking_code'] }}"
                                            style="max-width: 200px; height: auto; border: 1px solid #dee2e6; border-radius: 6px;">
                                    @endif
                                @else
                                    <!-- Fallback: Use online QR code service -->
                                    <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($booking['booking_code']) }}"
                                        alt="QR Code for {{ $booking['booking_code'] }}"
                                        style="max-width: 200px; height: auto; border: 1px solid #dee2e6; border-radius: 6px;">
                                @endif
                                <p style="margin-top: 10px; font-size: 12px; color: #6c757d;">
                                    If QR code doesn't display, use booking code:
                                    <strong>{{ $booking['booking_code'] }}</strong>
                                </p>
                            </div>

                            <div
                                style="background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                                <h3>📋 Important Information</h3>
                                <ul>
                                    <li>Please arrive 30 minutes before class starts</li>
                                    <li>Bring your own water bottle and towel</li>
                                    <li>Cancellations must be made at least 12 hours before class</li>
                                    <li>Show your QR code to the instructor for attendance</li>
                                    <li>Keep this email for your records</li>
                                </ul>
                            </div>

                            <!-- CTA Button -->
                            <div style="text-align: center; margin-bottom: 30px;">
                                <a href="{{ env('APP_URL') }}"
                                    style="display: inline-block; padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 25px; font-size: 16px; font-weight: bold; transition: all 0.3s ease;">
                                    Visit Aligné Studio
                                </a>
                            </div>

                            <!-- Footer -->
                            <div style="border-top: 1px solid #e0e0e0; padding-top: 20px; text-align: center;">
                                <p style="font-size: 13px; color: #666666; margin: 0;">
                                    If you have any questions, please don't hesitate to contact us at
                                    <a href="mailto:admin@alignestudio.id"
                                        style="color: #007bff; text-decoration: none;">
                                        admin@alignestudio.id
                                    </a>
                                </p>
                            </div>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
