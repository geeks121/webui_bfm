<?php
?>
<!DOCTYPE html>
<html>
<head>
<?php
        $title = "Vless YAML Generator";
        include("inc/header.php");
    ?>
    <style>
        .server {
            border: 2px solid #ddd;
            padding: 10px;
            margin-bottom: 10px;
        }
        .container {
            margin-top: 50px;
        }
        .result-textarea {
            width: 100%;
        }
    </style>
</head>
<body class="<?php echo getThemeClass(); ?>">
    <?php include('inc/navbar.php'); ?>
    <div class="container">
        <div class="row py-2">
            <div class="col-lg-6 col-md-12 mx-auto mt-3">
                <div class="card bg-info bg-transparent box-shadow">
				<div class="card">
			<div class="card-header">
                <div class="text-center">
    	<h3><i class="fa fa-gg-circle" aria-hidden="true"></i> Vless YAML Generator</h3>
				</div>
			</div>
                	<div class="col-lg-12"><p>
	<?php
// fungsi untuk menguraikan URL vless
$vless_url = '';
$reverse_vless = false;
$username = '';

if (isset($_POST['import'])) {
    if (empty($_POST['vless_url'])) {
        // Tampilkan notifikasi error jika URL kosong
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Please enter a valid VL URL.</p>";
        echo "</div></html>";
    } elseif (!preg_match('/^(vless):\/\/[^@]+@/', $_POST['vless_url'])) {
        // Tampilkan notifikasi error jika URL tidak sesuai format
        echo "<html>";
        echo "<div class='alert alert-danger'>";
        echo "<h3>Error!</h3>";
        echo "<p>Invalid URL. Please enter a valid URL starting with 'vless://'.</p>";
        echo "</div></html>";
    } else {
        $vless_url = $_POST['vless_url'];
        $reverse_vless = isset($_POST['reverse_vless']);

        // Mengecek nilai TLS
        function isTLSEnabled($vless_url) {
            $parsed_url = parse_url($vless_url);

            // Periksa apakah komponen 'host', 'port', dan 'user' ada dalam URL
            if (isset($parsed_url['host']) && isset($parsed_url['port']) && isset($parsed_url['user'])) {
                $port = $parsed_url['port'];

                if ($port == 443 || strpos($port, '43') !== false) {
                    return true;
                } else {
                    return false;
                }
            } else {
                // Tampilkan notifikasi error jika salah satu komponen hilang
                echo "<html>";
                echo "<div class='alert alert-danger'>";
                echo "<h3>Error!</h3>";
                echo "<p>The URL must contain 'host', 'port', and 'UUID' components. Please enter a valid URL with these components.</p>";
                echo "</div></html>";
                return false;
            }
        }

        // Output TLS
        $TLS = isTLSEnabled($vless_url);

        if ($TLS) {
            // menguraikan URL vless hanya jika TLS valid
            $parsed_url = parse_url($vless_url);
        }
    }
}

    // menguraikan URL vless
    $parsed_url = parse_url($vless_url);
    $vless_config = array();
	$vless_config['name'] = isset($parsed_url['fragment']) ? str_replace(["+", "%2F", "%20"], [" ", "/", " "], urldecode($parsed_url['fragment'])) : 'vless';
	$vless_config['server'] = isset($parsed_url['host']) ? $parsed_url['host'] : '';
    $vless_config['port'] = isset($parsed_url['port']) ? $parsed_url['port'] : '';
	$vless_config['host]'] = isset($parsed_url['host']) ? $parsed_url['host'] : '';
	
	// mengambil parameter
    $query_string = isset($parsed_url['query']) ? $parsed_url['query'] : '';
    $query_array = array();
    parse_str($query_string, $query_array);
    $vless_config['security'] = isset($query_array['security']) ? $query_array['security'] : '';
    $vless_config['headerType'] = isset($query_array['headerType']) ? $query_array['headerType'] : '';
	
		// mencari array host 
	$vless_config['host'] = '';
	if (strstr($query_string, 'host=')) {
    $vless_config['host'] = substr($query_string, strpos($query_string, 'host=') + 5);
    if (strstr($vless_config['host'], '&')) {
        $vless_config['host'] = substr($vless_config['host'], 0, strpos($vless_config['host'], '&'));
		}
    if (strstr($vless_config['host'], '=')) {
        $vless_config['host'] = str_replace('=', '', $vless_config['host']);
		}
	}
	
	    // mendapatkan username dan password dari URL
