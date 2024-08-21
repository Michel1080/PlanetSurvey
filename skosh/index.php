<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skadoosh Demo</title>
    <style>
        body {
            margin: 0;
            padding: 0;
            background-color: #0b1a58;
            color: white;
            font-family: Arial, sans-serif;
            text-align: center;
        }

        .container {
            width: 100%;
            max-width: 600px;
            margin: 0 auto;
            padding: 50px 20px;
        }

        .logo img {
            width: 150px;
            margin-bottom: 20px;
        }

        h1 {
            font-size: 24px;
            margin-bottom: 20px;
        }

        h1 span {
            color: #ff0000;
        }

        h1 span.yellow {
            color: #ffff00;
        }

        p {
            margin-bottom: 40px;
        }

        form {
            display: flex;
            flex-direction: column;
            align-items: center;
        }

        input[type="text"],
        input[type="email"] {
            width: 100%;
            max-width: 400px;
            padding: 10px;
            margin-bottom: 15px;
            border-radius: 5px;
            border: none;
            outline: none;
            font-size: 16px;
        }

        button[type="submit"] {
            background-color: #007bff;
            color: white;
            padding: 10px 30px;
            border: none;
            border-radius: 5px;
            font-size: 18px;
            cursor: pointer;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .footer {
            margin-top: 40px;
            font-size: 12px;
        }

        .footer img {
            width: 40px;
            margin-top: 20px;
        }
    </style>
</head>
<body>

<div class="container">
    <div class="logo">
        <img src=".png" alt="Skadoosh Logo"> <!-- Update the path to your logo -->
    </div>
    <h1>
        <span>PULL</span> THE RUG <br>
        OUT FROM UNDER <br>
        YOUR <span class="yellow">COMPETITORS!</span>
    </h1>
    <p>
        Our face-to-face demo is available for a fee. <br>
        If you are interested, please fill out the fields below.
    </p>
    <div>
    </div>
    <form action="process_form.php" method="POST"> <!-- You can set this to your processing file -->
        <input type="text" name="first_name" placeholder="First name" required>
        <input type="text" name="last_name" placeholder="Last name" required>
        <input type="email" name="email" placeholder="Email" required>
        <input type="text" name="mp_number" placeholder="MP number" required>
        <input type="text" name="facebook" placeholder="Facebook" required>
        <button type="submit">SEND</button>
    </form>

    <div class="footer">
        Fee, schedule options, and other details will be sent through your email.
        <div>
            <img src="logo.png" alt="Footer Logo"> <!-- Update the path to your footer logo -->
        </div>
        <p>Copyright © 2024 Edgepoint Solutions, Inc. All rights reserved.</p>
    </div>
</div>

</body>
</html>
