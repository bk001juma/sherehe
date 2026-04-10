<!doctype html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $event->mr_name }} & {{ $event->mrs_name }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            background-color: #ffffff;
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
        }

        .container {
            text-align: center;
            width: 80%;
            /* Covers 80% of the viewport width */
            max-width: 800px;
            /* Restrict maximum width to 800px */
            padding: 40px;
            /* Adds space inside the container */
            background-color: #f3f3f3;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
        }

        h1 {
            margin: 10px 0;
            font-size: 2.5em;
            /* Larger font size */
            font-weight: bold;
        }

        .qr-code img {
            width: 150px;
            /* Larger QR code */
            height: 150px;
            margin-top: 20px;
        }
    </style>
</head>

<body>

    <div class="container">
        <h1>{{ $event->event_name }} QrCode</h1>

        @if ($event->qr)
            <div class="qr-code">
                <img src="data:image/svg+xml;base64,{{ $event->qr }}" alt="QR Code">
            </div>
        @endif
    </div>

</body>

</html>