if (isset($parsed_url['user'])) {
    $username_password = explode("@", $parsed_url['user']);
    $username = $username_password[0];
    
    if (!empty($username)) {
        $vless_config['uuid'] = $username;
    } else {
        $vless_config['uuid'] = ''; // Kosongkan jika $username kosong
    }
} else {
    $vless_config['uuid'] = ''; // Kosongkan jika $parsed_url['user'] tidak ada
}

	
	// mencari array path	
    $vless_config['path'] = '';
	$query_string = str_replace('=', '', $query_string); // hilangkan tanda =
	$query_string = urldecode($query_string); // konversi ASCII
	$query_string = rawurldecode($query_string); // konversi semua karakter escape
    if (strstr($query_string, 'path')) {
        $vless_config['path'] = substr($query_string, strpos($query_string, 'path') + 4);
        if (strstr($vless_config['path'], '&')) {
            $vless_config['path'] = substr($vless_config['path'], 0, strpos($vless_config['path'], '&'));
          }
	}
	
		// mencari array security	
    $vless_config['security'] = '';
    if (strstr($query_string, 'security')) {
        $vless_config['security'] = substr($query_string, strpos($query_string, 'security') + 8);
        if (strstr($vless_config['security'], '&')) {
            $vless_config['security'] = substr($vless_config['security'], 0, strpos($vless_config['security'], '&'));
         }
    }
	// mencari array fp	
    $vless_config['fp'] = '';
    if (strstr($query_string, 'fp')) {
        $vless_config['fp'] = substr($query_string, strpos($query_string, 'fp') + 2);
        if (strstr($vless_config['fp'], '&')) {
            $vless_config['fp'] = substr($vless_config['fp'], 0, strpos($vless_config['fp'], '&'));
         }
    }
	// mencari array pbk	
    $vless_config['pbk'] = '';
    if (strstr($query_string, 'pbk')) {
        $vless_config['pbk'] = substr($query_string, strpos($query_string, 'pbk') + 3);
        if (strstr($vless_config['pbk'], '&')) {
            $vless_config['pbk'] = substr($vless_config['pbk'], 0, strpos($vless_config['pbk'], '&'));
         }
    }
	// mencari array sid	
    $vless_config['sid'] = '';
    if (strstr($query_string, 'sid')) {
        $vless_config['sid'] = substr($query_string, strpos($query_string, 'sid') + 3);
        if (strstr($vless_config['sid'], '&')) {
            $vless_config['sid'] = substr($vless_config['sid'], 0, strpos($vless_config['sid'], '&'));
         }
    }
		// mencari array sni	
    $vless_config['sni'] = '';
    if (strstr($query_string, 'sni')) {
        $vless_config['sni'] = substr($query_string, strpos($query_string, 'sni') + 3);
        if (strstr($vless_config['sni'], '&')) {
            $vless_config['sni'] = substr($vless_config['sni'], 0, strpos($vless_config['sni'], '&'));
         }
       }		
	// mencari array flow	
    $vless_config['flow'] = '';
    if (strstr($query_string, 'flow')) {
        $vless_config['flow'] = substr($query_string, strpos($query_string, 'flow') + 4);
        if (strstr($vless_config['flow'], '&')) {
            $vless_config['flow'] = substr($vless_config['flow'], 0, strpos($vless_config['flow'], '&'));
        }
		
	// mencari array type
    $vless_config['type'] = isset($query_array['type']) ? $query_array['type'] : '';
    } else {
        $vless_config['type'] = isset($query_array['type']) ? $query_array['type'] : '';
    }
	
		// mencari array serviceName for grpc	
    $vless_config['serviceName'] = '';
    if (strstr($query_string, 'serviceName')) {
        $vless_config['serviceName'] = substr($query_string, strpos($query_string, 'serviceName') + 11);
        if (strstr($vless_config['serviceName'], '&')) {
            $vless_config['serviceName'] = substr($vless_config['serviceName'], 0, strpos($vless_config['serviceName'], '&'));
        }
	}
	// jika reverse Vless diaktifkan, tukar posisi output "post" dari elemen name="sni" dan name="server"
    if($reverse_vless) {
        $temp = $vless_config['sni'];
        $vless_config['sni'] = $vless_config['server'];
        $vless_config['server'] = $temp;
   }	
	
	// Fungsi untuk mengenali type vless : ws or grpc
   function getNetType($vless_config) {
  return isset($vless_config['type']) ? $vless_config['type'] : '';
	}
	
		// Fungsi untuk mengenali security vless
   function getSecType($vless_config) {
  return isset($vless_config['security']) ? $vless_config['security'] : '';
	}

	// Fungsi untuk mengenali flow
