<?php
$clashlogs = "/data/adb/box/run/runs.log";
$pid = "/data/adb/box/run/box.pid";
$moduledir = "../modules/box_for_magisk";
$iniFile = '/data/adb/box/settings.ini';

session_start([
  'cookie_lifetime' => 31536000, // 1 year
]);

// Include the config file
include 'auth/config.php';

// Check if login is enabled and if the user is not logged in
if (LOGIN_ENABLED && !isset($_SESSION['user_id'])) {
    header('Location: /auth/login.php');
    exit;
}

// Check if the file exists and read the parameter clash config
if (file_exists($iniFile)) {
    // Open the file and read it line by line
    $lines = file($iniFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Loop through each line to find the name_clash_config setting
    foreach ($lines as $line) {
        if (strpos($line, 'name_clash_config=') === 0) {
            // Extract the value inside the quotes after the '=' symbol
            $name_clash_config = trim(str_replace(['name_clash_config=', '"'], '', $line));
            break; // Stop searching once we find the parameter
        }
    }
}

// Check if the file exists and read the parameter Sing config
if (file_exists($iniFile)) {
    // Open the file and read it line by line
    $lines = file($iniFile, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    
    // Loop through each line to find the name_clash_config setting
    foreach ($lines as $line) {
        if (strpos($line, 'name_sing_config=') === 0) {
            // Extract the value inside the quotes after the '=' symbol
            $name_sing_config = trim(str_replace(['name_sing_config=', '"'], '', $line));
            break; // Stop searching once we find the parameter
        }
    }
}



if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $action = $_REQUEST['actionButton'];
    switch ($action) {
        case "disable":
            $myfile = fopen("$moduledir/disable", "w") or die("Unable to open file!");
            break;
        case "enable":
            unlink("$moduledir/disable");
            break;
    }
}

$p = $_SERVER['HTTP_HOST'];
$x = explode(':', $p);
$host = $x[0];
?>
<!DOCTYPE html>
<html lang="en">
  <head>
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <title> BOX UI</title>
    <meta
      content="width=device-width, initial-scale=1.0, shrink-to-fit=no"
      name="viewport"
    />
    <link
      rel="icon"
      href="webui/assets/img/icon.png"
      type="kaiadmin/image/x-icon"
    />

    <!-- Fonts and icons -->
    <script src="kaiadmin/assets/js/plugin/webfont/webfont.min.js"></script>
    <script>
      WebFont.load({
        google: { families: ["Public Sans:300,400,500,600,700"] },
        custom: {
          families: [
            "Font Awesome 5 Solid",
            "Font Awesome 5 Regular",
            "Font Awesome 5 Brands",
            "simple-line-icons",
          ],
          urls: ["kaiadmin/assets/css/fonts.min.css"],
        },
        active: function () {
          sessionStorage.fonts = true;
        },
      });
    </script>
<style>
  /* Ensure iframe takes the full width and height */
  .dashboard-iframe {
      width: 100%;
      height: calc(100vh - 65px);
      border: none;
      background-color: #121212; /* Dark background */
  }
</style>

    <!-- CSS Files -->
    <link rel="stylesheet" href="kaiadmin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="kaiadmin/assets/css/kaiadmin.min.css" />
  </head>
  <body>
    <div class="wrapper">
      <!-- Sidebar -->
      <div class="sidebar" data-background-color="dark">
        <div class="sidebar-logo">
          <!-- Logo Header -->
          <div class="logo-header" data-background-color="dark">
            <a href="/" class="logo">
              <img
                src="webui/assets/img/logo.png"
                alt="navbar brand"
                class="navbar-brand"
                height="20"
              />
            </a>
            <div class="nav-toggle">
              <button class="btn btn-toggle toggle-sidebar">
                <i class="gg-menu-right"></i>
              </button>
              <button class="btn btn-toggle sidenav-toggler">
                <i class="gg-menu-left"></i>
              </button>
            </div>
            <button class="topbar-toggler more">
              <i class="gg-more-vertical-alt"></i>
            </button>
          </div>
          <!-- End Logo Header -->
        </div>
        <div class="sidebar-wrapper scrollbar scrollbar-inner">
          <div class="sidebar-content">
            <ul class="nav nav-secondary">
              <li class="nav-item">
                <li class="nav-item">
                <a data-bs-toggle="collapse" href="#DASHBOARD">
                  <i class="fas fa-home"></i>
                  <p>Clash Dashboard</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="DASHBOARD">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>:9090/ui/?hostname=<?php echo $host; ?>&port=9090')">
                        <span class="sub-item">Default</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>/zashboard/ui#/setup?hostname=<?php echo $host; ?>&port=9090')">
                        <span class="sub-item">zashboard</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#system">
                  <i class="fas fa-cogs"></i>
                  <p>System</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="system">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('/tools_argon/sysinfo.php')">
                        <span class="sub-item">System Info</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/select_theme/theme.php')">
                        <span class="sub-item">Select Theme</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
            
            
              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Tools</h4>
              </li>
              <li class="nav-item">
                <a href="#" onclick="loadIframe('/tools/smsviewer.php')">
                    <i class="fas fa-envelope"></i>
                    <p>SMS Inbox</p>
                    <span class="badge badge-secondary"></span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="loadIframe('/tools/boxsettings.php')">
                  <i class="fas fa-cube"></i>
                  <p>BOX SET</p>
                  <span class="badge badge-secondary"></span>
                </a>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#storage">
                  <i class="fas fa-folder-open"></i>
                  <p>File Manager</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="storage">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('/tiny/index.php')">
                        <span class="sub-item">Root Path</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>/tiny/index.php?p=sdcard')">
                        <span class="sub-item">Storage Path</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>/tiny/index.php?p=data%2Fadb')">
                        <span class="sub-item">/data/adb</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>/tiny/index.php?p=data%2Fadb%2Fphp7%2Ffiles%2Fwww')">
                        <span class="sub-item">www</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#bfr">
                  <i class="fas fa-box"></i>
                  <p>BOX</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="bfr">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('/tools_argon/opsi_box.php')">
                        <span class="sub-item">Box For root</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>/tiny/index.php?p=data%2Fadb%2Fbox%2Fclash&view=config.yaml')">
                        <span class="sub-item">config.yaml</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>/tiny/index.php?p=data%2Fadb%2Fbox%2Fsing-box&view=config.json')">
                        <span class="sub-item">config.json</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools_argon/opsi_cfm.php')">
                        <span class="sub-item">Clash For Magisk</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools/ocgen/index.php')">
                        <span class="sub-item">Config Generator</span>
                      </a>
                    </li>
                    <li>
                    <a href="blackbox.php" target="_blank">
                      <span class="sub-item">BLACK BOX</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#networks">
                  <i class="fas fa-wifi"></i>
                  <p>Networks</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="networks">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('/tools_argon/opsi_interface.php')">
                        <span class="sub-item">Interface</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools/ipset.php')">
                        <span class="sub-item">Set Wlan Ip</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools_argon/modpes.php')">
                        <span class="sub-item">Airplane Pilot</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('https://openspeedtest.com/Get-widget.php')">
                        <span class="sub-item">speedtest</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools/vnstat.php')">
                        <span class="sub-item">Vnstat Bandwith</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools/qos.php')">
                        <span class="sub-item">QOS</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#root_tools">
                  <i class="fas fa-laptop-code"></i>
                  <p>Root Tools</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="root_tools">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>:3001')">
                        <span class="sub-item">Ttyd terminal</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#admin">
                  <i class="fas fas fa-user-cog"></i>
                  <p>Admin</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="admin">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('/auth/change_password_default.php')">
                        <span class="sub-item">Change Password</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/auth/manage_login_default.php')">
                        <span class="sub-item">enable/disable login</span>
                      </a>
                    </li>
                    <!--<li>
                      <a href="#">
                        <span class="sub-item">Icon Menu</span>
                      </a>
                    </li>-->
                  </ul>
                </div>
              </li>

              <li class="nav-item">
                <a href="#" onclick="loadIframe('/article.html')">
                  <i class="fas fa-file-word"></i>
                  <p>Documentation</p>
                  <span class="badge badge-secondary"></span>
                </a>
              <li class="nav-item">
                <a href="https://github.com/geeks121/webui_bfm">
                  <i class="fas fa-code-branch"></i>
                  <p>Our github</p>
                  <span class="badge badge-secondary"></span>
                </a>
                <a href="#" onclick="loadIframe('tools/reboot.php')">
                  <i class="fas fa-sync"></i>
                  <p>Reboot</p>
                  <span class="badge badge-secondary"></span>
                </a>
              </li>
            </ul>
          </div>
        </div>
      </div>
      <!-- End Sidebar -->

      <div class="main-panel" data-background-color="dark">
        <div class="main-header">
          <div class="main-header-logo">
            <!-- Logo Header -->
            <div class="logo-header" data-background-color="dark">
              <a href="index.html" class="logo">
                <img
                  src="webui/assets/img/logo.png"
                  alt="navbar brand"
                  class="navbar-brand"
                  height="20"
                />
              </a>
              <div class="nav-toggle">
                <button class="btn btn-toggle toggle-sidebar">
                  <i class="gg-menu-right"></i>
                </button>
                <button class="btn btn-toggle sidenav-toggler">
                  <i class="gg-menu-left"></i>
                </button>
              </div>
              <button class="topbar-toggler more">
                <i class="gg-more-vertical-alt"></i>
              </button>
            </div>
            <!-- End Logo Header -->
          </div>
          <!-- Navbar Header -->
          <nav
            class="navbar navbar-header navbar-header-transparent navbar-expand-lg border-bottom" data-background-color="dark"
          >
            <div class="container-fluid">
              <nav
                class="navbar navbar-header-left navbar-expand-lg navbar-form nav-search p-0 d-none d-lg-flex"
              >
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none">
                </li>
                <li class="nav-item topbar-user dropdown hidden-caret">
                  <a
                    class="dropdown-toggle profile-pic"
                    data-bs-toggle="dropdown"
                    href="#"
                    aria-expanded="false"
                  >
                    <div class="avatar-sm">
                      <img
                        src="webui/assets/img/icon.png"
                        alt="..."
                        class="avatar-img rounded-circle"
                      />
                    </div>
                    <span class="profile-username">
                      <span class="op-7">Hi,</span>
                      <span class="fw-bold">Root</span>
                    </span>
                  </a>
                  <ul class="dropdown-menu dropdown-user animated fadeIn">
                    <div class="dropdown-user-scroll scrollbar-outer">
                      <!--<li>
                        <div class="user-box">
                          <div class="avatar-lg">
                            <img
                              src="kaiadmin/assets/img/profile.jpg"
                              alt="image profile"
                              class="avatar-img rounded"
                            />
                          </div>
                          <div class="u-text">
                            <h4>Hi root</h4>
                            <p class="text-muted">hello@root.com</p>
                            <a
                              href="£"
                              class="btn btn-xs btn-secondary btn-sm"
                              >View Profile</a
                            >
                          </div>
                        </div>
                      </li>-->
                      <li>
                        <div class="dropdown-divider"></div>
                        <a class="dropdown-item" href="#" onclick="loadIframe('/auth/change_password_default.php')">Change password</a>
                        <a class="dropdown-item" href="auth/logout.php">Logout</a>
                      </li>
                    </div>
                  </ul>
                </li>
              </ul>
            </div>
          </nav>
          <!-- End Navbar -->
                   <!-- Main Content -->
        <div class="content">
          <!-- Iframe -->
          <!--<iframe class="dashboard-iframe" id="iframe" src="http://<?php echo $host; ?>:9090/ui/?hostname=<?php echo $host; ?>#/proxies"></iframe>-->
          <iframe class="dashboard-iframe" id="iframe" src="/tools_argon/sysinfo.php"></iframe>
                  
        </div>

        <!-- End Main Content --> 
        </div>
       
        
        <!--<footer class="footer">

        </footer>-->
      </div>

      <!-- Custom template | don't include it in your project! -->

      <!-- End Custom template -->
    </div>
    <!-- iframe -->
    <script>
      function loadIframe(url) {
        document.getElementById('iframe').src = url;
      };
    </script>
    <!--   Core JS Files   -->
    <script src="kaiadmin/assets/js/core/jquery-3.7.1.min.js"></script>
    <script src="kaiadmin/assets/js/core/popper.min.js"></script>
    <<script src="kaiadmin/assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Kaiadmin JS don't remove -->
    <script src="kaiadmin/assets/js/kaiadmin.min.js"></script>

    <script>
      $("#lineChart").sparkline([102, 109, 120, 99, 110, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#177dff",
        fillColor: "rgba(23, 125, 255, 0.14)",
      });

      $("#lineChart2").sparkline([99, 125, 122, 105, 110, 124, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#f3545d",
        fillColor: "rgba(243, 84, 93, .14)",
      });

      $("#lineChart3").sparkline([105, 103, 123, 100, 95, 105, 115], {
        type: "line",
        height: "70",
        width: "100%",
        lineWidth: "2",
        lineColor: "#ffa534",
        fillColor: "rgba(255, 165, 52, .14)",
      });

    </script>
  </body>
</html>
