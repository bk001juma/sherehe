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


        .link {
            position: absolute;
            top: {{ $top }}%;
            left: {{ $left }}%;
            transform: translateX(-50%);
            font-weight: bold;
            font-size: {{ $font_size }};
            padding: 10px;
            padding-bottom: 110px;
            color: {{ $color }};
            z-index: 10;
            /* Ensure it's above the background */
            pointer-events: auto;
            /* Enable pointer events on the link */
        }

        .qr-code {
            position: absolute;
            top: {{ $qr_top }}px;
            right: {{ $qr_left }}px;
            z-index: 20;
            text-align: center;
            padding: 10px;
            background-color: rgba(255, 255, 255, 0.8);
            border-radius: 8px;
            pointer-events: none;
            /* Disable pointer events on QR code */
        }


        .card {
            background-color: white;
            padding: 0;
            border-radius: 0;
            box-shadow: none;
        }



        .qr-code img {
            max-width: 100%;
            height: auto;
            margin-bottom: 15px;
        }

        .qr-code p {
            margin: 0;
            font-size: 36px;
            color: #000;
            font-weight: bold;
        }

        .qr-code p.qr-otp-code {
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
            }
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="qr-code" id="qr-code">
            <div class="card">
                @if (!empty($attendee->qr))
                    <img src="data:image/svg+xml;base64,{{ $attendee->qr }}" alt="QR Code">
                @endif
                <p class="qr-otp-code">{{ $attendee->qr_otp_code ?? '' }}</p>
                <p>{{ $cardType ?? '' }}</p>
            </div>
        </div>

        <div class="section">
            <a href="https://withjoy.com/aika-and-daniel/registry" class="link">Click: Our Registry</a>
        </div>
        <div class="section">
            <p class="link">{{ $attendee->full_name ?? '' }}</p>
        </div>
        {{-- <div class="section">
            <a href="https://withjoy.com/aika-and-daniel/registry" class="link">Bofya: Kapu la Zawadi</a>
        </div>
        <div class="section">
            <p class="link">{{ $attendee['full_name'] }}</p>
        </div> --}}
    </div>
</body>

</html>
