<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Skadoosh Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 0;
            background-color: #f3f3f3;
            color: #333;
        }

        .header {
            background-color: #e8e8e8;
            padding: 20px;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .header .logo {
            display: flex;
            align-items: center;
        }

        .header .logo img {
            width: 100px;
        }

        .header .date {
            font-size: 16px;
            font-weight: bold;
            color: #555;
        }

        .header .search {
            display: flex;
            align-items: center;
        }

        .header .search input[type="text"] {
            padding: 5px;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .header .search img {
            width: 20px;
            margin-left: 10px;
        }

        .nav {
            background-color: #007bff;
            padding: 10px 0;
            text-align: center;
        }

        .nav select {
            padding: 10px;
            font-size: 16px;
            background-color: #ffffff;
            border: 1px solid #ccc;
            border-radius: 3px;
        }

        .content {
            padding: 20px;
        }

        .graphs {
            display: flex;
            justify-content: space-between;
            margin-bottom: 30px;
        }

        .graph {
            width: 48%;
            background-color: #ffffff;
            padding: 20px;
            box-shadow: 0 0 10px rgba(0, 0, 0, 0.1);
        }

        .graph img {
            width: 100%;
        }

        .menu {
            display: flex;
            flex-wrap: wrap;
            justify-content: space-between;
        }

        .menu div {
            width: 24%;
            margin-bottom: 20px;
        }

        .menu div h3 {
            font-size: 16px;
            margin-bottom: 10px;
            color: #007bff;
        }

        .menu div ul {
            list-style-type: none;
            padding: 0;
            margin: 0;
        }

        .menu div ul li {
            font-size: 14px;
            margin-bottom: 5px;
        }

        .menu div ul li span {
            font-weight: bold;
        }

        .footer {
            text-align: center;
            padding: 20px;
            background-color: #e8e8e8;
            margin-top: 20px;
            font-size: 12px;
        }

        .footer img {
            width: 40px;
            margin-top: 10px;
        }
    </style>
</head>
<body>

<div class="header">
    <div class="logo">
        <img src="path_to_logo/logo.png" alt="Skadoosh Logo"> <!-- Update with your logo path -->
    </div>
    <div class="date">
        27 MARCH 2025, THURSDAY
    </div>
    <div class="search">
        <input type="text" placeholder="Search...">
        <img src="path_to_search_icon/search_icon.png" alt="Search Icon"> <!-- Update with your search icon path -->
    </div>
</div>

<div class="nav">
    <select>
        <option value="1">GOVERNOR PREFERENCE</option>
        <!-- Add more options if needed -->
    </select>
</div>

<div class="content">
    <div class="graphs">
        <div class="graph">
            <img src="path_to_graph_image/graph1.png" alt="Graph 1"> <!-- Update with your graph image path -->
        </div>
        <div class="graph">
            <img src="path_to_graph_image/graph2.png" alt="Graph 2"> <!-- Update with your graph image path -->
        </div>
    </div>

    <div class="menu">
        <div>
            <h3>HISTOGRAM</h3>
            <ul>
                <li><span>Provincial</span> National</li>
                <li><span>District</span> First District</li>
                <li><span>Municipal/City</span> Third District</li>
                <li><span>Barangay</span> Abucay</li>
                <li><span>Precinct</span> 00221</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>LINE GRAPHS</h3>
            <ul>
                <li><span>Muni/City</span> Agdangan</li>
                <li><span>Barangay</span> A. Mabini, Guin...</li>
                <li><span>Precinct</span> 00223</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>TABLES</h3>
            <ul>
                <li><span>Provincial</span> National</li>
                <li><span>District</span> First District</li>
                <li><span>Municipal/City</span> Third District</li>
                <li><span>Barangay</span> Abucay</li>
                <li><span>Precinct</span> 00221</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>MAPS</h3>
            <ul>
                <li><span>Provincial</span> National</li>
                <li><span>District</span> First District</li>
                <li><span>Municipal/City</span> Third District</li>
                <li><span>Barangay</span> Abucay</li>
                <li><span>Precinct</span> 00221</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>SEARCH GATHER</h3>
            <ul>
                <li><span>Name</span> ADDRESS</li>
                <li><span>Execcom</span> MANAGERS</li>
                <li><span>Campaign Staff</span> Mid managers</li>
                <li><span>Muni/City</span> A. Mabini, Guin...</li>
                <li><span>Barangay</span> Abucay</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>MEET COMM.</h3>
            <ul>
                <li><span>Managers</span> Top managers</li>
                <li><span>Campaign Staff</span> Frontline managers</li>
                <li><span>Muni/City</span> A. Mabini, Guin...</li>
                <li><span>Barangay</span> Abucay</li>
                <li><span>Precinct</span> 00221</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>PROGRAMS/FIN.</h3>
            <ul>
                <li><span>Design</span> Facebook</li>
                <li><span>Survey</span> Respondents</li>
                <li><span>Campaign Staff</span> Frontline managers</li>
                <li><span>Muni/City</span> A. Mabini, Guin...</li>
                <li><span>Barangay</span> Abucay</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>SOC. MEDIA</h3>
            <ul>
                <li><span>Video</span> Facebook</li>
                <li><span>Image</span> Facebook</li>
                <li><span>Text</span> Facebook</li>
                <li><span>Document</span> Facebook</li>
                <li><span>Add Variables</span> Export</li>
                <!-- More items -->
            </ul>
        </div>
        <div>
            <h3>MANAGE DATA</h3>
            <ul>
                <li><span>Import</span> Logs</li>
                <li><span>Edit</span> Criteria</li>
                <li><span>Password</span> Facebook</li>
                <!-- More items -->
            </ul>
        </div>
    </div>
</div>

<div class="footer">
    <img src="/logo.png" alt="Footer Logo"> <!-- Update with your footer logo path -->
    <p>Copyright © 2024 Edgepoint Solutions, Inc. All rights reserved.</p>
</div>

</body>
</html>
