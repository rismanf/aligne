<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Aligné | Invoice</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>

<body style="margin: 0; padding: 0; background-color: #f6f4ee; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f6f4ee; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="40" cellspacing="0" border="0" style="background-color: #ffffff; border: 2px solid #5c5329; border-radius: 8px;">
                    <tr>
                        <td>
                            @if (!empty($invoice))
                                <!-- Invoice Header -->
                                <div style="text-align: center; padding: 20px 0; border-bottom: 2px solid #f0f0f0; margin-bottom: 30px;">
                                    <h1 style="color: #2e2e2e; font-size: 28px; margin: 0 0 10px;">Invoice #{{ $invoice->invoice_number }}</h1>
                                    <p style="color: #666666; font-size: 16px; margin: 0;">Thank you for your membership purchase!</p>
                                </div>

                                <!-- Customer Information -->
                                <table width="100%" cellpadding="0" cellspacing="0" border="0" style="margin-bottom: 30px;">
                                    <tr>
                                        <td style="width: 50%; vertical-align: top;">
                                            <h3 style="color: #2e2e2e; font-size: 16px; margin: 0 0 10px; font-weight: bold;">Customer:</h3>
                                            <p style="color: #333333; font-size: 14px; margin: 0; line-height: 1.5;">
                                                {{ $invoice->user->name }}<br>
                                                {{ $invoice->user->email }}
                                            </p>
                                        </td>
                                        <td style="width: 50%; vertical-align: top; text-align: right;">
                                            <h3 style="color: #2e2e2e; font-size: 16px; margin: 0 0 10px; font-weight: bold;">Invoice Date:</h3>
                                            <p style="color: #333333; font-size: 14px; margin: 0 0 10px; line-height: 1.5;">
                                                {{ $invoice->created_at->format('M d, Y') }}
                                            </p>
                                            @php
                                                $statusColor = '#28a745'; // success - green
                                                $statusText = 'white';
                                                if($invoice->payment_status === 'pending') {
                                                    $statusColor = '#ffc107'; // warning - yellow
                                                    $statusText = '#000';
                                                } elseif($invoice->payment_status === 'failed' || $invoice->payment_status === 'cancelled') {
                                                    $statusColor = '#dc3545'; // danger - red
                                                    $statusText = 'white';
                                                }
                                            @endphp
                                            <span style="background-color: {{ $statusColor }}; color: {{ $statusText }}; padding: 4px 8px; border-radius: 4px; font-size: 12px; font-weight: bold;">
                                                {{ ucfirst($invoice->payment_status) }}
                                            </span>
                                        </td>
                                    </tr>
                                </table>

                                <!-- Divider -->
                                <hr style="border: none; border-top: 1px solid #e0e0e0; margin: 30px 0;">

                                <!-- Invoice Details -->
                                <div style="border: 1px solid #e0e0e0; padding: 20px; margin-bottom: 30px; border-radius: 4px;">
                                    <table width="100%" cellpadding="8" cellspacing="0" border="0" style="font-size: 14px; color: #333333;">
                                        <tr>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0;">
                                                <span>Membership Package:</span>
                                            </td>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0; text-align: right; font-weight: bold;">
                                                {{ $invoice->membership->name }}
                                            </td>
                                        </tr>
                                        
                                        @if($invoice->membership->description)
                                        <tr>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0;">
                                                <span>Description:</span>
                                            </td>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0; text-align: right; color: #666666;">
                                                {{ $invoice->membership->description }}
                                            </td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0;">
                                                <span>Package Price:</span>
                                            </td>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0; text-align: right;">
                                                IDR {{ number_format($invoice->price, 0, ',', '.') }}
                                            </td>
                                        </tr>

                                        @if($invoice->unique_code)
                                        <tr>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0;">
                                                <span>Unique Code:</span>
                                            </td>
                                            <td style="border-bottom: 1px solid #f0f0f0; padding: 8px 0; text-align: right;">
                                                IDR {{ number_format($invoice->unique_code, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                        @endif

                                        <tr>
                                            <td style="padding: 15px 0 8px 0; font-weight: bold; font-size: 16px;">
                                                <strong>Total Amount:</strong>
                                            </td>
                                            <td style="padding: 15px 0 8px 0; text-align: right; font-weight: bold; font-size: 16px;">
                                                <strong>IDR {{ number_format($invoice->total_price, 0, ',', '.') }}</strong>
                                            </td>
                                        </tr>

                                        <tr>
                                            <td style="border-top: 1px solid #f0f0f0; padding: 8px 0;">
                                                <span>Validity Period:</span>
                                            </td>
                                            <td style="border-top: 1px solid #f0f0f0; padding: 8px 0; text-align: right; font-weight: bold;">
                                                @if ($invoice->membership->valid_until == 0 || $invoice->membership->valid_until == null)
                                                    Lifetime
                                                @else
                                                    {{ $invoice->membership->valid_until }} days
                                                @endif
                                            </td>
                                        </tr>

                                        @if($invoice->expires_at)
                                        <tr>
                                            <td style="padding: 8px 0;">
                                                <span>Expires On:</span>
                                            </td>
                                            <td style="padding: 8px 0; text-align: right; font-weight: bold;">
                                                {{ $invoice->expires_at->format('M d, Y') }}
                                            </td>
                                        </tr>
                                        @endif
                                    </table>
                                </div>

                                <!-- Included Classes -->
                                @if($invoice->membership->groupClasses && $invoice->membership->groupClasses->count() > 0)
                                <div style="border: 1px solid #e0e0e0; padding: 20px; margin-bottom: 30px; border-radius: 4px;">
                                    <h3 style="color: #2e2e2e; font-size: 18px; margin: 0 0 15px;">Included Classes:</h3>
                                    <table width="100%" cellpadding="4" cellspacing="0" border="0">
                                        @foreach($invoice->membership->groupClasses as $class)
                                        <tr>
                                            <td style="padding: 4px 0; font-size: 14px; color: #333333;">
                                                • {{ $class->name }}
                                            </td>
                                        </tr>
                                        @endforeach
                                    </table>
                                </div>
                                @endif

                                <!-- Payment Status Message -->
                                <div style="margin-bottom: 30px;">
                                    @if($invoice->payment_status === 'paid')
                                        <div style="background-color: #d4edda; border: 1px solid #c3e6cb; color: #155724; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                                            <strong>Payment Confirmed!</strong> Your membership is now active.
                                        </div>
                                    @elseif($invoice->payment_status === 'pending')
                                        <div style="background-color: #fff3cd; border: 1px solid #ffeaa7; color: #856404; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                                            <strong>Payment Pending</strong> Please wait for admin confirmation.
                                        </div>
                                    @else
                                        <div style="background-color: #d1ecf1; border: 1px solid #bee5eb; color: #0c5460; padding: 15px; border-radius: 4px; margin-bottom: 20px;">
                                            <strong>Payment Required</strong> Please complete your payment to activate membership.
                                        </div>
                                    @endif
                                </div>

                                <!-- CTA Button -->
                                <div style="text-align: center; margin-bottom: 30px;">
                                    <a href="{{ env('APP_URL') }}" style="display: inline-block; padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 25px; font-size: 16px; font-weight: bold; transition: all 0.3s ease;">
                                        Visit Aligné Studio
                                    </a>
                                </div>

                            @else
                                <div style="text-align: center; padding: 40px 0;">
                                    <h2 style="color: #2e2e2e; font-size: 20px; margin: 0 0 15px;">Invoice Not Found</h2>
                                    <p style="color: #666666; font-size: 16px; margin: 0 0 20px;">The requested invoice could not be found or you don't have permission to view it.</p>
                                    <a href="{{ env('APP_URL') }}" style="display: inline-block; padding: 12px 30px; background-color: #007bff; color: white; text-decoration: none; border-radius: 25px; font-size: 16px; font-weight: bold;">
                                        Browse Memberships
                                    </a>
                                </div>
                            @endif

                            <!-- Footer -->
                            <div style="border-top: 1px solid #e0e0e0; padding-top: 20px; text-align: center;">
                                <p style="font-size: 13px; color: #666666; margin: 0;">
                                    If you have any questions, please don't hesitate to contact us at
                                    <a href="mailto:admin@alignestudio.id" style="color: #007bff; text-decoration: none;">
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
