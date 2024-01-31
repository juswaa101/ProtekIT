<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Loading Page</title>
    <style>
        /* Add your custom styles for the loading page here */
        body {
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            flex-direction: column;
            height: 100vh;
            background-color: #f1f1f1;
        }

        .loader {
            border: 16px solid #d1d1d1;
            border-top: 16px solid #f44336;
            border-radius: 50%;
            width: 120px;
            height: 120px;
            animation: spin 1s linear infinite; /* Updated animation duration to 1s */
        }

        .loading-text {
            margin-top: 20px;
            font-size: 24px;
            color: #f44336;
            font-weight: bold;
        }

        @keyframes spin {
            0% {
                transform: rotate(0deg);
            }

            100% {
                transform: rotate(360deg);
            }
        }
    </style>
</head>

<body>
    <div id="loading-page">
        <div class="loader"></div>
        <div class="loading-text">Loading...</div>
    </div>
</body>

</html>
