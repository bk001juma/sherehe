<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Floral Invitation Card</title>
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Georgia', serif;
            background-color: #f4f1f0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }

        .invitation-card {
            max-width: 400px;
            padding: 20px;
            background-color: white;
            border-radius: 10px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.1);
            border: 2px solid #e7d3d1;
            text-align: center;
            position: relative;
        }

        .invitation-card::before {
            content: '';
            position: absolute;
            top: 0;
            left: 50%;
            transform: translateX(-50%);
            width: 100px;
            height: 100px;
            background: url('floral.png') no-repeat center;
            background-size: contain;
        }

        h1 {
            font-family: 'Cursive', sans-serif;
            font-size: 28px;
            color: #c27ba0;
            margin: 50px 0 20px;
        }

        p {
            font-size: 14px;
            color: #555;
            margin-bottom: 20px;
        }

        .event-details {
            font-size: 16px;
            background-color: #fafafa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
        }

        .qr-code img {
            width: 120px;
            height: 120px;
        }

        .footer {
            font-size: 12px;
            color: #999;
        }

        .footer a {
            color: #c27ba0;
            text-decoration: none;
        }

        .footer a:hover {
            text-decoration: underline;
        }
    </style>
</head>

<body>

    <div class="invitation-card">
        <h1>You're Invited!</h1>
        <p>We’re excited to celebrate this special moment with you.</p>

        <div class="event-details">
            <p>Wedding of {{ $event->mr_name ?? 'Sarah' }} & {{ $event->mrs_name ?? 'John' }}</p>
            <p>Date: {{ \Carbon\Carbon::parse($event->event_date)->format('F j, Y') }}</p>
            <p>Location: {{ $event->location ?? 'Venue: Grand Ballroom' }}</p>
        </div>

        <div class="qr-code">
            @if ($event->qr)
                <!-- Displaying the QR code in SVG format -->
                <img src="data:image/svg+xml;base64,{{ $event->qr }}" alt="QR Code" class="img-fluid">
            @endif
        </div>

        <div class="footer">
            <p>Contact us: <a href="tel:">{{ $event->contact_phone_1 ?? $event->contact_phone_2 }}</a></p>
        </div>
    </div>

</body>

</html>
