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
        }
        h1, h2 {
            text-align: center;
            margin: 10px 0;
            font-weight: bold;
        }
        .event-details {
            font-size: 14px;
            background-color: #fafafa;
            padding: 15px;
            border-radius: 5px;
            margin: 20px 0;
            border: 1px solid #ddd;
        }
        .qr-code img {
            width: 100px;
            height: 100px;
            display: block;
            margin: 20px auto; /* Center the QR code */
        }
        .contact-link {
            color: #007bff;
            text-decoration: none;
        }
        .img-clip-path {
            position: relative;
            overflow: hidden;
            border-radius: 10px; /* Add border radius for aesthetics */
        }
        .img-clip-path img {
            width: 100%;
            height: auto;
        }
        .header-image {
            width: 100%;
            height: auto;
            border-radius: 10px;
        }
    </style>
</head>
<body>

    <h1>You're Invited!</h1>

    <div class="img-clip-path">
        @if ($event->image)
            @php
                $path = public_path($event->image);
                $type = pathinfo($path, PATHINFO_EXTENSION);
                $data = file_get_contents($path);
                $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
            @endphp
            <img src="{{ $base64 }}" alt="Event Image" class="header-image">
        @else
            <img src="https://demo.lucky-roo.com/dahlia-v1.0/images/the-couple-hero.jpg" alt="Default Image" class="header-image">
        @endif
    </div>

    <h2>{{ $event->mr_name ?? 'Milea' }} & {{ $event->mrs_name ?? 'Dilan' }}</h2>

    <div class="event-details">
        <p>Date: {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
        <p>Location: {{ $event->location ?? 'Venue: Grand Ballroom' }}</p>
        <p>Contact: <a class="contact-link" href="tel:{{ $event->contact_phone_1 }}">{{ $event->contact_phone_1 ?? $event->contact_phone_2 }}</a></p>
    </div>

    @if ($event->qr)
        <div class="qr-code">
            <img src="data:image/svg+xml;base64,{{ $event->qr }}" alt="QR Code">
        </div>
    @endif

    {{-- <img width="100%" src="https://demo.lucky-roo.com/dahlia-v1.0/img/ornament-divider.png" alt="Divider" class="d-block mx-auto"> --}}

</body>
</html>
