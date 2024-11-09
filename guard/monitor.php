<?php
session_start();

// Database connection
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "parkingz";

$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Function to check if the slot number already exists
function isSlotNumberExists($conn, $slotNumber) {
    $sql = "SELECT COUNT(*) as count FROM tblparkingslots WHERE SlotNumber = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $slotNumber);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    return $row['count'] > 0;
}

// Function to get the next available slot number for an area
function getNextSlotNumber($conn, $area, $prefix) {
    // Query to find the highest slot number for the area and prefix
    $sql = "SELECT MAX(SUBSTRING(SlotNumber, 2) * 1) as max_num FROM tblparkingslots WHERE Area = ? AND SlotNumber LIKE ?";
    $stmt = $conn->prepare($sql);
    $prefixLike = $prefix . '%';
    $stmt->bind_param("ss", $area, $prefixLike);
    $stmt->execute();
    $result = $stmt->get_result();
    $row = $result->fetch_assoc();
    $maxNum = $row['max_num'] ? $row['max_num'] + 1 : 1; // If no slots exist, start with 1
    return $prefix . $maxNum;
}

// Handle form submission to add a new parking slot
if (isset($_POST['add_slot'])) {
    $area = $_POST['area'];
    $status = $_POST['status'];
    $manualSlotNumber = $_POST['slotNumber']; // New slot number input

    // Determine the area prefix based on selected area
    switch ($area) {
        case "Front Admin":
            $prefix = "A";
            break;
        case "Beside CME":
            $prefix = "B";
            break;
        case "Kadasig":
            $prefix = "C";
            break;
        case "Behind":
            $prefix = "D";
            break;
        default:
            $prefix = "";
    }
    // Check if manual slot number is empty and generate the next available slot number
    if (empty($manualSlotNumber)) {
        $slotNumber = getNextSlotNumber($conn, $area, $prefix); // Auto-generate next available slot number
    } else {
        // Validate if the slot number starts with the correct prefix and is a number
        if (!preg_match("/^$prefix\d+$/", $manualSlotNumber)) {
            echo "<script>showModal('Invalid slot number! Slot number should start with $prefix and followed by numbers.');</script>";
        } elseif (isSlotNumberExists($conn, $manualSlotNumber)) {
            echo "<script>alert('Slot number already exists! Please choose a different number.');</script>";
        } else {
            $slotNumber = $manualSlotNumber; // Use manual input slot number
        }
    }

    // Insert the new slot into the database if slot number is valid
    if (isset($slotNumber)) {
        $stmt = $conn->prepare("INSERT INTO tblparkingslots (Area, SlotNumber, Status) VALUES (?, ?, ?)");
        $stmt->bind_param("sss", $area, $slotNumber, $status);
        $stmt->execute();
        $stmt->close();
        echo "Slot $slotNumber has been added to $area";
        header("Location: monitor.php");
        exit;
    }
}

// Handle AJAX requests for updating or deleting slots
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (isset($_POST['action']) && $_POST['action'] === 'updateStatus') {
        $slotNumber = $_POST['slotNumber'];
        $status = $_POST['status'];

        $stmt = $conn->prepare("UPDATE tblparkingslots SET Status = ? WHERE SlotNumber = ?");
        $stmt->bind_param("ss", $status, $slotNumber);
        $stmt->execute();
        $stmt->close();

        echo "Slot $slotNumber marked as $status.";
        exit;
    }

    if (isset($_POST['action']) && $_POST['action'] === 'deleteSlot') {
        $slotNumber = $_POST['slotNumber'];

        $stmt = $conn->prepare("DELETE FROM tblparkingslots WHERE SlotNumber = ?");
        $stmt->bind_param("s", $slotNumber);
        $stmt->execute();
        $stmt->close();

        echo "Slot $slotNumber deleted.";
        exit;
    }
}

