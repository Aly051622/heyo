<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">

<head>

<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
<script src="https://kit.fontawesome.com/your-kit-code.js" crossorigin="anonymous"></script>
</head> 
<style>
    .left-panel{
        margin-top: 15px;
        border-top: groove 2px;
    }
    #sidebar {
    width: 200px;
    position: fixed;
    left: 0;
    height: 100bh;
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
}

@media (max-width: 576px) {
    #sidebar {
        width: 200px;
        margin-top: -10px;
    }

    #sidebar.collapsed {
        width: 50px;
    }

    #toggleSidebar {
        left: 5px;
        top: 5px;
    }
}

@media (max-width: 480px) {
    #sidebar {
        width: 200px;
        margin-top: -10px;
    }

    #sidebar.collapsed {
        width: 50px;
    }

    #toggleSidebar {
        left: 5px;
        top: 5px;
    }
}
</style> 
<aside id="left-panel" class="left-panel">
    <nav class="navbar navbar-expand-sm navbar-default">
        <div id="main-menu" class="main-menu collapse navbar-collapse">
            <ul class="nav navbar-nav">
                <li class="active">
                    <a href="dashboard.php" style="text-decoration: none;">
                        <i class="menu-icon fa fa-laptop"></i>Dashboard
                    </a>
                </li>
                <li>
                    <a href="view-vehicle.php"  style="text-decoration: none;">
                    <i class="menu-icon fa fa-car-side"></i> Owned Vehicle/s
                    </a>
                </li>
                <li>
                    <a href="vehicle-transac.php"  style="text-decoration: none;">
                        <i class="menu-icon fa fa-truck"></i>Vehicle Logs
                    </a>
                </li>
                <li>
                    <a href="add-vehicle.php"  style="text-decoration: none;">
                        <i class="menu-icon fa fa-address-book"></i>Register Vehicle
                    </a>
                </li>
                <li>
                    <a href="service.php"  style="text-decoration: none;">
                        <i class="menu-icon fa fa-headset"></i>Chat Concern
                    </a>
                </li>
            </ul>
        </div>
    </nav>
</aside>
