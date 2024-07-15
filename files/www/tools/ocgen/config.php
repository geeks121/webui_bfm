<?php
?>
<!DOCTYPE html>
<html>
<head>
<?php
        $title = "OpenClash Config Generator";
        include("inc/header.php");
    ?>
	<meta name="theme-color" content="#ffffff">
    <style>
        .proxy-provider {
            border: 1px solid #ccc;
            padding: 10px;
            margin-bottom: 10px;
        }
       h1 {
  text-align: center;
         }
    </style>
</head>
<body class="<?php echo getThemeClass(); ?>">
    <?php include('inc/navbar.php'); ?>
    <div class="container"></p>
        <div class="row py-2">
            <div class="col-lg-6 col-md-12 mx-auto mt-3">
                <div class="card bg-info bg-transparent box-shadow">
				<div class="card">
                <div class="card-header">
				<div class="text-center">
        <h4><i class="fa fa-cog fa-spin fa-1x fa-fw"></i> Openclash Config Generator</h4>
				</div>
				</div>
			</div>
                	<div class="col-lg-12"><p>
			
        
        <?php
function generateOpenClashConfig($configName, $proxyType, $pingInterval, $proxyGroupName, $proxyProviderData) {
    $config = "---
port: 7890
socks-port: 7891
redir-port: 7892
mixed-port: 7893
tproxy-port: 7895
ipv6: false
mode: rule
log-level: silent
allow-lan: true
external-controller: 0.0.0.0:9090
secret: ''
bind-address: '*'
unified-delay: true
dns:
  enable: true
  ipv6: false
  enhanced-mode: redir-host
  listen: 0.0.0.0:7874
  nameserver:
    - dhcp://\"eth1\"
    - dhcp://\"eth2\"
    - dhcp://\"usb0\"
    - 8.8.8.8
    - 8.8.4.4
    - https://dns.adguard.com/dns-query
    - https://dns.google/dns-query
    - tls://dns.adguard.com
    - tls://dns.google
  fallback:
    - 1.1.1.1
    - 8.8.4.4
    - https://cloudflare-dns.com/dns-query
    - 112.215.203.254
  default-nameserver:
    - 8.8.8.8
    - 1.1.1.1
    - 8.8.4.4
external-ui: '/usr/share/openclash/ui'
geodata-loader: memconservative
tcp-concurrent: true
sniffer:
  enable: true
  force-dns-mapping: true
  parse-pure-ip: true
tun:
  enable: true
  stack: system
  device: utun
  auto-route: false
  auto-detect-interface: false
  dns-hijack:
    - tcp://any:53
authentication:
  - Clash:7YVU9aQe
rules:
- DST-PORT,7895,REJECT
- DST-PORT,7892,REJECT
- IP-CIDR,198.18.0.1/16,REJECT,no-resolve
- MATCH,GLOBAL
proxy-groups:
  - name: $proxyGroupName
    type: $proxyType
    url-test: http://www.gstatic.com/generate_204
    interval: 900
";
    
    if ($proxyType === 'load-balance') {
        $config .= "    strategy: round-robin\n";
    }
    
    $config .= "    proxies:\n";
    foreach ($proxyProviderData as $proxyProvider) {
        $proxyProviderName = $proxyProvider['name'];
        $config .= "      - $proxyProviderName\n";
    }

    $config .= "proxy-providers:\n";

    foreach ($proxyProviderData as $proxyProvider) {
        $proxyProviderName = $proxyProvider['name'];
        $proxyProviderPath = $proxyProvider['path'];
        $config .= "  $proxyProviderName:\n";
        $config .= "    type: file\n";
        $config .= "    path: $proxyProviderPath\n";
        $config .= "    health-check:\n";
        $config .= "      enable: true\n";
        $config .= "      url: http://www.gstatic.com/generate_204\n";
        $config .= "      interval: 150\n";
    }

    return $config;
}