function getFlowArray($vless_config) {
  $url = parse_url($vless_config);
  $query_params = array();

  if (isset($url['query'])) {
    parse_str($url['query'], $query_params);

    if (isset($query_params['flow'])) {
      return $query_params['flow'];
    }
  }

  return '';
}

if(isset($_POST['save'])){
    $vless_config['name'] = $_POST['name'];
    $vless_config['server'] = $_POST['server'];
    $vless_config['port'] = $_POST['port'];
    $vless_config['type'] = $_POST['network'];
    $vless_config['uuid'] = $_POST['uuid'];
    $vless_config['sni'] = $_POST['sni'];
    $vless_config['udp'] = ($_POST['udp'] == 'true') ? true : false;
    $vless_config['skip_cert_verify'] = ($_POST['skip_cert_verify'] == 'true') ? true : false;
    $vless_config['path'] = isset($_POST['path']) && !empty($_POST['path']) ? $_POST['path'] : '';
    $vless_config['host'] = isset($_POST['host']) && !empty($_POST['host']) ? $_POST['host'] : '';
    $vless_config['tls'] = isset($_POST['tls']) ? ($_POST['tls'] == 'true') : false;

	
   // Membaca nama file yaml yang dipilih
    $yaml_name = $_POST['yaml_name'];
	
	// Membaca nama Interface
	$interfaceName = $_POST['interface_name'];
	
	// Membaca nama network
	$networkType = isset($_POST['network']) && !empty($_POST['network']) ? $_POST['network'] : '';
	
	// jika nilai $_POST['network'] adalah tcp, maka kosongkan nilainya
	if ($networkType == 'tcp') {
    $networkType = '';
	}
	
	// Membaca nama host
	$hostName = isset($_POST['host']) && !empty($_POST['host']) ? $_POST['host'] : '';
	
	// Membaca nama flow
	$flowName = isset($_POST['flow']) && !empty($_POST['flow']) ? $_POST['flow'] : '';
	
	// Membaca nama flow
	$PubKey = isset($_POST['pbk']) && !empty($_POST['pbk']) ? $_POST['pbk'] : '';
	
	// Membaca nama path
	$pathName = isset($_POST['path']) && !empty($_POST['path']) ? $_POST['path'] : '';
	
	// Membaca nama Grpc-Name
	$grpcName = isset($_POST['grpc-name']) && !empty($_POST['grpc-name']) ? $_POST['grpc-name'] : '';

    // Membaca isi file yaml
    $yaml_content = file_get_contents("/data/adb/box/clash/config/proxy_provider/" . $yaml_name . ".yaml");	


	// Mengecek isi config yaml, jika ada kata "proxies:" maka jangan tuliskan kata tersebut, jika belum ada maka tuliskan di barisan pertama.
	if(strpos($yaml_content, 'proxies:') === false) {
	$yaml_content = "proxies:\n" . $yaml_content;
	}

// Buatkan fungsi $yaml_content untuk menyimpannya ke dalam config dengan isi berikut
    $yaml_content .= "- name: " . $_POST['name'] . "\n";
	$yaml_content .= "  type: " . $_POST['type'] . "\n";
	$yaml_content .= "  server: " . $_POST['server'] . "\n";
	$yaml_content .= "  port: " . $_POST['port'] . "\n";
	$yaml_content .= "  uuid: " . $_POST['uuid'] . "\n";
	$yaml_content .= "  cipher: " . $_POST['cipher'] . "\n";
	$yaml_content .= "  skip-cert-verify: " . $_POST['skip_cert_verify'] . "\n";
	$yaml_content .= "  tls: " . $_POST['tls'] . "\n";
	$yaml_content .= "  servername: " . $_POST['sni'] . "\n";
	// tcp
	if (!empty($networkType)) {
        $yaml_content .= "  network: " . $_POST['network'] . "\n";
		}
	// xtls	(direct)
	if (!empty($flowName)) {
		$yaml_content .= "  flow: " . $_POST['flow'] . "\n";
		}
	// xtls Reality
	if (!empty($PubKey)) {
		$yaml_content .= "  network: tcp\n";
		$yaml_content .= "  reality-opts:\n";
		$yaml_content .= "      public-key: " . $_POST['pbk'] . "\n";
		$yaml_content .= "      short-id: " . $_POST['sid'] . "\n";
		$yaml_content .= "  client-fingerprint: " . $_POST['fp'] . "\n";
		$yaml_content .= "  xudp: " . $_POST['xudp'] . "\n";
		}
	// grpc
	if (!empty($grpcName)) {
		$yaml_content .= "  grpc-opts:\n";
		$yaml_content .= "      grpc-service-name: " . $_POST['grpc-name'] . "\n";
		}
	// ws	
	if (!empty($pathName)) {
	$yaml_content .= "  ws-opts:\n      path: " . $_POST['path'] . "\n";
	$yaml_content .= "      headers:\n";
	}
	if (!empty($hostName)) {
        $yaml_content .= "         Host: " . $_POST['host'] . "\n";
		}
	if (!empty($interfaceName)) {
    $yaml_content .= "  interface-name: " . $_POST['interface_name'] . "\n";
	}
                
    $yaml_content .= "  udp: " . $_POST['udp'] . "\n";

// Menyimpan isi file yaml yang telah diperbarui
file_put_contents("/data/adb/box/clash/config/proxy_provider/" . $yaml_name . ".yaml", $yaml_content);

// Tampilkan notifikasi sukses menyimpan config
			echo "<div class='alert alert-success'>";
			echo "<h4>YAML Configuration Saved!</h4>";
			echo "<p>File saved at <b>../data/adb/box/clash/config/proxy_provider/</b> as: <a href='/tools/file.php?p=box%2Fclash%2Fconfig%2Fproxy_provider&edit=$yaml_name.yaml'>$yaml_name.yaml</a> name</p>";
			echo " </div>";
			echo "<div class='row mb-3'>";
			echo "<div class='col-md-6'>";
			echo "<a href='vless.php' class='btn btn-primary'><i class='fa-solid fa-arrow-left'></i> Back</a>";
			echo " </div>";
			echo " </div>";
			echo "<form method='post'>";
			echo "<button type='submit' class='btn btn-danger' name='restart-oc'><i class='fa fa-refresh' aria-hidden='true'></i> Restart OpenClash</button>";
			echo "</form><p>";
			include "inc/javascript.php";
			include "inc/footer.php";
			exit();
}

