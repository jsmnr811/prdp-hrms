<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Your Registration Password</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
        }

        .container {
            max-width: 600px;
            margin: 0 auto;
            background-color: #ffffff;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .header {
            background-color: #374f2f;
            color: #ffffff;
            padding: 20px;
            text-align: center;
            border-radius: 10px 10px 0 0;
        }

        .content {
            padding: 20px;
            color: #333333;
        }

        .password-box {
            background-color: #f8f9fa;
            border: 1px solid #dee2e6;
            padding: 15px;
            border-radius: 5px;
            text-align: center;
            font-size: 18px;
            font-weight: bold;
            color: #374f2f;
            margin: 20px 0;
        }

        .footer {
            margin-top: 30px;
            padding-top: 20px;
            border-top: 1px solid #dee2e6;
            color: #6c757d;
            font-size: 14px;
        }

        .info {
            background-color: #d1ecf1;
            border: 1px solid #bee5eb;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
        }
    </style>
</head>

<body>
    <div class="container">
         <div class="header" style="text-align:center;">
            <img src="{{ $message->embed(public_path('assets/images/Scale-Up Logo.png')) }}" alt="Scale-Up Logo"
                style="height:100px; display:block; margin:0 auto 5px auto;">
            <h1 style="margin:0; font-size:24px;">PRDP-HRMS Account Registration</h1>
        </div>
        
        <div class="content">
            <h2>Hello {{ $user->name }},</h2>
            <p>Welcome to PRDP-HRMS! Your account has been successfully created. Below is your default password to log in to the system.</p>

            <div class="password-box">
                {{ $registrationPassword }}
            </div>

            <div class="info">
                <strong>Important:</strong> This is your default login password. For security reasons, we recommend changing it to a stronger password after your first login.
            </div>

            <p>You can now log in to the PRDP-HRMS system using your employee number and the password above.</p>
        </div>
        <div class="footer">
            <p>Best regards,<br>The PRDP-HRMS Support Team</p>
            <p>This is an automated message. Please do not reply to this email.</p>
            <p>Please do not mark this email as spam, as it contains important login information.</p>
        </div>
    </div>
</body>

</html>