// Fetch parking slots from the database, sorted by the numerical portion of SlotNumber
$slots_result = $conn->query("
    SELECT * FROM tblparkingslots 
    ORDER BY 
    Area, 
    CAST(SUBSTRING(SlotNumber, 2) AS UNSIGNED) ASC
");

// Function to fetch and display slots
function fetchAndDisplaySlots($conn, $area, $prefix) {
    $sql = "SELECT SlotNumber, Status FROM tblparkingslots WHERE Area = ? AND SlotNumber LIKE ? ORDER BY CAST(SUBSTRING(SlotNumber, 2) AS UNSIGNED)";
    $stmt = $conn->prepare($sql);
    $prefixLike = $prefix . '%';
    $stmt->bind_param("ss", $area, $prefixLike);
    $stmt->execute();
    $result = $stmt->get_result();

    // Display slots in sorted order (A1, A2, A3, etc.)
    while ($row = $result->fetch_assoc()) {
        echo "<div class='slot' data-slot='{$row['SlotNumber']}'>";
        echo "{$row['SlotNumber']} ({$area})";
        echo "</div>";
    }
}

// Example usage for Front Admin area
fetchAndDisplaySlots($conn, 'Front Admin', 'A');
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Parking Slot Manager</title>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-icons/1.10.3/font/bootstrap-icons.min.css" rel="stylesheet">
    <link rel="stylesheet" href="guard.css">
</head>
<style>
/*navbar add css*/
        .navbar{
            background-color: rgb(53, 97, 255);
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            }
        #title{
            margin-left: 50px;
        }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            #title{
                margin-top: 25px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
                margin-left: 20px;
            }
        }
        @media (max-width: 480px) {
            #title{
                margin-left: 25px;
                margin-top: 20px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
            }
        }
        .toggle-menu{
            margin-top: 4px;
            margin-left: 18px;
            padding: 5px;
            border: none;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
                
        .toggle-menu:hover{
            color: orange;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }
         /* Responsive adjustments */
        @media (max-width: 768px) {
            .toggle-menu {
                margin-top: -10px; /* Reduced margin for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .toggle-menu {
                margin-top: -5px; /* Further reduced margin for very small screens */
                margin-left: 35px;
            }
        }
        .container {
            margin-top: 85px; /* Default margin for larger screens */
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .container {
                margin-top: 17em; /* Reduced margin for smaller screens */
            }
        }

        @media (max-width: 480px) {
            .container {
                margin-top: 6em; /* Further reduced margin for very small screens */
            }
        }

        /*qrbutton add css*/
        .dropbtns, .slot-action button{
            color: white;
            padding: 8px;
            font-size: 16px;
            border: none;
            cursor: pointer;
            background-color: orange;
            border-radius: 9px;
            font-weight: bold;
            border: solid;
            margin-right: 30px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        .dropbtns:hover, .slot-action button:hover{
            background-color: white;
            color: orange;
            border: solid orange;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }

        .slot-action{
            align-items: left;
        }
        /*slot add css*/
        .slot{
            width: 100px;
            height: 100px;
            border-radius: 15px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 
            0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
            font-size: 20px;
            }
            
        .vacant {
            background-color: rgba(34, 191, 16, 0.949); /* Green for Vacant */
            color: white;
        }

        .occupied {
            background-color: rgba(255, 43, 43, 0.95); /* Red for Occupied */
            color: white;
        }

        /*function for slots css*/
        #stat, #areaSelect, #searchInput, #slotNumberInput {
            margin-top: 7px;
            border-radius:9px;
            cursor:text;    
            border: solid;
        }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {

            #stat, #areaSelect, #searchInput, #slotNumberInput {
                margin-left: 7px; /* Add space between buttons */
                text-align: center; /* Center text in buttons */
            }
        }
        #stat:hover, #areaSelect:hover, #searchInput:hover, #slotNumberInput:hover{
            border:solid orange;
            background-color: aliceblue;
        }
        #areaSelect{
            border-bottom-left-radius: 9px;
            border-bottom-right-radius: 9px;
        }
        #btnFrontAdmin, #btnBesideCME, 
        #btnKadasig, #btnBehind {
            border-radius: 9px;
            border: solid;
            cursor: pointer;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
        #btnFrontAdmin:hover, #btnBesideCME:hover, #btnKadasig:hover,#btnsearch:hover, 
        #btnBehind:hover, #btnadd:hover, #btnsearch:hover .slot-action button:hover{
            color: orange;
            background-color: white;
            border: solid orange;
            box-shadow: rgb(204, 219, 232) 3px 3px 6px 0px inset, rgba(255, 255, 255, 0.5) -3px -3px 6px 1px inset;
        }

        .legend {
            margin-top: -40px;
            margin-left: 50px;
            display: block;
            align-items: flex-start; 
        }

        /* Adding flexbox for better alignment */
        .legend-container {
            display: flex;
            flex-wrap: wrap; 
            justify-content: flex-start;
        }

        .v-legend {
            color: rgba(34, 191, 16, 0.949);
            margin-right: 10px; 
        }

        .o-legend {
            color: rgba(255, 43, 43, 0.95);
            margin-right: 10px; 
        }

        /* Responsive adjustments */
        @media (max-width: 768px) {
            .legend {
                margin-left: 20px; 
                margin-top: -18px; 
            }

            .v-legend,
            .o-legend {
                font-size: 16px; 
                margin-right: 10px; 
            }
        }

        @media (max-width: 480px) {
            .legend {
                margin-left: 10px; 
                margin-top: 3px;  
            }

            .v-legend,
            .o-legend {
                font-size: 16px;
                margin-right: 10px; 
            }
        }
        .dropdown-content{
            font-size: 12px;
            border-bottom-left-radius: 9px;
            border-bottom-right-radius: 9px;
            margin-top: 0px;
            width: 135px;
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }

        .dropdown-content a:hover {
            background-color: #f3ab19e0;
            color:white;
        }
        #drp-con2,  #drp-con1{
                margin-top: -2px;
                width: 82%; 
                text-align: center; 
                padding: -1px;
                z-index:1007;
            }
        /* Media Queries for Responsiveness */
        @media (max-width: 768px) {
            .dropbtns {
                margin-top: 9px;
                display: inline;
                position: relative;
                z-index: 1006;
            }

            #drp-con1{
                margin-top: -7px; 
                width: 40%; 
                text-align: center; 
                padding: 0px;
                z-index:1007;
                margin-left: 20px;
                position: absolute;
            }
            #drp-con2 {
                margin-top: -7px; 
                width: 40%; 
                text-align: center; 
                padding: -1px;
                z-index:1007;
                margin-left: 195px;
                position: absolute;
            }
            #bt1{
                margin-bottom: 8px; 
                width: 40%;
                text-align: center;
                padding: 5px;
                margin-left: 20px;  
            }
            #bt2{
                margin-left: 195px;
                margin-top: -42px;
                width: 40%;
                padding: 5px;
                z-index: 1006;
            }
        }

        #btnsearch, #btnadd{
            box-shadow: rgba(0, 0, 0, 0.4) 0px 2px 4px, rgba(0, 0, 0, 0.3) 0px 7px 13px -3px, rgba(0, 0, 0, 0.2) 0px -3px 0px inset;
        }
