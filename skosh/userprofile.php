<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>User Profile</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }
        .navbar {
            background-color: #030b32;
            color: white;
            padding: 15px;
            text-align: center;
        }
        .navbar h1 {
            margin: 0;
            font-size: 24px;
        }
        .container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .section {
            margin-bottom: 40px;
        }
        .section h2 {
            background-color: #ffc107;
            padding: 10px;
            border-radius: 5px;
            font-size: 20px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group textarea, .form-group select {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 16px;
        }
        .form-group button {
            padding: 10px 20px;
            background-color: #007bff;
            color: white;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 16px;
        }
        .form-group button:hover {
            background-color: #0056b3;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>User Profile</h1>
</div>

<div class="container">
    <div class="section">
        <h2>Basic Information</h2>
        <form>
            <div class="form-group">
                <label for="name">Voter's Name</label>
                <input type="text" id="name" name="name" placeholder="Enter voter's name">
            </div>
            <div class="form-group">
                <label for="address">Voter's Address</label>
                <input type="text" id="address" name="address" placeholder="Enter voter's address">
            </div>
            <div class="form-group">
                <label for="sex">Sex</label>
                <select id="sex" name="sex">
                    <option value="Male">Male</option>
                    <option value="Female">Female</option>
                </select>
            </div>
            <div class="form-group">
                <label for="birthdate">Birthdate</label>
                <input type="date" id="birthdate" name="birthdate">
            </div>
            <div class="form-group">
                <label for="province">Province</label>
                <input type="text" id="province" name="province" placeholder="Enter province">
            </div>
            <div class="form-group">
                <label for="city">City/Municipality</label>
                <input type="text" id="city" name="city" placeholder="Enter city/municipality">
            </div>
            <div class="form-group">
                <label for="barangay">Barangay</label>
                <input type="text" id="barangay" name="barangay" placeholder="Enter barangay">
            </div>
            <div class="form-group">
                <label for="precinct">Precinct</label>
                <input type="text" id="precinct" name="precinct" placeholder="Enter precinct">
            </div>
            <div class="form-group">
                <label for="email">Email</label>
                <input type="email" id="email" name="email" placeholder="Enter email">
            </div>
            <div class="form-group">
                <label for="facebook">Facebook Profile</label>
                <input type="text" id="facebook" name="facebook" placeholder="Enter Facebook profile link">
            </div>
            <div class="form-group">
                <label for="contact">Contact Number</label>
                <input type="tel" id="contact" name="contact" placeholder="Enter contact number">
            </div>
        </form>
    </div>

    <div class="section">
        <h2>Preferences</h2>
        <form>
            <div class="form-group">
                <label for="color">Favorite Color</label>
                <input type="text" id="color" name="color" placeholder="Enter favorite color">
            </div>
            <div class="form-group">
                <label for="artist">Favorite Artist</label>
                <input type="text" id="artist" name="artist" placeholder="Enter favorite artist">
            </div>
            <div class="form-group">
                <label for="songs">Type of Songs Preferred</label>
                <input type="text" id="songs" name="songs" placeholder="Enter type of songs preferred">
            </div>
        </form>
    </div>

    <div class="section">
        <h2>Problems in Barangay</h2>
        <form>
            <div class="form-group">
                <label for="problem1">Top Problem 1</label>
                <input type="text" id="problem1" name="problem1" placeholder="Enter top problem 1">
            </div>
            <div class="form-group">
                <label for="problem2">Top Problem 2</label>
                <input type="text" id="problem2" name="problem2" placeholder="Enter top problem 2">
            </div>
            <div class="form-group">
                <label for="problem3">Top Problem 3</label>
                <input type="text" id="problem3" name="problem3" placeholder="Enter top problem 3">
            </div>
        </form>
    </div>

    <div class="section">
        <h2>Political Preferences</h2>
        <form>
            <div class="form-group">
                <label for="senator">Senator Preference</label>
                <input type="text" id="senator" name="senator" placeholder="Enter senator preference">
            </div>
            <div class="form-group">
                <label for="congressman">Congressman Preference</label>
                <input type="text" id="congressman" name="congressman" placeholder="Enter congressman preference">
            </div>
            <div class="form-group">
                <label for="mayor">Mayor Preference</label>
                <input type="text" id="mayor" name="mayor" placeholder="Enter mayor preference">
            </div>
            <div class="form-group">
                <label for="board_member">Board Member Preference</label>
                <input type="text" id="board_member" name="board_member" placeholder="Enter board member preference">
            </div>
            <div class="form-group">
                <label for="captain">Barangay Captain Preference</label>
                <input type="text" id="captain" name="captain" placeholder="Enter barangay captain preference">
            </div>
            <div class="form-group">
                <button type="submit">Save Profile</button>
            </div>
        </form>
    </div>
</div>

</body>
</html>
