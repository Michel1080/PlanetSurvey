<?php
include('config.php');
require 'vendor/autoload.php';

use PhpOffice\PhpSpreadsheet\IOFactory;

$spreadsheet = IOFactory::load('data.xlsx');
$sheet = $spreadsheet->getActiveSheet();
$categories = [
    'PROVINCE' => [],
    'CITY' => [],
    'BARANGAY' => [],
    'PRECINCT' => [],
    'GOVERNOR PREFERENCE' => [],
    'SECOND DISTRICT CONGRESSMAN PREFERENCE' => [],
    'MAYOR PREFERENCE' => [],
    'SEX' => []
];
$rowNumber = 0;
$highestColumn = $sheet->getHighestColumn();
$highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
$dataArray = $sheet->toArray();
for ($i=11; $i<$highestColumnIndex; $i++){
   $fieldary[] =  $dataArray[0][$i];
}
// var_dump($filedary);exit;
$voters = [];
foreach ($sheet->getRowIterator() as $row) {
    $cellIterator = $row->getCellIterator();
    $cellIterator->setIterateOnlyExistingCells(false);    
    if ($rowNumber === 0) {
        $rowNumber++;
        continue;
    }
    $data = [];
    foreach ($cellIterator as $cell) {
        $data[] = $cell->getValue();
    }
    // var_dump($data);exit;
    if (count($data) >= 14) {
        $categories['PROVINCE'][$data[4]] = ($categories['PROVINCE'][$data[4]] ?? 0) + 1;
        $categories['CITY'][$data[5]] = ($categories['CITY'][$data[5]] ?? 0) + 1;
        $categories['BARANGAY'][$data[6]] = ($categories['BARANGAY'][$data[6]] ?? 0) + 1;
        $categories['PRECINCT'][$data[7]] = ($categories['PRECINCT'][$data[7]] ?? 0) + 1;
        $categories['GOVERNOR PREFERENCE'][$data[11]] = ($categories['GOVERNOR PREFERENCE'][$data[11]] ?? 0) + 1;
        $categories['SECOND DISTRICT CONGRESSMAN PREFERENCE'][$data[12]] = ($categories['SECOND DISTRICT CONGRESSMAN PREFERENCE'][$data[12]] ?? 0) + 1;
        $categories['MAYOR PREFERENCE'][$data[13]] = ($categories['MAYOR PREFERENCE'][$data[13]] ?? 0) + 1;
        $categories['SEX'][$data[2]] = ($categories['SEX'][$data[2]] ?? 0) + 1;
        $voter = [
            'name' => $data[0],
            'address' => $data[1],
            'sex' => $data[2],
            'birthdate' => $data[3],
            'province' => $data[4],
            'city' => $data[5],
            'barangay' => $data[6],
            'precinct' => $data[7],
            'email' => $data[8],
            'fb' => $data[9],
            'cpNo' => $data[10]
            // foreach($fieldary as $field){
            //     'governorPreference' => $data[11],
            //     'secondDistrictCongressmanPreference' => $data[12],
            //     'mayorPreference' => $data[13]
            // }
           
        ];
        $i = 11;
        foreach($fieldary as $field){
            // echo $field;exit;
            $voter[$field] = $data[$i];
            $i++;
        }
        $voters[] = $voter;
    }
    $rowNumber++;
}
// var_dump($categories);exit;
foreach ($categories as $key => $data) {
    $totalVotes = array_sum($data);
    foreach ($data as $location => $count) {
        $categories[$key][$location] = ($count / $totalVotes) * 100;
    }
}
// var_dump($voters);exit;
$categoriesJson = json_encode($categories);
$votersJson = json_encode($voters);
$fieldsJson = json_encode($fieldary);














$allManagers = IOFactory::load('manager.xlsx');
$sheetManager = $allManagers->getActiveSheet();
$categoriesManager = [
    'Name' => [],
    'TYPE' => [],
    'Staff' => [],
    'City' => [],
    'BARANGAY' => [],
    'PRECINT' => [],
];
$rowNumberManager = 0;
foreach ($sheetManager->getRowIterator() as $rowManager) {
    if ($rowNumberManager === 0) {
        $rowNumberManager++;
        continue;
    }
    $cellIteratorManagers = $rowManager->getCellIterator();
    $cellIteratorManagers->setIterateOnlyExistingCells(false);
    $dataManager = [];
    foreach ($cellIteratorManagers as $cell) {
        $dataManager[] = $cell->getValue();
    }
    $categoriesManager['TYPE'][$dataManager[0]] = ($categoriesManager['TYPE'][$dataManager[0]] ?? 0) + 1;
    $categoriesManager['CITY'][$dataManager[2]] = ($categoriesManager['CITY'][$dataManager[2]] ?? 0) + 1;
    $categoriesManager['BARANGAY'][$dataManager[3]] = ($categoriesManager['BARANGAY'][$dataManager[3]] ?? 0) + 1;
    $categoriesManager['PRECINCT'][$dataManager[4]] = ($categoriesManager['PRECINCT'][$dataManager[4]] ?? 0) + 1;
    $categoriesManager['NAME'][$dataManager[5]] = ($categoriesManager['NAME'][$dataManager[5]] ?? 0) + 1;
    $managers[] = [
        'name' => $dataManager[5],
        'city' => $dataManager[2],
        'type' => $dataManager[0],
        'barangay' => $dataManager[3],
        'precinct' => $dataManager[4],
    ];
    $rowNumberManager++;
}
foreach ($categoriesManager as $key => $dataManager) {
    $totalVotes = array_sum($dataManager);
    foreach ($dataManager as $location => $count) {
        $categoriesManager[$key][$location] = ($count / $totalVotes) * 100;
    }
}

