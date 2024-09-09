<?php
include 'config.php';
if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: login.php");
    exit;
}
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Insert plan details into the `plans` table
    $name = $_POST['plan_name'];
    $vision = $_POST['vision'];
    $mission = $_POST['mission'];
    $strategies = $_POST['strategies'];
    $target_number = $_POST['target_number'];
    $target_percentage = $_POST['target_percentage'];
    $province = $_POST['province'];
    $region = $_POST['region'];
    $provincial = $_POST['provincial'];
    $city = $_POST['city'];
    $barangay = $_POST['barangay'];
    $precinct = $_POST['precinct'];
    $created_by = 1; // Assuming admin is logged in with ID 1

    $query = "INSERT INTO plans (name, vision, mission, strategies, target_number, target_percentage, province, region, provincial, city, barangay, precinct, created_by, created_at) VALUES ('$name', '$vision', '$mission', '$strategies', $target_number, $target_percentage, '$province', '$region', '$provincial', '$city', '$barangay', '$precinct', $created_by, NOW())";
    mysqli_query($conn, $query);

    $plan_id = mysqli_insert_id($conn); // Get the newly created plan ID

    // Insert activities into the `activities` table
    foreach ($_POST['activities'] as $activity) {
        $name = $activity['name'];
        $location = $activity['location'];
        $assigned_person = $activity['assigned_person'];
        $date = $activity['date'];
        $budget = $activity['budget'];

        $query = "INSERT INTO activities (plan_id, name, location, assigned_person, date, budget, created_at) VALUES ($plan_id, '$name', '$location', '$assigned_person', '$date', $budget, NOW())";
        mysqli_query($conn, $query);
    }

    // Redirect to the view plan page
    header("Location: view_plan.php?id=$plan_id");
    exit;
}

?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Create New Plan</title>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link rel="stylesheet" href="styles.css">
    <style>
        body {
            font-family: 'Arial', sans-serif;
            background: #f4f7f6;
            margin: 0;
            padding: 0;
            display: flex;
            justify-content: center;
            align-items: center;
        }

        .container {
            margin-top: 30px;
            background: #fff;
            padding: 2rem;
            border-radius: 10px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            max-width: 800px;
            width: 100%;
        }

        h1 {
            font-size: 2.5rem;
            color: #333;
            text-align: center;
            margin-bottom: 1.5rem;
        }

        form div {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            font-weight: bold;
            margin-bottom: 0.5rem;
            color: #555;
        }

        input[type="text"],
        input[type="number"],
        input[type="date"],
        textarea {
            width: 100%;
            padding: 0.75rem;
            border: 1px solid #ddd;
            border-radius: 5px;
            font-size: 1rem;
            transition: border-color 0.3s ease;
        }

        input[type="text"]:focus,
        input[type="number"]:focus,
        input[type="date"]:focus,
        textarea:focus {
            border-color: #0056b3;
        }

        textarea {
            height: 100px;
        }

        #activities .activity {
            padding: 1rem;
            background: #f9f9f9;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-bottom: 1rem;
        }

        #add_activity {
            display: block;
            margin: 1.5rem 0;
            padding: 0.75rem 1.5rem;
            background-color: #28a745;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1rem;
        }

        #add_activity:hover {
            background-color: #218838;
        }

        button[type="submit"] {
            width: 100%;
            padding: 1rem;
            background-color: #007bff;
            color: #fff;
            border: none;
            border-radius: 5px;
            cursor: pointer;
            font-size: 1.25rem;
            margin-top: 1rem;
        }

        button[type="submit"]:hover {
            background-color: #0056b3;
        }

        .activity-labels {
            display: flex;
            justify-content: space-between;
            margin-top: 1rem;
        }

        .activity-labels label {
            flex: 1;
            margin-right: 0.5rem;
        }

        .activity-labels label:last-child {
            margin-right: 0;
        }

        .activity-inputs {
            display: flex;
            justify-content: space-between;
        }

        .activity-inputs input {
            flex: 1;
            margin-right: 0.5rem;
        }

        .activity-inputs input:last-child {
            margin-right: 0;
        }
        /* Style for the select dropdowns */
select {
    width: 100%;
    padding: 0.75rem;
    border: 1px solid #ddd;
    border-radius: 5px;
    font-size: 1rem;
    cursor: pointer;
    transition: border-color 0.3s ease;
}

select:focus {
    border-color: #0056b3;
    outline: none;
}

/* Style for the options within the dropdown */
select option {
    padding: 10px;
    background-color: #fff;
    color: #333;
    border-bottom: 1px solid #ddd;
    transition: background-color 0.3s ease;
}

/* Hover effect for options */
select option:hover {
    background-color: #f0f0f0;
}

.planlist-back {
            position: fixed;
            float: left;
            left: 20px;
            bottom: 30px;
            width: 50px;
            height: 50px;
        }
        .planlist-back img{
            width: 100%;
            cursor: pointer;
        }



    </style>
</head>
<body>
    <div class="container">
        <h1>Create New Plan</h1>
        <form method="post" action="create_plan.php">
            <div>
                <label for="plan_name">Plan Name:</label>
                <input type="text" id="plan_name" name="plan_name" required>
            </div>
            <div>
                <label for="vision">Vision:</label>
                <textarea id="vision" name="vision" required></textarea>
            </div>
            <div>
                <label for="mission">Mission:</label>
                <textarea id="mission" name="mission" required></textarea>
            </div>
            <div>
                <label for="strategies">Strategies:</label>
                <textarea id="strategies" name="strategies" required></textarea>
            </div>
            <div>
                <label for="target_number">Target Number:</label>
                <input type="number" id="target_number" name="target_number" required>
            </div>
            <div>
                <label for="target_percentage">Target Percentage:</label>
                <input type="number" step="0.01" id="target_percentage" name="target_percentage" required>
            </div>
            <div>
                <label for="province">Province:</label>
                <input type="text" id="province" name="province" required>
            </div>
            <div>
    <label for="provincial">Provincial:</label>
    <select id="provincial" name="provincial" required>
        <option value="National">National</option>
        <option value="Regional">Regional</option>
        <option value="First District">First District</option>
        <option value="Second District">Second District</option>
        <option value="Third District">Third District</option>
    </select>