if(isset($_POST['restart-oc'])) {
    exec('su -c /data/adb/box/scripts/box.iptables disable && su -c /data/adb/box/scripts/box.service stop &');
    exec('su -c /data/adb/box/scripts/box.service start &&  su -c /data/adb/box/scripts/box.iptables enable &');
    $message = "<b>BFR restarted..</b>";
    echo "<div class='alert alert-success' style='padding: 10px; color: #2b2b2b;'>{$message}</div>";
}

?>   
    <?php if (!preg_match('/^(vless):\/\//', $vless_url)): ?>
        <form method="post">
<fieldset id="servers">
    <div class="server">
            <label class="form-label" for="vless_url"><b>Vless URL:</b></label><br>
            <textarea class="form-control result-textarea" name="vless_url" rows="10" cols="50" placeholder="Enter Vless URL = vless://abcdefg.." required></textarea><br>
               

<label for="reverse-vless" class="btn btn-success" style="padding: 7px; cursor: pointer; box-shadow: 2px 2px 2px #999; border-radius: 5px; color: white;">
  <input type="checkbox" id="reverse-vless" name="reverse_vless" value="1">
   <i class="fa fa-random" aria-hidden="true"></i> Vless WS Reverse
</label>

			<button type="submit" class="btn btn-primary" name="import" style="margin-top: -6px; margin-left: 10px;"> <i class="fa fa-sign-in" aria-hidden="true"></i> Import</button><br><br>
			<?php include('inc/stats.php'); ?>
			</div><br>
		
</div>

