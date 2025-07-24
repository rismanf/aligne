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
                            <h1 style="color: #2e2e2e; font-size: 24px; margin: 0 0 20px;">Hi {{ $name }}</h1>
                            <h1 style="color: #2e2e2e; font-size: 24px; margin: 0 0 20px;">Invoice Number {{ $data->invoice_number }}</h1>
                            <p style="font-size: 16px; color: #333333; margin-bottom: 30px;">
                                You're almost there! Don't forget to fill out your Health Information Form for a safer
                                and more personalized Pilates experience. It takes just a few minutes.
                            </p>
                            <a href="{{ env('APP_URL') }}"
                                style="background-color: #4a4521; color: white; padding: 12px 30px; text-decoration: none; border-radius: 25px; font-size: 16px; display: inline-block; margin-bottom: 30px;">Go
                                to Aligné</a>
                            <p style="font-size: 13px; color: #444444;">
                                If you have already filled up the Health Information Form, you can ignore this
                                email.<br>
                                Thank you for providing us with the necessary details to enhance your Pilates
                                experience.
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
