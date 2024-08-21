<?php
function calculateSampleSize($population, $confidenceLevel, $marginOfError) {
    // Z-score for confidence level
    $z = 1.96; // for 95% confidence level

    $p = 0.5; // estimated proportion of the population
    $e = $marginOfError / 100; // convert percentage to decimal

    // Sample size formula
    $sampleSize = ($z * $z * $p * (1 - $p)) / ($e * $e);
    $sampleSize = $sampleSize / (1 + (($sampleSize - 1) / $population));

    return round($sampleSize);
}

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    $population = intval($_POST['population']);
    $confidenceLevel = floatval($_POST['confidence_level']);
    $marginOfError = floatval($_POST['margin_of_error']);

    $sampleSize = calculateSampleSize($population, $confidenceLevel, $marginOfError);

    echo "<script>alert('Calculated sample size: $sampleSize');</script>";
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Sample Size Calculator</title>
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: linear-gradient(to right, #4facfe, #00f2fe);
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
            height: 100vh;
        }
        .container {
            background-color: white;
            padding: 30px;
            border-radius: 10px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
            max-width: 400px;
            width: 100%;
            text-align: center;
        }
        h1 {
            font-size: 24px;
            color: #333;
            margin-bottom: 20px;
        }
        label {
            display: block;
            font-size: 14px;
            color: #555;
            margin-bottom: 8px;
            text-align: left;
        }
        input[type="number"] {
            width: calc(100% - 20px);
            padding: 10px;
            margin-bottom: 20px;
            border: 1px solid #ccc;
            border-radius: 5px;
            font-size: 16px;
            color: #333;
            box-sizing: border-box;
        }
        button {
            background-color: #4facfe;
            color: white;
            padding: 10px 20px;
            border: none;
            border-radius: 5px;
            font-size: 16px;
            cursor: pointer;
            transition: background-color 0.3s ease;
        }
        button:hover {
            background-color: #00c6ff;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>Sample Size Calculator</h1>
        <form method="POST">
            <label for="population">Population Size</label>
            <input type="number" id="population" name="population" required>

            <label for="confidence_level">Confidence Level (%)</label>
            <input type="number" id="confidence_level" name="confidence_level" value="95" required>

            <label for="margin_of_error">Margin of Error (%)</label>
            <input type="number" id="margin_of_error" name="margin_of_error" value="5" required>

            <button type="submit">Calculate Sample Size</button>
        </form>
    </div>
</body>
</html>
