<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $details['subject'] }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f8f9fa;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }
        .card {
            border: none;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.1);
        }
        .card-header {
            background-color: #007bff;
            border-radius: 10px 10px 0 0;
            color: #fff;
            padding: 20px;
        }
        .card-body {
            padding: 20px;
        }
        h1, h2, h3, h4, h5, h6 {
            margin-top: 0;
        }
        p {
            margin-bottom: 1rem;
        }
        .center_logo{
            text-align:center;
        }
    </style>
</head>
<body>
    <div class="container">
        <div class="card">
            <div class="card-header">
                <div class="center_logo">
                    <img src="{{ asset('new/img/newdp-image.png') }}" alt="Logo">
                </div>
                <h1 class="text-center">{{ $details['subject'] }}</h1>
            </div>
            <div class="card-body">
                <p>{{ $details['body'] }}</p>
                <hr>
                <p>If you have any questions or concerns, please don't hesitate to contact us.</p>
                <p>Thank you for using our service!</p>
            </div>
        </div>
    </div>
</body>
</html>
