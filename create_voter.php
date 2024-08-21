
<?php
session_start();
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create Voter</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f9;
            color: #333;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            margin-top: 30px;
            background: #fff;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
            padding: 20px;
            max-width: 600px;
            width: 100%;
        }

        h1 {
            color: #007bff;
            margin-bottom: 20px;
            font-size: 24px;
            text-align: center;
        }

        form {
            display: grid;
            gap: 15px;
        }

        label {
            font-weight: bold;
            display: block;
            margin-bottom: 5px;
        }

        input[type="text"],
        input[type="email"],
        input[type="date"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }

        button {
            background-color: #007bff;
            color: #fff;
            border: none;
            padding: 10px 20px;
            border-radius: 4px;
            cursor: pointer;
            font-size: 16px;
            transition: background-color 0.3s ease;
        }

        button:hover {
            background-color: #0056b3;
        }

        .form-group {
            margin-bottom: 15px;
        }

        .form-group input {
            width: calc(100% - 22px);
            padding: 10px;
            border-radius: 4px;
            border: 1px solid #ddd;
        }
    </style>
</head>

<body>
    <div class="container">
        <h2>Create Voter</h2>
        <form action="save_voter.php" method="post">
            <div class="form-group">
                <label for="name">Voter's Name:</label>
                <input type="text" id="name" name="name">
            </div>

            <div class="form-group">
                <label for="address">Voter's Address:</label>
                <input type="text" id="address" name="address">
            </div>

            <div class="form-group">
                <label for="sex">Sex:</label>
                <input type="text" id="sex" name="sex">
            </div>

            <div class="form-group">
                <label for="birthdate">Birthdate:</label>
                <input type="date" id="birthdate" name="birthdate">
            </div>

            <div class="form-group">
                <label for="province">Province:</label>
                <input type="text" id="province" name="province">
            </div>

            <div class="form-group">
                <label for="city">City/Municipality:</label>
                <input type="text" id="city" name="city">
            </div>

            <div class="form-group">
                <label for="barangay">Barangay:</label>
                <input type="text" id="barangay" name="barangay">
            </div>

            <div class="form-group">
                <label for="precinct">Precinct:</label>
                <input type="text" id="precinct" name="precinct">
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email">
            </div>

            <div class="form-group">
                <label for="fb">Facebook:</label>
                <input type="text" id="fb" name="fb">
            </div>

            <div class="form-group">
                <label for="cp">Cellphone Number:</label>
                <input type="text" id="cp" name="cp">
            </div>

            <div class="form-group">
                <label for="governor">Governor Preference:</label>
                <input type="text" id="governor" name="governor">
            </div>

            <div class="form-group">
                <label for="congressman">Second District Congressman Preference:</label>
                <input type="text" id="congressman" name="congressman">
            </div>

            <div class="form-group">
                <label for="mayor">Mayor Preference:</label>
                <input type="text" id="mayor" name="mayor">
            </div>

            <button type="submit">Save Voter</button>
        </form>
    </div>
</body>

</html>