</style>
<body>
    <!-- Responsive Navigation Bar -->
    <nav class="navbar">
    <button id="toggleMenuBtn" class="toggle-menu"><i class="bi bi-menu-button-wide-fill"></i> </button>
        <div class="navbar-brand"><a href="monitor.php" id="title" >Parking Slot Manager</a></div>

        <div class="navbar-menu" id="navbarMenu">
            <!-- QR Scanner Dropdown -->
            <div class="dropdown">
                <button class="dropbtns" id="bt1"><i class="bi bi-qr-code-scan"></i> QR Scanner</button>
                <div class="dropdown-content" id="drp-con1">
                    <a href="qrlogin.php"><i class="bi bi-car-front-fill"></i> Log-in</a>
                    <a href="qrlogout.php"><i class="bi bi-car-front"></i> Log-out</a>
                </div>
            </div>

            <!-- Manual Input Dropdown -->
            <div class="dropdown">
                <button class="dropbtns"id="bt2"><i class="bi bi-journal-album"></i> Manual Input</button>
                <div class="dropdown-content"  id="drp-con2">
                    <a href="#"><i class="bi bi-car-front-fill"></i> Log-in</a>
                    <a href="#"><i class="bi bi-car-front"></i> Log-out</a>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
          <!-- Search Slot -->
          <div class="search-slot">
                    <input type="text" id="searchInput" placeholder="Enter Slot Number (e.g A1)">
                    <button onclick="searchSlot()" id="btnsearch"><i class="bi bi-search"></i> Search</button>
                </div>
    <!-- Add New Slot -->
    <form method="POST" action="">
                <div class="add-slot">
                    <select name="area" id="areaSelect">
                            <option value="Front Admin">Front Admin</option>
                            <option value="Beside CME">Beside CME</option>
                            <option value="Kadasig">Kadasig</option>
                            <option value="Behind">Behind</option>
                    </select>
                    <input type="text" name="slotNumber" id="slotNumberInput" placeholder="Enter Slot Number (e.g A1)" >
                    <select name="status" id="stat">
                        <option value="Vacant">Vacant</option>
                        <option value="Occupied">Occupied</option>
                    </select>
                    <button type="submit" name="add_slot" id="btnadd"><i class="bi bi-pin-map-fill"></i> Add Slot</button>
                </div>
            </form>
        <!-- Sidebar Menu -->
        <div id="sidebar" class="sidebar">
               <!-- Toggle Button -->
            <div class="select-area">
                <button id="btnFrontAdmin"><i class="bi bi-signpost-2-fill"></i> Front Admin</button>
                <button id="btnBesideCME"><i class="bi bi-signpost-2-fill"></i> Beside CME</button>
                <button id="btnKadasig"><i class="bi bi-signpost-2-fill"></i> Kadasig</button>
                <button id="btnBehind"><i class="bi bi-signpost-2-fill"></i> Behind</button>
            </div>
        </div>


            <!-- Managing Slot-->
        <div class="slot-action">
            <button onclick="selectSlot()"><i class="bi bi-ui-checks-grid"></i> Select</button>
            <button onclick="selectAll()"><i class="bi bi-ui-checks"></i> Select All</button>
            <div id="toggle-btn" style="display: none; margin-top: 10px;">
                <button onclick="markSlotAs('vacant')"><i class="bi bi-square-fill"></i> Mark as Vacant</button>
                <button onclick="markSlotAs('occupied')"><i class="bi bi-dash-square-fill"> </i>Mark as Occupied</button>
                <button onclick="deleteSelected()"><i class="bi bi-trash-fill"></i> Delete Slot</button>
            </div>
        </div>

        </div>

          <!-- Slot's Legend -->
          <div class="legend">
            <span class="v-legend"><i class="bi bi-square-fill"></i> Vacant</span><br>
            <span class="o-legend"><i class="bi bi-dash-square-fill"></i> Occupied</span>
        </div>

        <!-- Slots Display -->
        <div class="slots-display" >
        <?php
        while ($row = $slots_result->fetch_assoc()): 
            $area_class = strtolower(str_replace(' ', '-', $row['Area']));
        ?>
        <div class="slot <?= $area_class ?> <?= $row['Status'] === 'Vacant' ? 'vacant' : 'occupied' ?>" 
             data-slot-number="<?= $row['SlotNumber'] ?>" 
             data-status="<?= $row['Status'] ?>">
            <?= $row['SlotNumber'] ?>
        </div>
        <?php endwhile; ?>
        </div>
    </div>

    <script src="guard.js"></script>
</body>
</html>