</fieldset>
        </form>
    <?php else: ?>
	        <div class="form-container">
            <h4>Enter Vless Details:</h4>
				<div class="form-group">
        <form method="post">
            <?php
            // Nama folder yang akan ditampilkan isinya
            $folder = "/data/adb/box/clash/config/proxy_provider/";

            // Buka folder
            if ($handle = opendir($folder)) {
            ?>
                <!-- Tampilkan pilihan file di dalam folder -->
				  <fieldset id="servers">
				      <div class="server">
                <label for="yaml_name">Select YAML Config <a href='/tools/file.php?p=box%2Fclash%2Fconfig%2Fproxy_provider'>(check here)</a> :</label>
                <select name="yaml_name" class="form-control" id="yaml_name">
                    <?php
                    while (false !== ($entry = readdir($handle))) {
                        if ($entry != "." && $entry != "..") {
                            // Hilangkan ekstensi file
                            $entry_name = pathinfo($entry, PATHINFO_FILENAME);
                    ?>
                            <option value="<?= $entry_name ?>"><?= $entry ?></option>
                    <?php
                        }
                    }
                    ?>
                </select>
				</fieldset>
				                    </div>
                        </div>
						
            <?php
                // Tutup folder
                closedir($handle);
            }
            ?><br>
                <fieldset id="servers">
                    <legend>Servers:</legend>

    <div class="server">
                        <div class="form-group">
                            <label for="name">Vless Name:</label>
                            <input type="text" class="form-control" id="name" required name="name" value="<?php echo $vless_config['name']; ?>">
                        </div>
                        <div class="form-group">
                            <label for="type">Type:</label>
                            <input type="text" name="type" class="form-control" id="type" value="vless" readonly>
                        </div>
                        <div class="form-group">
                            <label for="server">Server:</label>
                            <input type="text" name="server" class="form-control" id="server" value="<?php echo $vless_config['server']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="port">Port:</label>
                            <input type="number" name="port" class="form-control" id="port" min="0" value="<?php echo $vless_config['port']; ?>" required>
                        </div>
                        <div class="form-group">
                            <label for="uuid">UUID:</label> 
						<input type='text' name='uuid' value="<?php echo $vless_config['uuid']; ?>" class="form-control" id="password" required>
						  </div>
                        <div class="form-group">
                            <label for="sni">SNI:</label>
                            <input type="text" name="sni" class="form-control" id="sni" value="<?php echo $vless_config['sni']; ?>" placeholder="Isikan SNI">
                        </div>
                        <div class="form-group">
                            <label for="cipher">Cipher:</label>
                            <input type="text" name="cipher" class="form-control" id="cipher" value="auto" readonly>
                        </div>
                        <div class="form-group">
                            <label for="udp">UDP:</label>
                            <select name="udp" class="form-control" id="udp">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="skip_cert_verify">Skip Cert Verify:</label>
                            <select name="skip_cert_verify" class="form-control" id="skip_cert_verify">
                                <option value="true">True</option>
                                <option value="false">False</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="tls">TLS:</label>
                            <select name="tls" class="form-control" id="tls">
                                <<option value="true" <?php if ($TLS) echo 'selected'; ?>>True</option>
                                <option value="false" <?php if (!$TLS) echo 'selected'; ?>>False</option>
                            </select>
							</div>
<?php
	// menentukan tpye Vless, Ws or Grpc, or Reality 

  $sec_type = getSecType($vless_config);

  if ($sec_type == 'reality') {
    echo '
                        <div class="form-group">
      <label for="security">Security: <font color="#d61334"><b>(You are using Vless Reality) <button class="popup-vreality" type="button"><i class="fa-solid fa-question"></i></button></b></font></label>
      <input type="text" name="security" class="form-control" id="security" value="' . $vless_config['security'] . '" readonly>
    </div>
    <div class="form-group">
      <label for="fp">Client Fingerprint:</label>
      <input type="text" name="fp" class="form-control" id="fp" value="' . $vless_config['fp'] . '" placeholder="none" readonly>
    </div>
    <div class="form-group">
      <label for="pbk">Public Key:</label>
      <input type="text" name="pbk" class="form-control" id="pbk" value="' . $vless_config['pbk'] . '" placeholder="none" readonly>
    </div>
    <div class="form-group">
      <label for="sid">Short ID:</label>
      <input type="text" name="sid" class="form-control" id="sid" value="' . $vless_config['sid'] . '" placeholder="none" readonly>
    </div>
    <div class="form-group">
      <label for="xudp">xUDP:</label>
		<select name="xudp" class="form-control" id="xudp">
            <option value="true">True</option>
            <option value="false">False</option>
		</select>	
    </div>
		';				
  } else {

  }

