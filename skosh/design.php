<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Size Calculator</title>
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
            max-width: 600px;
            margin: 20px auto;
            padding: 20px;
            background-color: white;
            box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1);
            border-radius: 8px;
        }
        .form-group {
            margin-bottom: 20px;
        }
        .form-group label {
            display: block;
            font-weight: bold;
            margin-bottom: 5px;
        }
        .form-group input, .form-group select {
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
        .result {
            margin-top: 20px;
            padding: 10px;
            background-color: #d4edda;
            border: 1px solid #c3e6cb;
            border-radius: 5px;
            color: #155724;
        }
    </style>
</head>
<body>

<div class="navbar">
    <h1>Sample Size Calculator</h1>
</div>
<div class="form-group">
    <label for="confidenceLevel">Confidence Level (%)</label>
    <select id="confidenceLevel">
        <option value="90">90%</option>
        <option value="95" selected>95%</option>
        <option value="99">99%</option>
    </select>
</div>

<div class="form-group">
    <button type="button" onclick="calculateSampleSize()">Calculate Sample Size</button>
</div>

<div id="result" class="result" style="display:none;">
    <p>Sample Size: <span id="sampleSize"></span></p>
    <p>Interval: <span id="interval"></span></p>
</div>

<script>
function calculateSampleSize() {
    const populationSize = document.getElementById('populationSize').value;
    const marginOfError = document.getElementById('marginOfError').value / 100;
    const confidenceLevel = document.getElementById('confidenceLevel').value;

    if (!populationSize || !marginOfError) {
        alert('Please enter all values.');
        return;
    }

    let z;
    if (confidenceLevel == 90) z = 1.645;
    if (confidenceLevel == 95) z = 1.96;
    if (confidenceLevel == 99) z = 2.576;

    const p = 0.5;
    const numerator = Math.pow(z, 2) * p * (1 - p);
    const denominator = Math.pow(marginOfError, 2);
    const sampleSize = Math.round((numerator / denominator) / (1 + (numerator / denominator) / populationSize));

    const interval = Math.round(populationSize / sampleSize);

    document.getElementById('sampleSize').innerText = sampleSize;
    document.getElementById('interval').innerText = interval;

    document.getElementById('result').style.display = 'block';
}
</script>

</div>

</body>
</html>
