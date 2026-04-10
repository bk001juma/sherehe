<!DOCTYPE html>
<html lang="en">

<head>
    <style>
        html,
        .container {
            margin: 0;
            padding: 0;
            width: {{ $width }}px;
            height: {{ $height }}px;
        }

        .container {
            background-image: url('{{ $imageBase64 }}');
            background-size: cover;
            background-repeat: no-repeat;
            background-position: center;
            position: relative;
        }

        .section {
            height: 50%;
            position: relative;
        }

        .link {
            position: absolute;
            top: 48%;
            /* bottom: 100px; */
            left: 50%;
            transform: translateX(-50%);
            font-weight: bold;
            /* font-style: oblique; */
            font-size: 70px;
            padding: 10px;
            padding-bottom: 110px;
            color: #31322d;
        }
    </style>
</head>

<body>
    <div class="container">

        <div class="section">
            {{-- @if ($withNameOrNot === 'yes')
                <p class="link">{{ $attendee['full_name'] }}</p>
            @endif --}}
        </div>
    </div>
</body>

</html>
