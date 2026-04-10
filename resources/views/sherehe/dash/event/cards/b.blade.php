<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wedding Invitation</title>
    <style>
        body {
            font-family: "Arial", sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f7f7f7;
            text-align: center;
        }

        #container {
            max-width: 500px;
            margin: 50px auto;
            padding: 20px;
            background-color: transparent;
            border-radius: 15px 240px 0 0;
            overflow: hidden;
            box-shadow: 12px 13px 14px rgba(0, 0, 0, 0.9);
            filter: drop-shadow(0 0 10px #000);
            animation: glow 3s alternate infinite;
        }

        @keyframes glow {
            0% {
                filter: drop-shadow(0 0 10px #000);
            }
            100% {
                filter: drop-shadow(0 0 10px #fff);
            }
        }

        img {
            max-width: 500px;
            overflow: hidden;
            z-index: 12;
            border-radius: 9px 219px 5px 8px;
            box-shadow: 12px 13px 14px rgba(0, 0, 0, 0.9);
        }

        .title {
            max-width: 100%;
            background: transparent;
            margin-top: 50px;
            font-size: 40px;
            border-radius: 8px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        span {
            color: #ff00cf;
        }

        #details {
            max-width: 500px;
            padding: 10px;
            text-align: center;
            border-radius: 6px;
            box-shadow: 2px 2px 10px rgba(0, 0, 0, 0.1);
        }

        h2 {
            font-size: 30px;
            color: #ff7e5f;
            font-family: "Arial", "Segoe UI Emoji", sans-serif;
        }

        #details p {
            margin: 10px 0;
        }

        #footer {
            background-color: #ff7e5f;
            color: #fff;
            text-align: center;
            padding: 10px;
            margin-top: 10px;
        }
    </style>
</head>
<body>
    <center>
        <div id="container">
            {{-- <img class="slide" src="https://images.pexels.com/photos/1444442/pexels-photo-1444442.jpeg" alt=""> --}}
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
            <!-- Add more images as needed -->
            <h1 class="title"><span>Wedding</span> Invitation</h1>
            <h2>king &#10084; Queen</h2>
            <div id="details">
                <h3>Ceremony Details</h3>
                <p>Date: January 1, 2024</p>
                <p>Time: 5:00 PM</p>
                <p>Venue: Beautiful Gardens</p>
                <p>Dress Code: Formal</p>
            </div>

            <div id="footer">
                <p>We look forward to celebrating with you!</p>
            </div>
        </div>
    </center>

    <script>
        // Simple image slider using JavaScript
        let currentIndex = 0;
        const slides = document.querySelectorAll(".slide");

        function showSlide(index) {
            slides.forEach((slide) => {
                slide.style.display = "none";
            });
            slides[index].style.display = "block";
        }

        function nextSlide() {
            currentIndex = (currentIndex + 1) % slides.length;
            showSlide(currentIndex);
        }

        // Change slide every 3 seconds
        setInterval(nextSlide, 3000);
        showSlide(currentIndex); // Show the first slide initially
    </script>
</body>
</html>
