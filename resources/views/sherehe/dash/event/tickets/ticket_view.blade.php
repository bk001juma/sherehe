<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        @page {
            margin: 0;
            padding: 0;
            size: auto;
        }

        html,
        body {
            margin: 0 !important;
            padding: 0 !important;
            width: {{ $width }}px;
            height: {{ $height }}px;
            overflow: hidden;
            background-color: transparent;
        }

        .container {
            margin: 0 !important;
            padding: 0 !important;
            width: {{ $width }}px;
            height: {{ $height }}px;
            position: relative;
            background-image: url('{{ $imageBase64 }}');
            background-size: 100% 100%;
            background-repeat: no-repeat;
            background-position: left top;
        }

        .qr-code {
            position: absolute;
            top: {{ $qr_top }}%;
            right: {{ $qr_left }}%;
            transform: translateY(-50%);
            z-index: 10;
            text-align: center;
            padding-top: 10px;
            border-radius: 10px;
            height: 250px;
            width: auto;
        }

        .card {
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            max-width: 500px;
            width: 100%;
            position: relative;
        }

        .qr-code img {
            background-color: white;
            padding: 10px;
            border-radius: 10px;
            width: 400px;
            /* Fixed QR size */
            height: auto;
            display: block;
            margin: 0 auto;
        }

        .qr-code-texts p {
            margin: 5px 0;
            font-size: 25px;
            color: white;
            font-weight: bold;
            font-family: 'Arial', sans-serif' !important;

        }

        .attendee-name {
            position: absolute;
            top: {{ $top }}%;
            /* 42% QR top + half QR height */
            right: {{ $left }}%;
            transform: translateY(-50%);
            font-size: {{ $font_size }} !important;
            color: {{ $color }} !important;
            font-weight: bold;
            font-family: 'Arial', sans-serif' !important;
 white-space: nowrap;
            overflow: visible;
            text-align: center;
            z-index: 10;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- QR Code -->
        <div class="qr-code" id="qr-code">
            <div class="card">
                <img src="data:image/svg+xml;base64,{{ $attendee->qr }}" alt="QR Code">
                <div class="qr-code-texts">
                    <p>{{ $attendee->qr_otp_code }}</p>
                    <p>{{ $cardType }}</p>
                </div>
            </div>
        </div>

        <!-- Name: Positioned below the QR code, doesn't affect its layout -->
        <p class="attendee-name">{{ $attendee->full_name }}</p>
    </div>
</body>

</html>
