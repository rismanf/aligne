<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <title>Aligné | Invoice</title>
</head>

<body style="margin: 0; padding: 0; background-color: #f6f4ee; font-family: Arial, sans-serif;">
    <table width="100%" cellpadding="0" cellspacing="0" border="0" style="background-color: #f6f4ee; padding: 40px 0;">
        <tr>
            <td align="center">
                <table width="600" cellpadding="40" cellspacing="0" border="0"
                    style="background-color: #ffffff; border: 2px solid #5c5329; ">
                    <tr>
                        <td>
                            <h1 style="color: #2e2e2e; font-size: 24px; margin: 0 0 20px;">Hi {{ $data->user->name }}
                            </h1>
                            <h1 style="color: #2e2e2e; font-size: 24px; margin: 0 0 20px;">Invoice Number
                                {{ $data->invoice_number }}</h1>
                            <p style="font-size: 16px; color: #333333; margin-bottom: 30px;">
                                Thank you for your purchase at Aligné. Your invoice is attached below.
                            </p>
                            <p>Detail Payment</p>

                            <p style="font-size: 16px; color: #333333; margin-bottom: 30px;">
                                Membership : {{ $product->name }}
                                @foreach ($product->classes as $val)
                                    <br><span style="font-size: 8;"> -
                                        {{ $val->name . ' | ' . $val->pivot->quota }}</span>
                                @endforeach
                            </p>

                            <table width="100%" cellpadding="6" cellspacing="0"
                                style="font-size: 18px; color: #2e2e2e; margin-bottom: 30px;">
                                <tr>
                                    <td style="font-weight: bold; width: 100px;">Total</td>
                                    <td style="width: 10px;">:</td>
                                    <td>IDR {{ number_format($data->total_price, 0, ',', '.') }}</td>
                                </tr>
                                <tr>
                                    <td style="font-weight: bold;">Valid</td>
                                    <td>:</td>
                                    <td>{{ $product->valid_until }} Days</td>
                                </tr>
                            </table>
                            <a href="{{ env('APP_URL') }}"
                                style="background-color: #4a4521; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-size: 16px; display: inline-block; margin-bottom: 30px;">Go
                                to Aligné</a>
                            <p style="font-size: 13px; color: #444444;">
                                If you have any questions, please don't hesitate to contact us.
                                <a href="admin@alignestudio.id">
                                    admin@<span class="domain">alignestudio.id</span>
                                </a>
                            </p>
                            <br>
                        </td>
                    </tr>
                </table>
            </td>
        </tr>
    </table>
</body>

</html>