$categoriesManagerJson = json_encode($categoriesManager);
$managersJson = json_encode($managers);


if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}
$user_name = $_SESSION['user_name'];
$role = '';
if (!empty($_SESSION['user'])) {
    $role = $_SESSION['user'];
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <title>Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 0;
        }

        .container {
            width: 95%;
            /* max-width: 1400px; */
            margin: 0 auto;
            background-color: white;
            padding: 20px;
            border-radius: 10px;
            box-shadow: 0 0 20px rgba(0, 0, 0, 0.1);
            margin-top: 50px;
            /* position: relative; */
        }

        .header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            border-bottom: 2px solid #ddd;
            padding-bottom: 10px;
        }

        .header h1 {
            font-size: 24px;
            color: #333;
        }

        .header .date {
            font-size: 16px;
            color: #999;
        }

        .tabs {
            display: flex;
            cursor: pointer;
        }

        .tabs div {
            padding: 10px;
            background-color: #f0f0f0;
            margin-right: 5px;
        }

        .tabs div.active {
            background-color: #ccc;
        }

        .tab-content {
            display: none;
            /* border: 1px solid #ccc; */
        }

        .tab-content.active {
            display: block;
        }

        .menu-bar {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            background-color: #f9f9f9;
            padding: 10px 0;
            margin: 20px 0;
            border-radius: 5px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .menu-bar a {
            text-decoration: none;
            color: #333;
            font-weight: bold;
            padding: 10px 15px;
            border-radius: 5px;
            transition: background-color 0.3s ease;
        }

        .menu-bar a:hover {
            background-color: #4CAF50;
            color: white;
        }

        .graphs {
            display: flex;
            justify-content: space-between;
            gap: 20px;
            margin: 20px 0;
        }

        .graph {
            width: 48%;
            background-color: #f9f9f9;
            padding: 15px;
            border-radius: 10px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .graph h2 {
            font-size: 18px;
            margin-bottom: 15px;
            text-align: center;
        }

        .graph img {
            width: 100%;
            border-radius: 10px;
        }

        .dropdown-container {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin: 10px 0;
        }

        .dropdown-container select,
        .dropdown-container input[type="text"] {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 5px;
            margin-left: 10px;
        }

        .menu {
            display: grid;
            grid-template-columns: repeat(8, 1fr);
            gap: 10px;
            margin-top: 20px;
        }

        .menu-item {
            padding: 15px;
            background-color: #f9f9f9;
            /* display: flex; */
            justify-content: space-between;
            border-radius: 10px;
            /* box-shadow: 0 0 10px rgba(0, 0, 0, 0.1); */
        }

        .menu-item h3 {
            margin-bottom: 10px;
            color: #333;
            border-bottom: 2px solid #4CAF50;
            padding-bottom: 5px;
            /* margin-top: 0px; */
        }

        .menu-item p a,
        .menu-item p {
            margin: 5px 0;
            cursor: pointer;
            color: #333;
            text-decoration: none;
        }

        .menu-item p:hover {
            color: #4CAF50;
            text-decoration: underline;
        }

        /* Popup container - can be styled as you want */
        .popup {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgb(0, 0, 0);
            background-color: rgba(0, 0, 0, 0.4);
        }

        .popup-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }

        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }

        .chat_link{
            position: fixed;
            bottom: 30px;
            right: 10px;
            z-index: 100;
            /* width: 50px;
            height: 50px; */
        }

        /* List of search results */
        #voterSearchResults,
        #voterBirthdayResults,
        #topManagersResults,
        #voterAddressResults {
            list-style-type: none;
            padding: 0;
        }

        #popupResults {
            list-style-type: none;
            padding: 0;
        }

        #popupResults li {
            cursor: pointer;
            padding: 14px 5px;
            border-bottom: 1px solid #ddd;
        }

        #popupResults li:hover {
            background-color: #f0f0f0;
        }

        #voterSearchResults li,
        #topManagersResults li,
        #voterBirthdayResults li {
            cursor: pointer;
            padding: 14px 5px;
            border-bottom: 1px solid #ddd;
        }

        #voterSearchResults li:hover,
        #topManagersResults li:hover {
            background-color: #f0f0f0;
        }

        /* Voter details section */
        #voterDetails {
            margin-top: 20px;
        }

        #voterAddressInput,
        #voterBirthdayInput,
        #voterSearchInput {
            width: 30%;
            border-radius: 4px;
            border: 1px solid #a69f9f;
            padding: 10px;
        }

        .search-gather-button,
        #backButton,
        .meet-buttons {
            background: #4CAF50;
            color: white;
            border: none;
            padding: 10px 40px;
            border-radius: 6px;
            cursor: pointer;
        }

        #voterAddressResults li {
            cursor: pointer;
            padding: 14px 5px;
            border-bottom: 1px solid #ddd;
        }

        #voterAddressResults li:hover {
            background-color: #f0f0f0;
        }
    </style>
</head>