</div>
<div>
    <label for="city">City:</label>
    <select id="city" name="city" required>
        <option value="Agdangan">Agdangan</option>
        <option value="Alabat">Alabat</option>
        <option value="Atimonan">Atimonan</option>
        <option value="Buenavista">Buenavista</option>
        <option value="Burdeos">Burdeos</option>
        <option value="Calauag">Calauag</option>
        <option value="Catanauan">Catanauan</option>
        <option value="Dolores">Dolores</option>
        <option value="General Luna">General Luna</option>
    </select>
</div>
<div>
    <label for="barangay">Barangay:</label>
    <select id="barangay" name="barangay" required>
        <option value="A. Mabini, Guinayangan">A. Mabini, Guinayangan</option>
        <option value="Abang, Lucban">Abang, Lucban</option>
        <option value="Abiawin, Infanta">Abiawin, Infanta</option>
        <option value="Abo-abo, Mauban">Abo-abo, Mauban</option>
        <option value="Abuyon, San Narciso">Abuyon, San Narciso</option>
        <option value="Adia Bitago, Gen. Nakar">Adia Bitago, Gen. Nakar</option>
        <option value="Agaoho, Calauag">Agaoho, Calauag</option>
        <option value="Agos-agos, Infanta">Agos-agos, Infanta</option>
        <option value="Ajus, Catanauan">Ajus, Catanauan</option>
    </select>
</div>
<div>
    <label for="precinct">Precinct:</label>
    <select id="precinct" name="precinct" required>
        <option value="0022A">0022A</option>
        <option value="0022B">0022B</option>
        <option value="0022C">0022C</option>
        <option value="0022D">0022D</option>
        <option value="0022E">0022E</option>
        <option value="0022F">0022F</option>
        <option value="0022G">0022G</option>
        <option value="0022H">0022H</option>
        <option value="0022I">0022I</option>
        <option value="0022J">0022J</option>
    </select>
</div>

            <div>
                <label for="region">Region:</label>
                <input type="text" id="region" name="region" required>
            </div>

            <h2>Activities</h2>
            <div id="activities">
                <div class="activity">
                    <div class="activity-labels">
                        <label for="activity_name_1">Activity Name:</label>
                        <label for="location_1">Location:</label>
                    </div>
                    <div class="activity-inputs">
                        <input type="text" id="activity_name_1" name="activities[0][name]" required>
                        <input type="text" id="location_1" name="activities[0][location]" required>
                    </div>
                    <div class="activity-labels">
                        <label for="assigned_person_1">Assigned Person:</label>
                        <label for="date_1">Date:</label>
                    </div>
                    <div class="activity-inputs">
                        <input type="text" id="assigned_person_1" name="activities[0][assigned_person]" required>
                        <input type="date" id="date_1" name="activities[0][date]" required>
                    </div>
                    <div class="activity-labels">
                        <label for="budget_1">Budget:</label>
                    </div>
                    <div class="activity-inputs">
                        <input type="number" step="0.01" id="budget_1" name="activities[0][budget]" required>
                    </div>
                </div>
            </div>
            <button type="button" id="add_activity"><i class="fas fa-plus"></i> Add Another Activity</button>
            <button type="submit"><i class="fas fa-save"></i> Create Plan</button>
        </form>
    </div>
    <div class="planlist-back"><img src="https://svgrepo.com/show/67631/back-arrow.svg" onclick="window.history.back()"></img></div>
    <script>
        document.querySelectorAll('.option').forEach(option => {
            option.addEventListener('click', function() {
                const type = this.getAttribute('data-type');
                const value = this.getAttribute('data-value');
                window.location.href = `filtered-options.html?type=${type}&value=${encodeURIComponent(value)}`;
            });
        });
        document.getElementById('add_activity').addEventListener('click', function () {
            const activitiesDiv = document.getElementById('activities');
            const activityCount = activitiesDiv.getElementsByClassName('activity').length;
            const newActivity = document.createElement('div');
            newActivity.className = 'activity';
            newActivity.innerHTML = `
                <div class="activity-labels">
                    <label for="activity_name_${activityCount + 1}">Activity Name:</label>
                    <label for="location_${activityCount + 1}">Location:</label>
                </div>
                <div class="activity-inputs">
                    <input type="text" id="activity_name_${activityCount + 1}" name="activities[${activityCount}][name]" required>
                    <input type="text" id="location_${activityCount + 1}" name="activities[${activityCount}][location]" required>
                </div>
                <div class="activity-labels">
                    <label for="assigned_person_${activityCount + 1}">Assigned Person:</label>
                    <label for="date_${activityCount + 1}">Date:</label>
                </div>
                <div class="activity-inputs">
                    <input type="text" id="assigned_person_${activityCount + 1}" name="activities[${activityCount}][assigned_person]" required>
                    <input type="date" id="date_${activityCount + 1}" name="activities[${activityCount}][date]" required>
                </div>
                <div class="activity-labels">
                    <label for="budget_${activityCount + 1}">Budget:</label>
                </div>
                <div class="activity-inputs">
                    <input type="number" step="0.01" id="budget_${activityCount + 1}" name="activities[${activityCount}][budget]" required>
                </div>
            `;
            activitiesDiv.appendChild(newActivity);
        });
    </script>
</body>
</html>
