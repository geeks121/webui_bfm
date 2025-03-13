<?php
$p = $_SERVER['HTTP_HOST'];
$x = explode(':', $p);
$host = $x[0];// Get the host dynamically
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
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
  <meta name="theme-color" content="#5e72e4">
  <title>BOX UI - Argon</title>
  <link rel="icon" href="webui/assets/luci.ico" type="image/x-icon">
  <link rel="stylesheet" href="tools_argon/css&fonts/argon.css">
  <style>

   .decorative-img::before,
   .decorative-img-sidebar::before {
      content: 'BOX UI - Argon'; /* Replace with the text you want to display */
    }

  </style>
</head>
<body>
  <div class="wrapper">
    <!-- Sidebar -->
    <div id="mySidebar" class="sidebar">
      <!-- Decorative PNG Image at the Top -->
      <div class="decorative-img"></div>

      <ul>
        <li>
          <button class="dropdown-btn">
            <span class="material-symbols--dashboard-rounded" id="status"></span>Status
            <span class="dropdown-icon ci--caret-right-sm"></span> <!-- Dropdown icon -->
          </button>
          <div class="dropdown-container">
            <a onclick="loadContent('tools_argon/sysinfo.php')">Overview</a>
            <a onclick="loadContent('/tools_argon/logs.php')">Magisk Log</a>
          </div>
        </li>
        <li>
          <button class="dropdown-btn">
            <span class="garden--tray-gear-26" id="gear"></span>System
            <span class="dropdown-icon iconify ci--caret-right-sm"></span> <!-- Dropdown icon -->
          </button>
          <div class="dropdown-container">
          <a onclick="loadContent('/select_theme/theme.php')"> Select Theme</a>
            <a onclick="loadContent('/tiny/opsi.php')"> File Manager</a>
            <a onclick="loadContent('/auth/change_password.php')"> Administration</a>
            <a onclick="loadContent('/auth/manage_login.php')"> Manage Login</a>
            <a onclick="loadContent('tools_argon/reboot.php')"> Reboot</a>
          </div>
        <li>
          <button class="dropdown-btn">
            <span class="ic--twotone-miscellaneous-services" id="services"></span> Services
            <span class="dropdown-icon iconify ci--caret-right-sm"></span> <!-- Dropdown icon -->
          </button>
          <div class="dropdown-container">
            <a onclick="loadContent('https://openspeedtest.com/Get-widget.php')"> Speed Test</a>
            <a onclick="loadContent('/tools_argon/smsviewer.php')"> SMS Viewer</a>
            <a onclick="loadContent('/tools/ocgen/index.php')"> Config Generator</a>
            <a onclick="loadContent('/tools_argon/modpes.php')"> Airplane Pilot</a>
            <a onclick="loadContent('http://<?php echo $p; ?>:3001')"> Terminal</a>
          </div>
        </li>
        <li>
          <button class="dropdown-btn">
            <span class="solar--box-bold-duotone" id="box"></span>Box
            <span class="dropdown-icon iconify ci--caret-right-sm"></span> <!-- Dropdown icon -->
          </button>
          <div class="dropdown-container">
            <a onclick="loadContent('/tools_argon/opsi_box.php')"> Box For Root</a>
            <a onclick="loadContent('/tools_argon/opsi_cfm.php')"> Clash For Magisk</a>
          </div>
        </li>
        <li>
          <button class="dropdown-btn">
            <span class="icon-park-solid--network-tree" id="network"></span> Network
            <span class="dropdown-icon iconify ci--caret-right-sm"></span> <!-- Dropdown icon -->
          </button>
          <div class="dropdown-container">
            <a onclick="loadContent('/tools_argon/opsi_interface.php')">Interface</a>
            <a onclick="loadContent('/tools/signalpro.php')">Signal Pro</a>
            <a onclick="loadContent('/tools_argon/hotspot.php')">Wireless</a>
            <a onclick="loadContent('/tools_argon/vnstat.php')">Bandwith</a>
            <a onclick="loadContent('/tools/qos.php')">QOS</a>
          </div>
        </li>
        <li><button class="dropdown-btn" onclick="loadContent('/tools_argon/article.html'); ById()"><i class="material-symbols--package-rounded" id="box"></i> Documentation</button></li>
        <li><button class="dropdown-btn" onclick="loadContent('auth/logout.php'); refreshPage()"><i class="ri--logout-box-line"></i> Logout</button></li>
      </ul>
      </div>
      
      <div id="loading-container" class="loading-container" style="display:none;">
        <span class="svg-spinners--bars-rotate-fade" style="margin-right: 8px;"></span>
        <span>Loading...</span>
      </div>

      <!-- The main content panel remains empty -->
      <div class="main-panel"></div>  
    
      <!-- Content loaded in iframe will appear here -->
      <div id="iframeContainer" class="iframe-container"></div>

      <!-- Overlay to block interactions outside the sidebar -->
      <div id="overlay" class="overlay" onclick="closeNav()"></div>

      <!-- Decorative PNG image next to the toggle button -->
      <div class="decorative-img-sidebar"></div>

      <!-- Toggle button to open/close the sidebar -->
      <button class="toggle-btn" onclick="openNav()"><span class="eva--menu-2-fill"></span></button>

      <!-- Refresh button -->
      <button class="refresh-btn" onclick="refreshPage()">Refresh</button>
    </div>
  </div>
  
<script>
  // Open the sidebar
  function openNav() {
    document.getElementById("mySidebar").classList.add("open");
    document.getElementById("overlay").classList.add("open");
    document.querySelector(".toggle-btn").style.display = "none"; // Hide the toggle button after sidebar opens
    document.documentElement.style.overflow = "hidden";
    document.body.style.overflow = "hidden";
  }

  // Close the sidebar
  function closeNav() {
    document.getElementById("mySidebar").classList.remove("open");
    document.getElementById("overlay").classList.remove("open");
    document.querySelector(".toggle-btn").style.display = "block"; // Show the toggle button when the sidebar is closed
    document.body.style.overflow = "auto";
    document.documentElement.style.overflow = "auto";
  }

