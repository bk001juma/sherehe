<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Thank You</title>
    <style>
        /* Simple styles for the popup */
        .popup {
            position: fixed;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: #fff;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            text-align: center;
            display: none;
        }

        .popup h2 {
            margin: 0;
        }

        .popup .btn {
            margin-top: 15px;
            padding: 10px 20px;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
        }

        .popup .btn:hover {
            background-color: #218838;
        }
    </style>
</head>

<body>

    <div class="popup" id="thankYouPopup">
        <h2>{{ $message }}</h2>
        <button class="btn" onclick="closePopup()">Close</button>
    </div>

    <script>
        // Function to show the popup
        window.onload = function() {
            document.getElementById("thankYouPopup").style.display = "block";
        };

        // Function to close the popup
        function closePopup() {
            document.getElementById("thankYouPopup").style.display = "none";
        }
    </script>

</body>

</html>
