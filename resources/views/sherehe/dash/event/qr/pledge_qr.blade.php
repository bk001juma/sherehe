<!doctype html>
<html lang="en" class="h-100 w-100">

<head>
    <title>Wedding Invitation</title>
    <link rel="preload" href="https://demo.lucky-roo.com/dahlia-v1.0/css/style.min.css" as="style">
    <link rel="preload" href="https://demo.lucky-roo.com/dahlia-v1.0/js/script.min.js" as="script">
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="description" content>
    <meta name="keywords" content>

    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />

    <link href="https://demo.lucky-roo.com/dahlia-v1.0/css/style.min.css" rel="stylesheet">
    <link href="https://demo.lucky-roo.com/dahlia-v1.0/css/custom.min.css" rel="stylesheet">

    <link rel="icon" href="https://demo.lucky-roo.com/dahlia-v1.0/img/favicon-32x32.png" sizes="32x32"
        type="image/png">
    <link rel="icon" href="https://demo.lucky-roo.com/dahlia-v1.0/img/favicon-16x16.png" sizes="16x16"
        type="image/png">
    <link rel="icon" href="https://demo.lucky-roo.com/dahlia-v1.0/img/favicon.ico">

    <style>
        @page {
            size: 7in 9.25in;
            margin: 0px;
        }

        body {
            margin: 0px;
        }
    </style>

    {{--    <script async src="https://www.googletagmanager.com/gtag/js?id=G-EWZ9LKHWB6"> --}}
    {{--    </script> --}}
    {{--    <script> --}}
    {{--        window.dataLayer = window.dataLayer || []; --}}
    {{--        function gtag() { dataLayer.push(arguments); } --}}
    {{--        gtag('js', new Date()); --}}

    {{--        gtag('config', 'G-EWZ9LKHWB6'); --}}
    {{--    </script> --}}
</head>

<body id="page-top" class="h-100 w-100">

    <div class="row">
        <div class="col-md-9 col-lg-6 col-xl-5 bg-white  position-relative rounded shadow">
            <div class="mb-3"></div>
            <h5 class="font-alt fw-bold lh-1 mb-3 text-center">
                Tunafuraha kukukaribisha, {{ $attendee->full_name }}!</h5>
            <span class="section-divider divider-secondary"></span>
            <div class="img-clip-path clip-hero position-relative">
                @if ($event->image)
                    @php
                        $path = public_path($event->image);
                        $type = pathinfo($path, PATHINFO_EXTENSION);
                        $data = file_get_contents($path);
                        $base64 = 'data:image/' . $type . ';base64,' . base64_encode($data);
                    @endphp
                    <img src="{{ $base64 }}" alt class="img-fluid">
                @else
                    <img src="https://demo.lucky-roo.com/dahlia-v1.0/images/the-couple-hero.jpg" alt class="img-fluid">
                @endif

            </div>

            <div class="align-items-center d-flex justify-content-center mb-2">
                <h2 class="font-alt fw-bold m-0">{{ $event->mr_name ?? 'Milea' }} </h2>
                <span class="icon-svg icon-svg-lg mx-2"><img
                        src="https://demo.lucky-roo.com/dahlia-v1.0/img/icon-wedding-ampersand.svg" alt></span>
                <h2 class="font-alt fw-bold m-0">{{ $event->mrs_name ?? 'Dilan' }}</h2>
            </div>

            <p class="fs-5 text-center text-muted" style="font-size: 3px">Karibu Tusherehekee Pamoja Kwenye {{ $event->description }}.</p>

            {{-- @if ($event->qr)
                <!-- Displaying the QR code in SVG format -->
                <img src="data:image/svg+xml;base64,{{ $event->qr }}" alt="QR Code" class="img-fluid">
            @endif --}}



            <img width="100" src="https://demo.lucky-roo.com/dahlia-v1.0/img/ornament-divider.png" alt
                class="d-block mx-auto">
            <span class="ornament-corner ornament-primary ornament-top"></span>
            <span class="ornament-corner ornament-primary"></span>
        </div>
    </div>


    <script src="https://demo.lucky-roo.com/dahlia-v1.0/js/script.min.js"></script>
</body>

</html>
