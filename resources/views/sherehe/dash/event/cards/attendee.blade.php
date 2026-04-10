<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <style>
        @page {
            margin: 0;
            padding: 0;
        }

        html,
        body {
            margin: 0;
            padding: 0;
            width: {{ $width }}px;
            height: {{ $height }}px;
        }

        body {
            background-image: url('{{ $imageBase64 }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
        }


        .qr-code {
            position: absolute;
            top: {{ $qr_top }}px;
            right: {{ $qr_left }}px;
            z-index: 10;
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
        }

        .card {
            background-color: white;
            padding: 5px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
        }

        .qr-code img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
            /* Adds space between QR code and full_name */
        }

        .qr-code p {
            margin: 0;
            font-size: 36px;
            /* Default size for other p elements */
            color: #000;
            font-weight: bold;
        }

        .qr-code p.full-name {
            font-size: 32px;
            /* Larger size for full_name */
            margin-bottom: 2px;
            /* Adds space between full_name and cardType */
        }

        @media (max-width: 768px) {
            .qr-code {
                top: 10px;
                right: 10px;
                max-width: 40%;
            }

            .qr-code p {
                font-size: 34px;
                /* Default size for other p elements */
            }

            .qr-code p.full-name {
                font-size: 28px;
                /* Larger size for full_name */
            }
        }

        @media (max-width: 480px) {
            .qr-code {
                top: 5px;
                right: 5px;
                max-width: 50%;
            }

            .qr-code p {
                font-size: 32px;
                /* Default size for other p elements */
            }

            .qr-code p.full-name {
                font-size: 24px;
                /* Larger size for full_name */
            }
        }
    </style>
</head>

<body>
    <div class="qr-code" id="qr-code">
        <div class="card">
            <img src="data:image/svg+xml;base64,{{ $attendee->qr }}" alt="QR Code">
            {{-- <p class="full-name">{{ $attendee->full_name }}</p> --}}
            <p>{{ $cardType }}</p>
            <p>{{ $attendee->qr_otp_code }}</p>
        </div>
    </div>
</body>

</html>
