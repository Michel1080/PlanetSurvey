<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Survey Management</title>
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
        .grid-container {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
            gap: 20px;
        }
        .card {
            background-color: #e7e7e7;
            padding: 20px;
            border-radius: 8px;
            text-align: center;
            box-shadow: 0px 0px 5px rgba(0, 0, 0, 0.1);
        }
        .card h3 {
            margin-bottom: 15px;
            font-size: 18px;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Survey Management System</h1>
</div>

<div class="container">
    <div class="section">
        <h2>Create Survey</h2>
        <form>
            <div class="form-group">
                <label for="surveyName">Name of Survey</label>
                <input type="text" id="surveyName" name="surveyName" placeholder="Enter survey name" required>
            </div>
            <div class="form-group">
                <label for="voterProfile">Voters Profile</label>
                <textarea id="voterProfile" name="voterProfile" placeholder="Enter voter profile" rows="4"></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Save Survey</button>
            </div>
        </form>
    </div>

    <div class="section">
        <h2>Survey Forms</h2>
        <form>
            <div class="form-group">
                <label for="questions">List of Questions</label>
                <textarea id="questions" name="questions" placeholder="Enter questions" rows="4"></textarea>
            </div>
            <div class="form-group">
                <label for="respondentsProfile">Profile of Respondents</label>
                <textarea id="respondentsProfile" name="respondentsProfile" placeholder="Enter respondents profile" rows="4"></textarea>
            </div>
            <div class="form-group">
                <button type="submit">Save Data</button>
                <button type="submit">Submit Data</button>
            </div>
        </form>
    </div>

    <div class="section">
        <h2>Analytics</h2>
        <div class="grid-container">
            <div class="card">
                <h3>Generate Graph</h3>
                <button type="button">Generate</button>
            </div>
            <div class="card">
                <h3>Analyze</h3>
                <button type="button">Analyze</button>
            </div>
        </div>
    </div>

    <div class="section">
        <h2>Manage Data</h2>
        <div class="grid-container">
            <div class="card">
                <h3>Download</h3>
                <button type="button">Download</button>
            </div>
            <div class="card">
                <h3>Upload/Submit</h3>
                <button type="button">Upload</button>
            </div>
            <div class="card">
                <h3>Delete</h3>
                <button type="button">Delete</button>
            </div>
            <div class="card">
                <h3>Update</h3>
                <button type="button">Update</button>
            </div>
            <div class="card">
                <h3>Data Logs</h3>
                <button type="button">View Logs</button>
            </div>
        </div>
    </div>
</div>

</body>
</html>
