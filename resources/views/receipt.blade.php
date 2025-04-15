<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>Transaction Receipt</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            font-family: 'Segoe UI', Tahoma, sans-serif;
            font-size: 13px;
            background-color: #fff;
            color: #000;
        }

        .container {
            max-width: 700px;
            margin: 40px auto;
            padding: 20px;
            border: 1px solid #d6d6d6;
            box-shadow: 0 0 5px rgba(0, 0, 0, 0.05);
        }

        .header {
            background-color: rgb(223, 252, 214);
        ;
            color: #036517;
            padding: 15px 20px;
            font-size: 16px;
            font-weight: bold;
        }

        .section {
            display: flex;
            justify-content: space-between;
            padding: 10px 20px;
            border-bottom: 1px solid #e1e1e1;
        }

        .label {
            font-weight: bold;
            text-transform: uppercase;
            color: #333;
        }

        .value {
            text-align: right;
            color: #333;
        }

        .notice {
            background-color: #e9fce4;
            padding: 20px;
            font-size: 12px;
            line-height: 1.6;
            margin-top: 20px;
        }

        .footer {
            font-size: 12px;
            text-align: right;
            color: #666;
            padding: 10px 20px;
        }

        @media only screen and (max-width: 600px) {
            .section {
                flex-direction: column;
                align-items: flex-start;
            }
            .value {
                text-align: left;
                margin-top: 5px;
            }
        }
    </style>
</head>

<body>
<div class="container">

    <div class="header" style="padding: 15px 20px;">
        <table style="width: 100%;">
            <tr>
                <td style="font-size: 16px; font-weight: bold; color: #077d1f;">
                    Online Transaction Receipt
                </td>
                <td style="text-align: right;">
                    <img src="{{ public_path('assets/images/mainlogo.png') }}" alt="Logo" style="height: 40px;">
                </td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="label">Date</div>
        <div class="value">{{ $date }}</div>

    </div>

    <div class="section">
        <div class="label">Transaction</div>
        <div class="value">NGN {{ $amount }}</div>
    </div>

    <div class="section">
        <div class="label"></div>
        <div class="value">{{ $type }}</div>
    </div>

    <div class="section">
        <div class="label"></div>
        <div class="value">{{ $dateOnly }}</div>
    </div>

    <div class="section">
        <div class="label">Beneficiary</div>
        <div class="value">{{ $beneficiary }}</div>
    </div>

    <div class="section">
        <div class="label"></div>
        <div class="value">{{ $beneficiary_account }}</div>
    </div>

    <div class="section">
        <div class="label"></div>
        <div class="value">{{ $beneficiary_bank }}</div>
    </div>

    <div class="section">
        <div class="label">Sender</div>
        <div class="value">{{ $sender }}</div>
    </div>

    <div class="section">
        <div class="label"></div>
        <div class="value">{{ $sender_account }}</div>
    </div>

    <div class="section">
        <div class="label"></div>
        <div class="value">{{ $sender_bank }}</div>
    </div>

    <div class="section">
        <div class="label">Status</div>
        <div class="value">{{ $status }}</div>
    </div>

    <div class="section">
        <div class="label">Narration</div>
        <div class="value">{{ $narration }}</div>
    </div>

    <div class="notice">
        <strong>Notice</strong><br><br> This is an online auto generated transaction receipt.<br> This is an authentic receipt. For further inquiries, contact 0700 909 909 909<br> Email: customercare@emaarmfb.com
    </div>

    <div class="footer">
        Generated from EMAAR MFB App
    </div>
</div>
</body>

</html>