// Load content dynamically
function loadContent(url) {
  // Tampilkan efek loading
  document.getElementById("loading-container").style.display = "flex";
  
  // Close all open dropdowns
  closeDropdown();

  const iframeContainer = document.getElementById('iframeContainer');
  iframeContainer.innerHTML = `<iframe src="${url}" allowfullscreen></iframe>`;
  closeNav(); // Close the sidebar automatically after loading content

  // Tunggu iframe untuk selesai dimuat
  const iframe = iframeContainer.querySelector("iframe");
  iframe.onload = function() {
    // Sembunyikan efek loading setelah konten dimuat
    document.getElementById("loading-container").style.display = "none";

    // Mengatur tinggi iframe berdasarkan konten
    adjustIframeHeight(iframe);
  };
}

function adjustIframeHeight(iframe) {
  if (!iframe || !iframe.contentWindow || !iframe.contentWindow.document) {
    return; // Pastikan iframe tersedia
  }

  const iframeDoc = iframe.contentDocument || iframe.contentWindow.document;
  const body = iframeDoc.body;

  if (!body) {
    return; // Pastikan body tidak null
  }

  // Mengambil tinggi konten dalam iframe
  const contentHeight = body.scrollHeight;

  // Menetapkan batas max-height dan min-height
  const maxHeight = (1000 * window.innerHeight) / 100; // max-height dalam vh
  const minHeight = (101 * window.innerHeight) / 100; // min-height dalam vh

  // Menyesuaikan tinggi iframe berdasarkan konten dan menambahkan 30px ekstra
  iframe.style.height = (Math.min(Math.max(contentHeight, minHeight), maxHeight) + 30) + 'px';
}

  // Function to refresh the page
  function refreshPage() {
    location.reload();
  }

  // Close the dropdown container when an item is clicked
  function closeDropdown() {
    var dropdownContainers = document.getElementsByClassName("dropdown-container");
    for (var i = 0; i < dropdownContainers.length; i++) {
      dropdownContainers[i].classList.remove("active");
    }

    // Also reset the dropdown button's active state
    var dropdownButtons = document.getElementsByClassName("dropdown-btn");
    for (var i = 0; i < dropdownButtons.length; i++) {
      dropdownButtons[i].classList.remove("active");
    }
  }
document.querySelectorAll(".dropdown-btn").forEach(button => {
    button.addEventListener("click", function () {
        // Hapus class "clicked" dari semua tombol
        document.querySelectorAll(".dropdown-btn").forEach(item => {
            item.classList.remove("clicked");
        });

        // Tambahkan class "clicked" hanya ke tombol yang diklik
        this.classList.add("clicked");
    });
});
  // Add underline to clicked item in dropdown and remove it from other items
document.addEventListener("DOMContentLoaded", function () {
  document.body.addEventListener("click", function (event) {
    var target = event.target.closest("a"); // Cari elemen <a> terdekat

    if (target && target.closest(".dropdown-container")) {
      console.log("Clicked element:", target); // Debugging

      // Hapus "clicked" dari semua <a> dalam semua dropdown-container
      document.querySelectorAll(".dropdown-container a").forEach(function (item) {
        item.classList.remove("clicked");
      });

      // Tambahkan "clicked" hanya ke elemen yang diklik
      target.classList.add("clicked");
    }
  });
});

  // Dropdown function for All
function resetDropdowns() {
  var allDropdowns = document.getElementsByClassName("dropdown-container");
  for (var j = 0; j < allDropdowns.length; j++) {
    allDropdowns[j].classList.remove("open");
    var button = allDropdowns[j].previousElementSibling;
    if (button) {
      button.classList.remove("active");
      var icon = button.querySelector(".dropdown-icon");
      if (icon) icon.style.transform = "rotate(0deg)";
    }
  }
}

var dropdown = document.getElementsByClassName("dropdown-btn");
for (var i = 0; i < dropdown.length; i++) {
  dropdown[i].addEventListener("click", function (event) {
    event.stopPropagation(); // Prevent closing on click inside dropdown
    var dropdownContent = this.nextElementSibling;
    var dropdownIcon = this.querySelector(".dropdown-icon");

    // Toggle the clicked dropdown
    if (dropdownContent.classList.contains("open")) {
      dropdownContent.classList.remove("open"); // Close the dropdown
      dropdownIcon.style.transform = "rotate(0deg)"; // Reset rotation
      this.classList.remove("active");
    } else {
      resetDropdowns(); // Reset all before opening new one
      dropdownContent.classList.add("open"); // Open the dropdown
      this.classList.add("active");
      dropdownIcon.style.transform = "rotate(90deg)"; // Apply rotation
    }
  });
}

function ById() {
  resetDropdowns(); // Reset dropdowns opening new one
  document.querySelectorAll(".dropdown-container a").forEach(function (item) {
    item.classList.remove("clicked");
  });
}

  // Load "System Info" content automatically when the page loads
  window.onload = function() {
    loadContent('tools_argon/sysinfo.php'); // Automatically load System Info
  }

  // Prevent scrolling when the sidebar is open
  document.getElementById("mySidebar").addEventListener("transitionend", function() {
    // Optionally, do something after the sidebar transition ends
    // This is where you can reset scroll if needed after the transition is finished
    if (!document.getElementById("mySidebar").classList.contains("open")) {
      document.body.style.overflow = "auto"; // Ensure scroll is enabled after sidebar close
    }
  });

</script>
</body>
</html>