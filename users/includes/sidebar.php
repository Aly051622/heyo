<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<head>
<link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
<script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</head> 
<style>
    @import url('https://fonts.googleapis.com/css2?family=Open+Sans:wght@400;600;700&display=swap');
body, * {
    font-family: 'Open Sans', sans-serif !important; /* Ensure Open Sans is prioritized */
    margin: 0; /* Reset margin for consistency */
    padding: 0; /* Reset padding for consistency */
    box-sizing: border-box; /* Avoid layout issues */
}
    .left-panel{
        margin-top: 12px;
        border-top: groove 2px;
    }
    #sidebar {
    width: 200px;
    position: fixed;
    left: 0;
    height: 100vh;
    overflow: hidden;
    transition: width 0.3s ease;

    z-index: -1;
}

#sidebar.collapsed {
    width: 70px;
}

#toggleSidebar {
    color: white;
    border: none;
    left: 10px;
}


/*sidebarrrrr */

@media (max-width: 768px) {
    #sidebar {
        left: -250px;
    }

    #sidebar.collapsed {
        left: 0;
    }

    #toggleSidebar {
        display: block;
    }

    #right-panel {
        margin-left: 0;
    }
    .left-panel{
        height:100vh;
    }
}

@media (max-width: 576px) {
    #sidebar {
        width: 200px;
        margin-top: -15px;
    }

    #sidebar.collapsed {
        width: 50px;
    }

    #toggleSidebar {
        left: 5px;
        top: 5px;
    }
    .left-panel{
        height:100vh;
    }
}

@media (max-width: 480px) {
    #sidebar {
        width: 200px;
        margin-top: -15px;
    }

    #sidebar.collapsed {
        width: 50px;
    }

    #toggleSidebar {
        left: 5px;
        top: 5px;
    }
    .left-panel{
        height:100vh;
    }
}
</style> 
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="dashboard.php" style="text-decoration: none;">
                        <i class="menu-icon fa bi bi-laptop-fill"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="view-vehicle.php"  style="text-decoration: none;">
                    <i class="menu-icon fa bi bi-bus-front-fill"></i> Owned Vehicle/s
                    </a>
                </li>
                <li>
                    <a href="vehicle-transac.php"  style="text-decoration: none;">
                        <i class="menu-icon fa bi bi-train-front-fill"></i>Vehicle Logs
                    </a>
                </li>
                <li>
                    <a href="add-vehicle.php"  style="text-decoration: none;">
                        <i class="menu-icon fa bi bi-journal-album"></i>Register Vehicle
                    </a>
                </li>
                <li>
                    <a href="service.php"  style="text-decoration: none;">
                        <i class="menu-icon fa bi bi-question-circle-fill"></i>FAQs
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
