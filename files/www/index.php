<?php
$clashlogs = "/data/adb/box/run/runs.log";
$pid = "/data/adb/box/run/box.pid";
$moduledir = "../modules/box_for_magisk";

session_start();

if (!isset($_SESSION['user_id'])) {
    header('Location: /auth/login.php');
    exit;
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
      /* iframe style */
      .dashboard-iframe {
          width: 100%;
          height: calc(100vh - 65px);
          border: none;
      }
  </style>
    <!-- CSS Files -->
    <link rel="stylesheet" href="kaiadmin/assets/css/bootstrap.min.css" />
    <link rel="stylesheet" href="kaiadmin/assets/css/plugins.min.css" />
    <link rel="stylesheet" href="kaiadmin/assets/css/kaiadmin.min.css" />

    <!-- CSS Just for demo purpose, don't include it in your project -->
    <!--  <link rel="stylesheet" href="kaiadmin/assets/css/demo.css" />-->
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
                <a href="#" onclick="loadIframe('http://<?php echo $host; ?>:9090/ui/')">
                  <i class="fas fa-home"></i>
                  <p>Clash Dashboard</p>
                  <span class="badge badge-secondary">1</span>
                </a>
              </li>
            <li class="nav-item">
              <a href="#" onclick="loadIframe('/tools/sysinfo.php')">
                  <i class="fas fa-cogs"></i>
                  <p>System Info</p>
                  <span class="badge badge-secondary"></span>
              </a>
            </li>
                      

              <li class="nav-section">
                <span class="sidebar-mini-icon">
                  <i class="fa fa-ellipsis-h"></i>
                </span>
                <h4 class="text-section">Tools</h4>
              </li>
              <li class="nav-item">
                <a href="#" onclick="loadIframe('/tools/smsviewer.php')">
                    <i class="fas fa-comment-alt"></i>
                    <p>SMS Inbox</p>
                    <span class="badge badge-secondary"></span>
                </a>
              </li>
              <li class="nav-item">
                <a href="#" onclick="loadIframe('/tools/file.php')">
                  <i class="fas fa-archive"></i>
                  <p>TinyFM</p>
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
                <a data-bs-toggle="collapse" href="#bfr">
                  <i class="fas fa-box"></i>
                  <p>BOX</p>
                  <span class="caret"></span>
                </a>
                <div class="collapse" id="bfr">
                  <ul class="nav nav-collapse">
                    <li>
                      <a href="#" onclick="loadIframe('http://<?php echo $host; ?>/tools/file.php?p=box%2Fclash&edit=config.yaml&env=ace')">
                        <span class="sub-item">config.yaml editor</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools/executed.php')">
                        <span class="sub-item">Command</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools/ocgen/index.php')">
                        <span class="sub-item">Config Generator</span>
                      </a>
                    </li>
                    <li>
                      <a href="#" onclick="loadIframe('/tools/logs.php')">
                        <span class="sub-item">BOX logs</span>
                      </a>
                    </li>
                  </ul>
                </div>
              </li>
              <li class="nav-item">
                <a data-bs-toggle="collapse" href="#root_tools">
                  <i class="fas fa-hashtag"></i>
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
                      <a href="#" onclick="loadIframe('/auth/change_password.php')">
                        <span class="sub-item">Reset Password</span>
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
                  src="kaiadmin/assets/img/kaiadmin/logo_light.svg"
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
                <!--<div class="input-group">
                  <div class="input-group-prepend">
                    <button type="submit" class="btn btn-search pe-1">
                      <i class="fa fa-search search-icon"></i>
                    </button>
                  </div>
                  <input
                    type="text"
                    placeholder="Search ..."
                    class="form-control"
                  />
                </div>-->
              </nav>

              <ul class="navbar-nav topbar-nav ms-md-auto align-items-center">
                <li
                  class="nav-item topbar-icon dropdown hidden-caret d-flex d-lg-none"
                ><!--
                  <a
                    class="nav-link dropdown-toggle"
                    data-bs-toggle="dropdown"
                    href="#"
                    role="button"
                    aria-expanded="false"
                    aria-haspopup="true"
                  >
                  <i class="fa fa-search"></i>
                  </a>
                  <ul class="dropdown-menu dropdown-search animated fadeIn">
                    <form class="navbar-left navbar-form nav-search">
                      <div class="input-group">
                        <input
                          type="text"
                          placeholder="Search ..."
                          class="form-control"
                        />
                      </div>
                    </form>
                  </ul>-->
                </li>
                <!--<li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="messageDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-envelope"></i>
                  </a>
                  <ul
                    class="dropdown-menu messages-notif-box animated fadeIn"
                    aria-labelledby="messageDropdown"
                  >
                    <li>
                      <div
                        class="dropdown-title d-flex justify-content-between align-items-center"
                      >
                        Messages
                        <a href="#" class="small">Mark all as read</a>
                      </div>
                    </li>
                    <li>
                      <div class="message-notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="kaiadmin/assets/img/jm_denis.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Jimmy Denis</span>
                              <span class="block"> How are you ? </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="kaiadmin/assets/img/chadengle.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Chad</span>
                              <span class="block"> Ok, Thanks ! </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="kaiadmin/assets/img/mlane.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Jhon Doe</span>
                              <span class="block">
                                Ready for the meeting today...
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="kaiadmin/assets/img/talha.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="subject">Talha</span>
                              <span class="block"> Hi, Apa Kabar ? </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all messages<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li>-->
                <!--<li class="nav-item topbar-icon dropdown hidden-caret">
                  <a
                    class="nav-link dropdown-toggle"
                    href="#"
                    id="notifDropdown"
                    role="button"
                    data-bs-toggle="dropdown"
                    aria-haspopup="true"
                    aria-expanded="false"
                  >
                    <i class="fa fa-bell"></i>
                    <span class="notification">4</span>
                  </a>
                  <ul
                    class="dropdown-menu notif-box animated fadeIn"
                    aria-labelledby="notifDropdown"
                  >
                    <li>
                      <div class="dropdown-title">
                        You have 4 new notification
                      </div>
                    </li>
                    <li>
                      <div class="notif-scroll scrollbar-outer">
                        <div class="notif-center">
                          <a href="#">
                            <div class="notif-icon notif-primary">
                              <i class="fa fa-user-plus"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> New user registered </span>
                              <span class="time">5 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-success">
                              <i class="fa fa-comment"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Rahmad commented on Admin
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-img">
                              <img
                                src="kaiadmin/assets/img/profile2.jpg"
                                alt="Img Profile"
                              />
                            </div>
                            <div class="notif-content">
                              <span class="block">
                                Reza send messages to you
                              </span>
                              <span class="time">12 minutes ago</span>
                            </div>
                          </a>
                          <a href="#">
                            <div class="notif-icon notif-danger">
                              <i class="fa fa-heart"></i>
                            </div>
                            <div class="notif-content">
                              <span class="block"> Farrah liked Admin </span>
                              <span class="time">17 minutes ago</span>
                            </div>
                          </a>
                        </div>
                      </div>
                    </li>
                    <li>
                      <a class="see-all" href="javascript:void(0);"
                        >See all notifications<i class="fa fa-angle-right"></i>
                      </a>
                    </li>
                  </ul>
                </li> -->

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
                        <a class="dropdown-item" href="#" onclick="loadIframe('/auth/change_password.php')">Reset password</a>
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
          <iframe class="dashboard-iframe" id="iframe" src="http://<?php echo $host; ?>:9090/ui/"></iframe>
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
    <script src="kaiadmin/assets/js/core/bootstrap.min.js"></script>

    <!-- jQuery Scrollbar -->
    <script src="kaiadmin/assets/js/plugin/jquery-scrollbar/jquery.scrollbar.min.js"></script>

    <!-- Chart JS -->
    <script src="kaiadmin/assets/js/plugin/chart.js/chart.min.js"></script>

    <!-- jQuery Sparkline -->
    <script src="kaiadmin/assets/js/plugin/jquery.sparkline/jquery.sparkline.min.js"></script>

    <!-- Chart Circle -->
    <script src="kaiadmin/assets/js/plugin/chart-circle/circles.min.js"></script>

    <!-- Datatables -->
    <script src="kaiadmin/assets/js/plugin/datatables/datatables.min.js"></script>

    <!-- Bootstrap Notify -->
    <script src="kaiadmin/assets/js/plugin/bootstrap-notify/bootstrap-notify.min.js"></script>

    <!-- jQuery Vector Maps -->
    <script src="kaiadmin/assets/js/plugin/jsvectormap/jsvectormap.min.js"></script>
    <script src="kaiadmin/assets/js/plugin/jsvectormap/world.js"></script>

    <!-- Sweet Alert -->
    <script src="kaiadmin/assets/js/plugin/sweetalert/sweetalert.min.js"></script>

    <!-- Kaiadmin JS -->
    <script src="kaiadmin/assets/js/kaiadmin.min.js"></script>

    <!-- Kaiadmin DEMO methods, don't include it in your project! -->
   <!-- <script src="kaiadmin/assets/js/setting-demo.js"></script>
    <script src="kaiadmin/assets/js/demo.js"></script>-->
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