if(isset($_POST['save'])) {
    $configName = $_POST['configName'];
    $proxyType = $_POST['proxyType'];
    $pingInterval = $_POST['pingInterval'];
    $proxyGroupName = $_POST['proxyGroupName'];
    $proxyProviderNames = $_POST['proxyProviderName'];
    $proxyProviderPaths = $_POST['proxyProviderPath']; 

    $proxyProviderData = array();
    for ($i = 0; $i < count($proxyProviderNames); $i++) {
        $proxyProviderName = $proxyProviderNames[$i];
        $proxyProviderPath = $proxyProviderPaths[$i];
        $proxyProviderData[] = array('name' => $proxyProviderName, 'path' => $proxyProviderPath);
    }

    $openClashConfig = generateOpenClashConfig($configName, $proxyType, $pingInterval, $proxyGroupName, $proxyProviderData);

    $savePath = "/data/adb/box/clash/$configName.yaml";
    file_put_contents($savePath, $openClashConfig);

    // Tampilkan pesan sukses
    echo '<div class="alert alert-success">Konfigurasi berhasil disimpan di: <b> <a href="/tools/file.php?p=box%2Fclash">' . $savePath . ' </a></p></b>Silahkan switch confignya di dashboard <a href="/cgi-bin/luci/admin/services/openclash/config">Openclash</a></div>';      
}
?>

	<div class="form-container">
        <form method="post">
            <div class="form-group">
                <div class="proxy-provider">
<label for="yaml_name">YAML Openclash Config <a href='/tools/file.php?p=box%2Fclash'>(check here)</a> :</label>
				  <fieldset id="servers">
				      <div class="server">
                <label for="configName">Config Name:</label>
                <input type="text" class="form-control" name="configName" id="configName" placeholder="Games" required>
            </div>

            <div class="form-group">
                <label for="proxyType">Proxy Type:</label>
                <select class="form-control" name="proxyType" id="proxyType" required>
                    <option value="direct">Direct</option>
                    <option value="load-balance">Load Balance</option>
                    <option value="select">Select</option>
                </select>
            </div>

            <div class="form-group">
                <label for="pingInterval">Ping Interval (second):</label>
                <input type="number" class="form-control" name="pingInterval" id="pingInterval" value="150" required>
            </div>

            <div class="form-group">
                <label for="proxyGroupName">Proxy Group Name:</label>
                <input type="text" class="form-control" name="proxyGroupName" id="proxyGroupName" placeholder="Games" required>
            </div>

            <div id="proxyProviderContainer">
                <div class="proxy-provider">
                    <div class="form-group">
                        <label for="proxyProviderName">Proxy Provider Name:</label>
                        <input type="text" class="form-control" name="proxyProviderName[]" placeholder="Games" required>
                    </div>

                    <div class="form-group">
                        <label for="proxyProviderPath">Proxy Provider Path:</label>
                        <input type="text" class="form-control" name="proxyProviderPath[]" placeholder="./proxy_provider/games.yaml" required>
                    </div>
                </div>
            </div>

            <button type="button" class="btn btn-primary" id="addProxyProvider"><i class="fa-solid fa-plus"></i> Add New</button>

            <button type="submit" class="btn btn-success" name="save"><i class="fa-solid fa-floppy-disk"></i> Save</button>
        </form><p><br>
		</fieldset>
		</div>
		</div>
    </div>
	</div>
    <script>
        
        function addProxyProvider() {
            const container = document.getElementById("proxyProviderContainer");

            const div = document.createElement("div");
            div.className = "proxy-provider";

            const providerNameLabel = document.createElement("label");
            providerNameLabel.textContent = "Proxy Provider Name:";
            div.appendChild(providerNameLabel);

            const providerNameInput = document.createElement("input");
            providerNameInput.type = "text";
            providerNameInput.className = "form-control";
            providerNameInput.name = "proxyProviderName[]";
            providerNameInput.required = true;
            div.appendChild(providerNameInput);

            const providerPathLabel = document.createElement("label");
            providerPathLabel.textContent = "Proxy Provider Path:";
            div.appendChild(providerPathLabel);

            const providerPathInput = document.createElement("input");
			providerPathInput.type = "text";
			providerPathInput.className = "form-control";
			providerPathInput.name = "proxyProviderPath[]";
			providerPathInput.required = true;

// Menambahkan elemen <br> untuk memberikan jarak satu baris
			const lineBreak = document.createElement("br");

			div.appendChild(providerPathInput);
			div.appendChild(lineBreak);

			const deleteButton = document.createElement("button");
			deleteButton.type = "button";
			deleteButton.className = "btn btn-danger btn-sm delete-proxy-provider";
			deleteButton.innerHTML = '<i class="fa-solid fa-trash"></i> Delete'; // Adding the trash icon
			div.appendChild(deleteButton);

			container.appendChild(div);
            deleteButton.addEventListener("click", function() {
                div.remove();
            });
        }
        
        const addProxyProviderButton = document.getElementById("addProxyProvider");
        addProxyProviderButton.addEventListener("click", addProxyProvider);
    </script>
<?php include("inc/javascript.php"); ?>
</body>
</html>

<?php include 'inc/footer.php'; ?>