<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>SKADOOSH!</h1>
            <div class="date"><?php echo date('d M Y, l'); ?></div>
            <h1>Welcome, <?php echo $user_name; ?>!</h1>
            <a href="logout.php">Logout</a>
        </div>

        <!-- Dropdown and Search Bar -->
        <div class="dropdown-container">
            
            <div id = "cityoptiondiv" style = "display:none;">
                <label for="optiontype" id = "optionlbl"></label>
                <select id="optiontype" onchange="updateData()">
                    
                </select>
            </div>
            <div id = "surveydiv" style = "display:none">
                <label for="governor-preference">SURVEY QUESTIONS:</label>
                <select id="governor-preference" onchange="titleChange(this)">
                    <!-- <option value=""></option>
                    <option value="governorPreference">GOVERNOR PREFERENCE</option>
                    <option value="secondDistrictCongressmanPreference">SECOND DISTRICT CONGRESSMAN PREFERENCE</option>
                    <option value="mayorPreference">MAYOR PREFERENCE -->
                    </option>
                    <!-- Add more options as needed -->
                </select>
            </div>
            <div>
                <!-- <input type="text" name="Search" placeholder="Search..."> -->
            </div>
        </div>

        <!-- Graphs -->
         <?php if(!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'user'){ ?>
        <div class="graphs">
            <div class="graph">
                <h2 id = "barchart_title">Governor Preference</h2>
                <canvas id="myBarChart" width="400" height="200"></canvas> <!-- Corrected ID -->
            </div>
            <div class="graph">
                <h2>Trend Analysis</h2>
                <canvas id="myLineChart" width="400" height="200"></canvas> <!-- Corrected ID -->
            </div>
        </div>
        <?php }?>    
        <!-- Top Menu Bar -->
        <div class="menu-bar tabs">
            
            <?php

            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] == 'user') {
            } else {
                ?>
                  <a class="tab" data-tab="ANALYZE">ANALYZE</a>
            <?php } ?>
            <a class="tab" data-tab="PLAN" href="plan_index.php">PLAN</a>
            <a class="tab" data-tab="SEARCH-GATHER">SEARCH GATHER</a>
            <!-- <a class="tab" data-tab="MEET-COMM">MEET COMM</a> -->
            <a class="tab" data-tab="PROGRAMS-FIN">PROGRAMS/FIN</a>
            <?php

            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            } else {
                ?>
                
            <?php } ?>
            <a href="survey_list.php">SURVEY</a>
            <a class="tab" data-tab="SOCIAL-MEDIA">SOC. MEDIA</a>
            <?php

            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] == 'user') {
            } else {
                ?>
                <a class="tab" data-tab="MANAGE-DATA" href="list_voters.php">MANAGE DATA</a>
            <?php } ?>
            
        </div>

        <!-- Menu Items -->
        <div class="menu">

            

            <?php

            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] == 'user') {
            } else {
                ?>
                <div class="menu-item">
                    <div>
                        <h3>HISTOGRAM</h3>
                        <p data-category="PROVINCE" class="menu-item-child" data-type="HISTOGRAM">Provincial</p>
                        <p data-category="PROVINCE" class="menu-item-child" data-type="HISTOGRAM">District</p>
                        <p data-category="CITY" class="menu-item-child" data-type="HISTOGRAM">Municipal/City</p>
                        <p data-category="BARANGAY" class="menu-item-child" data-type="HISTOGRAM">Barangay</p>
                        <p data-category="PRECINCT" class="menu-item-child" data-type="HISTOGRAM">Precinct</p>
                        <p data-category="SEX" class="menu-item-child" data-type="HISTOGRAM">Gender</p>
                    </div>
                    <div>
                        <h3>LINE GRAPHS</h3>
                        <p data-category="PROVINCE" class="menu-item-child" data-type="LINE-GRAPHS">search</p>
                        <p data-category="PROVINCE" class="menu-item-child" data-type="LINE-GRAPHS">District</p>
                        <p data-category="CITY" class="menu-item-child" data-type="LINE-GRAPHS">Municipal/City</p>
                        <p data-category="BARANGAY" class="menu-item-child" data-type="LINE-GRAPHS">Barangay</p>
                        <p data-category="PRECINCT" class="menu-item-child" data-type="LINE-GRAPHS">Precinct</p>
                        <p data-category="SEX" class="menu-item-child" data-type="LINE-GRAPHS">Gender</p>
                    </div>
                    <div>
                        <h3>TABLES</h3>
                        <p>search</p>
                        <p>District</p>
                        <p>Municipal/City</p>
                        <p>Barangay</p>
                        <p>Precinct</p>
                    </div>
                    <div>
                        <h3>MAPS</h3>
                        <p>Provincial</p>
                        <p>District</p>
                        <p>Municipal/City</p>
                        <p>Barangay</p>
                        <p>Precinct</p>
                    </div>
                </div>
                <?php
            }

            ?>
            <!-- PLAN -->
            <div class="menu-item">
                <h3>PROVINCIAL</h3>
                <p class="option-filter" data-type-filter="provincial" data-value="National">National</p>
                <p class="option-filter" data-type-filter="provincial" data-value="Regional">Regional</p>
                <p class="option-filter" data-type-filter="provincial" data-value="First District">First District</p>
                <p class="option-filter" data-type-filter="provincial" data-value="Second District">Second District</p>
                <p class="option-filter" data-type-filter="provincial" data-value="Third District">Third District</p>
                <h3>MUNICIPAL/CITY</h3>
                <p class="option-filter" data-type-filter="municipal" data-value="Agdangan">Agdangan</p>
                <p class="option-filter" data-type-filter="municipal" data-value="Alabat">Alabat</p>
                <p class="option-filter" data-type-filter="municipal" data-value="Atimonan">Atimonan</p>
                <p class="option-filter" data-type-filter="municipal" data-value="Buenavista">Buenavista</p>
                <p class="option-filter" data-type-filter="municipal" data-value="Burdeos">Burdeos</p>
                <p class="option-filter" data-type-filter="municipal" data-value="Calauag">Calauag</p>
                <p class="option-filter" data-type-filter="municipal" data-value="Catanauan">Catanauan</p>
                <p class="option-filter" data-type-filter="municipal" data-value="Dolores">Dolores</p>
                <p class="option-filter" data-type-filter="municipal" data-value="General Luna">General Luna</p>

                <h3>BARANGAY</h3>
                <p class="option-filter" data-type-filter="barangay" data-value="A. Mabini, Guinayangan">A. Mabini,
                    Guinayangan
                </p>
                <p class="option-filter" data-type-filter="barangay" data-value="Abang, Lucban">Abang, Lucban</p>
                <p class="option-filter" data-type-filter="barangay" data-value="Abiawin, Infanta">Abiawin, Infanta</p>
                <p class="option-filter" data-type-filter="barangay" data-value="Abo-abo, Mauban">Abo-abo, Mauban</p>
                <p class="option-filter" data-type-filter="barangay" data-value="Abuyon, San Narciso">Abuyon, San
                    Narciso</p>
                <p class="option-filter" data-type-filter="barangay" data-value="Adia Bitago, Gen. Nakar">Adia Bitago,
                    Gen.
                    Nakar</p>
                <p class="option-filter" data-type-filter="barangay" data-value="Agaoho, Calauag">Agaoho, Calauag</p>
                <p class="option-filter" data-type-filter="barangay" data-value="Agos-agos, Infanta">Agos-agos, Infanta
                </p>
                <p class="option-filter" data-type-filter="barangay" data-value="Ajus, Catanauan">Ajus, Catanauan</p>

                <h3>PRECINCT</h3>
                <p class="option-filter" data-type-filter="precinct" data-value="0022A">0022A</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022B">0022B</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022C">0022C</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022D">0022D</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022E">0022E</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022F">0022F</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022G">0022G</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022H">0022H</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022I">0022I</p>
                <p class="option-filter" data-type-filter="precinct" data-value="0022J">0022J</p>
            </div>            

            <!-- SEARCH GATHER -->
            <div class="menu-item">
                <h3 class="search-gather-name">NAME</h3>
                <p>First Name</p>
                <p>Middle Name</p>
                <p>Last Name</p>
                <h3 class="search-gather-address">ADDRESS</h3>
                <p>Street</p>
                <p>City</p>
                <p>State</p>
                <p>Zip Code</p>
                <h3 class="search-gather-birthday">BIRTHDAY</h3>
                <p>Day</p>
                <p>Month</p>
                <p>Year</p>
            </div>

            <!-- MEET COMM. -->
            <!-- <div class="menu-item">
                <h3>EXE/COM</h3>
                <p data-type="Top Managers" class="manager-filter">Top Managers</p>
                <p data-type="Mid Managers" class="manager-filter">Mid Managers</p>
                <p data-type="Frontline Managers" class="manager-filter">Frontline Managers</p>
                <h3>CAMPAIGN STAFF</h3>
                <h4>MUN/CITY</h4>
                <p data-city="Agdangan" class="city-filter">Agdangan</p>
                <p data-city="Alabat" class="city-filter">Alabat</p>
                <p data-city="Atimonan" class="city-filter">Atimonan</p>
                <p data-city="Buenavista" class="city-filter">Buenavista</p>
                <p data-city="Burdeos" class="city-filter">Burdeos</p>
                <p data-city="Calauag" class="city-filter">Calauag</p>
                <p data-city="Candelaria" class="city-filter">Candelaria</p>
                <p data-city="Catanauan" class="city-filter">Catanauan</p>
                <p data-city="Dolores" class="city-filter">Dolores</p>
                <p data-city="General Luna" class="city-filter">General Luna</p>
                <h4>BARANGAY</h4>
                <p data-barangay="A. Mabini, Guinayangan" class="barangay-filter">A. Mabini, Guinayangan</p>
                <p data-barangay="Abang, Lucban" class="barangay-filter">Abang, Lucban</p>
                <p data-barangay="Abiawin, Infanta" class="barangay-filter">Abiawin, Infanta</p>
                <p data-barangay="Abo-abo, Mauban" class="barangay-filter">Abo-abo, Mauban</p>
                <p data-barangay="Abuyon, San Narciso" class="barangay-filter">Abuyon, San Narciso</p>
                <p data-barangay="Adia Bitago, Gen. Nakar" class="barangay-filter">Adia Bitago, Gen. Nakar</p>
                <p data-barangay="Agaoho, Calauag" class="barangay-filter">Agaoho, Calauag</p>
                <p data-barangay="Agos-agos, Infanta" class="barangay-filter">Agos-agos, Infanta</p>
                <p data-barangay="Ajus, Catanauan" class="barangay-filter">Ajus, Catanauan</p>
                <h4>PRECINCT</h4>
                <p data-precinct="0022A" class="precinct-filter">0022A</p>
                <p data-precinct="0022B" class="precinct-filter">0022B</p>
                <p data-precinct="0022C" class="precinct-filter">0022C</p>
                <p data-precinct="0022D" class="precinct-filter">0022D</p>
                <p data-precinct="0022E" class="precinct-filter">0022E</p>
                <p data-precinct="0022F" class="precinct-filter">0022F</p>
                <p data-precinct="0022G" class="precinct-filter">0022G</p>
                <p data-precinct="0022H" class="precinct-filter">0022H</p>
                <p data-precinct="0022I" class="precinct-filter">0022I</p>
                <p data-precinct="0022J" class="precinct-filter">0022J</p>
            </div> -->



            <!-- PROGRAMS/FIN. -->
            <div class="menu-item">
                <h3>EXE/COM</h3>
                <p>Managers</p>
                <p>Campaign Staff</p>
                <h4>MUN/CITY</h4>
                <p>Agdangan</p>
                <p>Alabat</p>
                <p>Atimonan</p>
                <p>Buenavista</p>
                <p>Burdeos</p>
                <p>Calauag</p>
                <p>Candelaria</p>
                <p>Catanauan</p>
                <p>Dolores</p>
                <p>General Luna</p>
                <h4>BARANGAY</h4>
                <p>A. Mabini, Guinayangan</p>
                <p>Abang, Lucban</p>
                <p>Abiawin, Infanta</p>
                <p>Abo-abo, Mauban</p>
                <p>Abuyon, San Narciso</p>
                <p>Adia Bitago, Gen. Nakar</p>
                <p>Agaoho, Calauag</p>
                <p>Agos-agos, Infanta</p>
                <p>Ajus, Catanauan</p>
                <h4>PRECINCT</h4>
                <p>0022A</p>
                <p>0022B</p>
                <p>0022C</p>
                <p>0022D</p>
                <p>0022E</p>
                <p>0022F</p>
                <p>0022G</p>
                <p>0022H</p>
                <p>0022I</p>
                <p>0022J</p>
            </div>

            <?php

            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
            } else {
                ?>

            <?php } ?>
            <!-- SURVEY -->
            <div class="menu-item">
                <h3>FORM</h3>
                <p><a href="create_survey.php">Design</a></p>
                <p>Respondents</p>
                <p>Survey</p>
            </div>
            <!-- SOC. MEDIA -->
            <div class="menu-item">
                <h3>VIDEO</h3>
                <p>Facebook</p>
                <p>Instagram</p>
                <p>Twitter</p>
                <p>YouTube</p>
                <p>Snapchat</p>
                <p>TikTok</p>
                <h3>IMAGE</h3>
                <p>Facebook</p>
                <p>Instagram</p>
                <p>Twitter</p>
                <p>YouTube</p>
                <p>Snapchat</p>
                <p>TikTok</p>
                <h3>TEXT</h3>
                <p>Facebook</p>
                <p>Instagram</p>
                <p>Twitter</p>
                <p>YouTube</p>
                <p>Snapchat</p>
                <p>TikTok</p>
                <h3>DOCUMENT</h3>
                <p>Facebook</p>
                <p>Instagram</p>
                <p>Twitter</p>
                <p>YouTube</p>
                <p>Snapchat</p>
                <p>TikTok</p>
            </div>
            <?php

            if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] == 'user') {
            } else {
                ?>
                <!-- MANAGE DATA -->
                <div class="menu-item">
                    <h3>ADD VARIABLES</h3>
                    <p><a href="list_voters.php">Voters List</a></p>
                    <p><a href="list_users.php">Users List</a></p>
                    <!-- <p>Export</p>
                    <p id = "import">Import</p>
                    <p>Logs</p>
                    <p>Edit Criteria</p>
                    <p>Password</p> -->
                </div>  
            <?php } ?>
          

            <div id = "fileuploaddiv" class = "popup">
                <div class="popup-content">
                <span class="close">&times;</span>
                    <form id="uploadForm" action="fileupload.php" method="post" enctype="multipart/form-data">
                        <label for="fileToUpload">Choose a file(only *.xlsx):</label>
                        <input type="file" id="fileToUpload" name="fileToUpload" accept=".xlsx" required>
                        <br><br>
                        <button type="submit">Upload</button>
                    </form>
                </div>
            </div>    
            <!-- Popup Modal -->
            <div id="searchPopup" class="popup">
                <div class="popup-content">
                    <span class="close">&times;</span>
                    <input type="text" id="voterSearchInput" placeholder="Search by Voter's Name...">
                    <ul id="voterSearchResults"></ul>
                    <div id="voterDetails"></div>
                </div>
            </div>

            <div id="searchAddressPopup" class="popup">
                <div class="popup-content">
                    <span class="close">&times;</span>
                    <input type="text" id="voterAddressInput" placeholder="Search by Voter's Address...">
                    <ul id="voterAddressResults"></ul>
                    <div id="voterDetailsAddress"></div>
                </div>
            </div>

            <div id="searchBirthdayPopup" class="popup">
                <div class="popup-content">
                    <span class="close">&times;</span>
                    <input type="text" id="voterBirthdayInput" placeholder="Search by Voter's Birthday...">
                    <ul id="voterBirthdayResults"></ul>
                    <div id="voterDetailsBirthday"></div>
                </div>
            </div>

            <div id="managerPopup" class="popup">
                <div class="popup-content">
                    <span class="close">&times;</span>
                    <h2 id="popupTitle"></h2>
                    <ul id="popupResults"></ul>
                    <div id="popupDetails"></div>
                </div>
            </div>


        </div>

       
    </div>
        <div class = "chat_link">
            <a href = "chat.php">
                <img src = './assets/chat.png' width="50px" height="50px"></img>
            </a>
        </div>
    </div>