?>							
<?php
	// menentukan tpye Vless, Ws or Grpc 

  $net_type = getNetType($vless_config);

  if ($net_type == 'ws') {
    echo '  
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($vless_config['type']) ? $vless_config['type'] : '') . '" readonly>
                        </div>
		';				
  } else if ($net_type == 'grpc') {
    echo '
	
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($vless_config['type']) ? $vless_config['type'] : '') . '" readonly>
                        </div>
		';				
  } else if ($net_type == 'reality') {
    echo '
	
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($vless_config['type']) ? $vless_config['type'] : '') . '" readonly>
                        </div>
		';				
  } else {
	echo '
	
                        <div class="form-group">
                            <label for="network">Network:</label>
                            <input type="select" name="network" class="form-control" id="network" value="' . (isset($vless_config['type']) ? $vless_config['type'] : '') . '" readonly>
                        </div>
		';	
  }



?>
                <?php
	// menentukan tpye Vless, Ws or Grpc 

  $net_type = getNetType($vless_config);

  if ($net_type == 'ws') {
    echo '
                        <div class="form-group">
      <label for="ws_path">WebSocket Path:</label>
      <input type="text" name="path" class="form-control" id="ws_path" value="' . (isset($vless_config['path']) ? $vless_config['path'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
    <div class="form-group">
      <label for="ws_host">WebSocket Host:</label>
      <input type="text" name="host" class="form-control" id="ws_host" value="' . (isset($vless_config['host']) ? $vless_config['host'] : '') . '" placeholder="kosongkan jika bukan ws">
    </div>
		';				
  } else if ($net_type == 'grpc') {
    echo '
	
                        <div class="form-group">
                            <label for="grpc-name">gRPC Name:</label>
                            <input type="text" name="grpc-name" class="form-control" id="grpc-name" value="' . $vless_config['serviceName'] . '" placeholder="kosongkan jika bukan grpc">
                        </div>
		';				
  } else {

  }

?>
 <?php
	// menentukan array flow 
	
$flow_input_name = getFlowArray($vless_url);
if (!empty($flow_input_name)) {

    echo '                        <div class="form-group">
                            <label for="flow_name">Flow :</label>
                            <input type="text" name="flow" class="form-control" id="flow" value="' . $vless_config['flow'] . '" readonly>
                        </div>
		';
  } 
?>
						<div class="form-group">
                            <label for="interface_name">Interface Name (optional):</label>
                            <input type="text" name="interface_name" class="form-control" id="interface_name" placeholder="eth01">
                        </div></p>
						
                        <button type="submit" class="btn btn-danger" name="new"><i class="fa-solid fa-circle-plus"></i> New</button></br>
                    </div>
                </fieldset>
                
                <div class="d-grid gap-2 col-4 mx-auto">                   
                    <button name="save" type="submit" class="btn btn-success" align="center"><i class="fa-regular fa-floppy-disk"></i> Generate</button>
                </div>
            </form>
        <br>
	</div>
    <?php endif; ?>

	    <script>
        // JavaScript untuk menampilkan popup menggunakan Swal.fire
        const popupButton = document.querySelector('.popup-vreality');

        // Tampilkan popup saat tombol ditekan
        popupButton.addEventListener('click', () => {
            Swal.fire({
                title: 'Syarat Penggunaan Vless Reality!',
                html: '<div style="max-height: 300px; overflow-y: auto;">'+
			'<p>1. SNI yang dipakai pengguna dan disisi Server harus sama, jika beda requests rubah SNI</p>'+
                        '<p>2. SNI harus support TLS 1.3. Tes bugnya di <a href="https://www.cdn77.com/tls-test" target="blank">sini.</a></p>'+
                        '<p>3. SNI harus support HTTP2 atau H2. Tes bugnya di <a href="https://tools.keycdn.com/http2-test" target="blank">sini.</a></p>'+
                        '<p>4. SNI harus support memiliki header 2xx, dan bukan header redirect/CNAME 301/302. Contohnya HTTP/2 200. Cek headernya di <a href="https://tools.keycdn.com/curl" target="blank"> sini.</a></p>'+
		               '</div>',
                showCloseButton: true,
                icon: 'info',
                confirmButtonText: 'Tutup',
                allowOutsideClick: false
            });
        });
    </script>	
	<?php include("inc/javascript.php"); ?>
	<?php include("inc/js.php"); ?>
</body>
</html>
<?php include 'inc/footer.php'; ?>