</body>
<script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
<script>
    $(document).ready(function () {
        $(document).on('click', '.option-filter', function (event) {
            console.log('done');
            event.stopPropagation();

            const type = $(this).data('type-filter');
            const value = $(this).data('value');

            // Redirect to the new page with query parameters
            window.location.href = `filtered_data.php?${type}=${encodeURIComponent(value)}`;
        });
    });

    $('#import').click(function(event){
        // if (event.target.classList.contains('popup')) {
            document.getElementById("fileuploaddiv").style.display = 'block';
            document.getElementById("fileToUpload").innerHTML = "";
            // console.log("ddd");
        // }
    });

    

    window.onload = function () {
        var categories = <?php echo $categoriesJson; ?>;
        var fields = <?php echo $fieldsJson; ?>;
        // console.log(categories);
        var barCtx = document.getElementById('myBarChart').getContext('2d');
        var lineCtx = document.getElementById('myLineChart').getContext('2d');

        updateCmb();

        function updateCmb(){
            optionElement = document.getElementById("governor-preference");
            optionElement.innerHTML = "";
            const option = document.createElement('option');
                option.value = "";
                option.textContent = "";
                optionElement.appendChild(option);                        
            fields.forEach(function(value){
                const option = document.createElement('option');
                option.value = value;
                option.textContent = value;
                optionElement.appendChild(option);
            });
        }

        function updateChart(chart, data) {
            // Skip the first row (menu heading) in the data
            var labels = Object.keys(data);
            var values = Object.values(data);
            // console.log(labels.sort());
            labels = labels.map(label => label == "null" ? "Unknown" : label);
            chart.data.labels = labels;
            chart.data.datasets[0].data = values;
            chart.update();
        }

        var myBarChart = new Chart(barCtx, {
            type: 'bar',
            data: {
                labels: [],
                datasets: [{
                    label: 'Percentage',
                    data: [],
                    backgroundColor: [
                        'navy', 'red', 'yellow', 'green', 'gray'
                    ],
                    borderColor: 'black',
                    borderWidth: 1,
                    barThickness: 20
                }]
            },
            options: {
                indexAxis: 'y',
                scales: {
                    x: {
                        beginAtZero: true,
                        max: 100,
                        ticks: {
                            callback: function (value) {
                                return value + '%';
                            }
                        }
                    },
                    y: {
                        ticks: {
                            display: true
                        },
                        grid: {
                            display: false
                        }
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        var myLineChart = new Chart(lineCtx, {
            type: 'line',
            data: {
                labels: [],
                datasets: [{
                    label: 'Voter Trends',
                    data: [],
                    backgroundColor: 'rgba(0, 123, 255, 0.2)',
                    borderColor: 'rgba(0, 123, 255, 1)',
                    borderWidth: 2,
                    fill: true,
                    tension: 0.4
                }]
            },
            options: {
                scales: {
                    x: {
                        beginAtZero: true
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: false
                    }
                }
            }
        });

        // Set default data for "Provincial"
        updateChart(myBarChart, categories['PROVINCE']);
        updateChart(myLineChart, categories['PROVINCE']);
        // console.log(categories);
        titleChange = function (element){
                document.getElementById("barchart_title").innerHTML = element.options[element.selectedIndex].innerHTML;
                updateData();
        }

 

        updateData = function (){
            var element1 = document.getElementById("optiontype");
            var element2 = document.getElementById("governor-preference");
            const selecttxt1 = element1.options[element1.selectedIndex].innerHTML.trim();
            const selecttxt2 = element2.value.toString();
            const type = document.getElementById("optionlbl").innerHTML == "PROVINCE:" ? "province" : "city";

            if(selecttxt1 != "" && selecttxt2 != ""){
                // alert("a");
                var voters = <?php echo $votersJson; ?>;
                // console.log(voters);
                const filterData = voters.filter(voter => String(voter[type]).toLowerCase() === (selecttxt1.toLowerCase()));
                console.log(filterData);
                const count = filterData.length/100;
                const chartData = filterData.reduce((acc, item) => {
                    
                    // console.log(count);
                    let ftype = item[selecttxt2];  
                    // if (ftype == null) {
                    //     ftype = undefined; // Assign a default category for null values
                    // } 
                    console.log(ftype);             
                    // Increment the count for the category
                    if (!acc[ftype]) {
                        acc[ftype] = 0; // Initialize if not present
                        
                    }
                    acc[ftype] += (1 / count);
                    return acc;
                }, {});
                           
                updateChart(myBarChart, chartData);
                updateChart(myLineChart, chartData);
            }
            
        }

        document.querySelectorAll('.menu-item-child').forEach(function (item) {
            item.addEventListener('click', function (event) {
                event.preventDefault();
                event.stopPropagation();
                var category = this.getAttribute('data-category');
                var data = categories[category];
                // console.log(category);
                if (this.getAttribute('data-type') === 'HISTOGRAM') {
                    updateChart(myBarChart, data);
                    // console.log(categories[category]);
                    if(this.innerHTML == "Provincial") {
                        document.getElementById("optionlbl").innerHTML = "PROVINCE:";
                        const entriesArray = Object.entries(categories['PROVINCE']);
                        var optionElement = document.getElementById("optiontype");
                        optionElement.innerHTML = "";
                        const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "";
                            optionElement.appendChild(option);                        
                        entriesArray.forEach(function(value,index){
                            const option = document.createElement('option');
                            option.value = value[0];
                            option.textContent = value[0];
                            optionElement.appendChild(option);
                        });
                        document.getElementById("cityoptiondiv").style = "display:block";
                        document.getElementById("surveydiv").style = "display:block";
                    }
                    if(this.innerHTML == "Municipal/City") {
                        document.getElementById("optionlbl").innerHTML = "Municipal/City:";
                        const entriesArray = Object.entries(categories['CITY']);
                        var optionElement = document.getElementById("optiontype");
                        optionElement.innerHTML = "";
                        const option = document.createElement('option');
                            option.value = "";
                            option.textContent = "";
                            optionElement.appendChild(option);                        
                        entriesArray.forEach(function(value,index){
                            const option = document.createElement('option');
                            option.value = value[0];
                            option.textContent = value[0];
                            optionElement.appendChild(option);
                        });
                        document.getElementById("cityoptiondiv").style = "display:block";
                        document.getElementById("surveydiv").style = "display:block";
                    }
                } else if (this.getAttribute('data-type') === 'LINE-GRAPHS') {
                    updateChart(myLineChart, data);
                }
            });
        });
    };



    $('.tab').click(function () {
        var tab_id = $(this).data('tab');

        $('.tab').removeClass('active');
        $('.tab-content').removeClass('active');

        $(this).addClass('active');
        $('#' + tab_id).addClass('active');
    });

    // Show popups
    document.querySelector('.search-gather-name').addEventListener('click', function () {
        document.getElementById('searchPopup').style.display = 'block';
    });

    document.querySelector('.search-gather-address').addEventListener('click', function () {
        document.getElementById('searchAddressPopup').style.display = 'block';
    });

    document.querySelector('.search-gather-birthday').addEventListener('click', function () {
        document.getElementById('searchBirthdayPopup').style.display = 'block';
    });

    // Close popups when clicking the close button
    document.querySelectorAll('.close').forEach(function (element) {
        element.addEventListener('click', function () {
            this.closest('.popup').style.display = 'none';
        });
    });

    // Close popups when clicking outside of the popup
    window.onclick = function (event) {
        if (event.target.classList.contains('popup')) {
            event.target.style.display = 'none';
        }
    };


    document.getElementById('voterBirthdayInput').addEventListener('keyup', function () {
        const query = String(this.value.toLowerCase());
        var voters = <?php echo $votersJson; ?>;
        const results = voters.filter(voter => String(voter.birthdate).toLowerCase().includes(query));

        const resultsContainer = document.getElementById('voterBirthdayResults');
        resultsContainer.innerHTML = '';

        results.forEach(voter => {
            const li = document.createElement('li');
            li.textContent = voter.name + ' - ' + voter.birthdate;
            li.addEventListener('click', function () {
                displayVoterDetailsBirthday(voter);
            });
            resultsContainer.appendChild(li);
        });
    });


    document.getElementById('voterSearchInput').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        var voters = <?php echo $votersJson; ?>;
        const results = voters.filter(voter => voter.name.toLowerCase().includes(query));

        const resultsContainer = document.getElementById('voterSearchResults');
        resultsContainer.innerHTML = '';

        results.forEach(voter => {
            const li = document.createElement('li');
            li.textContent = voter.name;
            li.addEventListener('click', function () {
                displayVoterDetails(voter);
            });
            resultsContainer.appendChild(li);
        });
    });

    document.getElementById('voterAddressInput').addEventListener('keyup', function () {
        const query = this.value.toLowerCase();
        var voters = <?php echo $votersJson; ?>;
        const results = voters.filter(voter => voter.address.toLowerCase().includes(query));

        const resultsContainer = document.getElementById('voterAddressResults');
        resultsContainer.innerHTML = '';

        results.forEach(voter => {
            const li = document.createElement('li');
            li.textContent = voter.address;
            li.addEventListener('click', function () {
                console.log(voter)
                displayVoterDetailsAddress(voter);
            });
            resultsContainer.appendChild(li);
        });
    });


    function displayVoterDetailsAddress(voter) {
        const resultsContainer = document.getElementById('voterAddressResults');
        resultsContainer.style.display = 'none'; // Hide the list of names

        const detailsContainer = document.getElementById('voterDetailsAddress');
        detailsContainer.innerHTML = `
            <p><strong>Name:</strong> ${voter.name}</p>
            <p><strong>Address:</strong> ${voter.address}</p>
            <p><strong>Sex:</strong> ${voter.sex}</p>
            <p><strong>Birthdate:</strong> ${voter.birthdate}</p>
            <p><strong>Province:</strong> ${voter.province}</p>
            <p><strong>City/Municipality:</strong> ${voter.city}</p>
            <p><strong>Barangay:</strong> ${voter.barangay}</p>
            <p><strong>Precinct:</strong> ${voter.precinct}</p>
            <p><strong>Email:</strong> ${voter.email}</p>
            <p><strong>FB:</strong> ${voter.fb}</p>
            <p><strong>CP No.:</strong> ${voter.cpNo}</p>
            <p><strong>Governor Preference:</strong> ${voter.governorPreference}</p>
            <p><strong>Second District Congressman Preference:</strong> ${voter.secondDistrictCongressmanPreference}</p>
            <p><strong>Mayor Preference:</strong> ${voter.mayorPreference}</p>
            <button id="backButton">Back to Search Results</button>
        `;

        document.getElementById('backButton').addEventListener('click', function () {
            detailsContainer.innerHTML = ''; // Clear the voter details
            resultsContainer.style.display = 'block'; // Show the list of names again
        });
    }


    function displayVoterDetails(voter) {
        const resultsContainer = document.getElementById('voterSearchResults');
        resultsContainer.style.display = 'none'; // Hide the list of names

        const detailsContainer = document.getElementById('voterDetails');
        detailsContainer.innerHTML = `
            <p><strong>Name:</strong> ${voter.name}</p>
            <p><strong>Address:</strong> ${voter.address}</p>
            <p><strong>Sex:</strong> ${voter.sex}</p>
            <p><strong>Birthdate:</strong> ${voter.birthdate}</p>
            <p><strong>Province:</strong> ${voter.province}</p>
            <p><strong>City/Municipality:</strong> ${voter.city}</p>
            <p><strong>Barangay:</strong> ${voter.barangay}</p>
            <p><strong>Precinct:</strong> ${voter.precinct}</p>
            <p><strong>Email:</strong> ${voter.email}</p>
            <p><strong>FB:</strong> ${voter.fb}</p>
            <p><strong>CP No.:</strong> ${voter.cpNo}</p>
            <p><strong>Governor Preference:</strong> ${voter.governorPreference}</p>
            <p><strong>Second District Congressman Preference:</strong> ${voter.secondDistrictCongressmanPreference}</p>
            <p><strong>Mayor Preference:</strong> ${voter.mayorPreference}</p>
            <button id="backButton">Back to Search Results</button>
        `;

        document.getElementById('backButton').addEventListener('click', function () {
            detailsContainer.innerHTML = ''; // Clear the voter details
            resultsContainer.style.display = 'block'; // Show the list of names again
        });
    }

    function displayVoterDetailsBirthday(voter) {
        const resultsContainer = document.getElementById('voterBirthdayResults');
        resultsContainer.style.display = 'none'; // Hide the list of names

        const detailsContainer = document.getElementById('voterDetailsBirthday');
        detailsContainer.innerHTML = `
            <p><strong>Name:</strong> ${voter.name}</p>
            <p><strong>Address:</strong> ${voter.address}</p>
            <p><strong>Sex:</strong> ${voter.sex}</p>
            <p><strong>Birthdate:</strong> ${voter.birthdate}</p>
            <p><strong>Province:</strong> ${voter.province}</p>
            <p><strong>City/Municipality:</strong> ${voter.city}</p>
            <p><strong>Barangay:</strong> ${voter.barangay}</p>
            <p><strong>Precinct:</strong> ${voter.precinct}</p>
            <p><strong>Email:</strong> ${voter.email}</p>
            <p><strong>FB:</strong> ${voter.fb}</p>
            <p><strong>CP No.:</strong> ${voter.cpNo}</p>
            <p><strong>Governor Preference:</strong> ${voter.governorPreference}</p>
            <p><strong>Second District Congressman Preference:</strong> ${voter.secondDistrictCongressmanPreference}</p>
            <p><strong>Mayor Preference:</strong> ${voter.mayorPreference}</p>
            <button id="backButton">Back to Search Results</button>
        `;

        document.getElementById('backButton').addEventListener('click', function () {
            detailsContainer.innerHTML = ''; // Clear the voter details
            resultsContainer.style.display = 'block'; // Show the list of names again
        });
    }
















    document.addEventListener('DOMContentLoaded', function () {
        function openPopup(type, precinct, barangay, city) {
            const managerPopup = document.getElementById('managerPopup');
            const resultsContainer = document.getElementById('popupResults');
            const detailsContainer = document.getElementById('popupDetails');

            managerPopup.style.display = 'block';
            resultsContainer.style.display = 'block';
            detailsContainer.innerHTML = '';

            const managers = <?php echo $managersJson ?>;

            console.log('Managers Data:', managers);

            const filteredDataManagers = managers.filter(item =>
                item.name || item.city || item.type || item.barangay || item.precinct
            );
            console.log('Filtered Data Managers:', filteredDataManagers);

            const results = filteredDataManagers.filter(manager =>
                manager.type.includes(type) &&
                (!precinct || manager.precinct === precinct) &&
                (!barangay || manager.barangay === barangay) &&
                (!city || manager.city === city)
            );

            console.log('Filtered Results:', results);

            const popupTitle = document.getElementById('popupTitle');
            popupTitle.textContent = type;
            resultsContainer.innerHTML = '';

            results.forEach(manager => {
                const li = document.createElement('li');
                li.textContent = manager.name;
                li.addEventListener('click', function () {
                    displayManagerDetails(manager);
                });
                resultsContainer.appendChild(li);
            });
        }

        function displayManagerDetails(manager) {
            const resultsContainer = document.getElementById('popupResults');
            resultsContainer.style.display = 'none';

            const detailsContainer = document.getElementById('popupDetails');
            detailsContainer.innerHTML = `
            <p><strong>Name:</strong> ${manager.name}</p>
            <p><strong>City:</strong> ${manager.city}</p>
            <p><strong>Barangay:</strong> ${manager.barangay}</p>
            <p><strong>Precinct:</strong> ${manager.precinct}</p>
            <p><strong>Type:</strong> ${manager.type}</p>
            <button id="backButton">Back to Search Results</button>
        `;
            document.getElementById('backButton').addEventListener('click', function () {
                detailsContainer.innerHTML = '';
                resultsContainer.style.display = 'block';
            });
        }

        function closePopup() {
            document.getElementById('managerPopup').style.display = 'none';
        }

        document.addEventListener('click', function (event) {
            const type = event.target.getAttribute('data-type');
            const precinct = event.target.getAttribute('data-precinct');
            const barangay = event.target.getAttribute('data-barangay');
            const city = event.target.getAttribute('data-city');

            // console.log('Clicked Type:', type);
            // console.log('Clicked Precinct:', precinct);
            // console.log('Clicked Barangay:', barangay);
            // console.log('Clicked City:', city);

            if (type) {
                openPopup(type, getSelectedPrecinct(), getSelectedBarangay(), getSelectedCity());
            } else if (precinct) {
                openPopup(getSelectedType(), precinct, getSelectedBarangay(), getSelectedCity());
            } else if (barangay) {
                openPopup(getSelectedType(), getSelectedPrecinct(), barangay, getSelectedCity());
            } else if (city) {
                openPopup(getSelectedType(), getSelectedPrecinct(), getSelectedBarangay(), city);
            }
        });

        function getSelectedType() {
            const activeManagerElement = document.querySelector('[data-type].active');
            return activeManagerElement ? activeManagerElement.getAttribute('data-type') : '';
        }

        function getSelectedPrecinct() {
            const activePrecinctElement = document.querySelector('[data-precinct].active');
            return activePrecinctElement ? activePrecinctElement.getAttribute('data-precinct') : '';
        }

        function getSelectedBarangay() {
            const activeBarangayElement = document.querySelector('[data-barangay].active');
            return activeBarangayElement ? activeBarangayElement.getAttribute('data-barangay') : '';
        }

        function getSelectedCity() {
            const activeCityElement = document.querySelector('[data-city].active');
            return activeCityElement ? activeCityElement.getAttribute('data-city') : '';
        }

        document.querySelector('.close').addEventListener('click', closePopup);

        window.onclick = function (event) {
            if (event.target === document.getElementById('managerPopup')) {
                closePopup();
            }
        };
    });





</script>

</html>