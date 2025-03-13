<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Interface Management</title>
    <style>
body {
    font-family: Arial, sans-serif;
    background-color: transparent;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    margin: 0;
}

.basil--hotspot-outline {
  display: inline-block;
  width: 18px;
  height: 18px;
  --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cg fill='%23000' fill-rule='evenodd' clip-rule='evenodd'%3E%3Cpath d='M12 11.65a1.25 1.25 0 1 0 0 2.5a1.25 1.25 0 0 0 0-2.5M9.25 12.9a2.75 2.75 0 1 1 5.5 0a2.75 2.75 0 0 1-5.5 0'/%3E%3Cpath d='M12 7.65a5.25 5.25 0 0 0-3.712 8.962a.75.75 0 1 1-1.061 1.06a6.75 6.75 0 1 1 9.546 0a.75.75 0 0 1-1.06-1.06A5.25 5.25 0 0 0 12 7.649'/%3E%3Cpath d='M12 3.75a9.15 9.15 0 0 0-6.47 15.62a.75.75 0 1 1-1.06 1.06a10.65 10.65 0 1 1 15.06 0a.75.75 0 0 1-1.06-1.06A9.15 9.15 0 0 0 12 3.75'/%3E%3C/g%3E%3C/svg%3E");
  background-color: currentColor;
  -webkit-mask-image: var(--svg);
  mask-image: var(--svg);
  -webkit-mask-repeat: no-repeat;
  mask-repeat: no-repeat;
  -webkit-mask-size: 100% 100%;
  mask-size: 100% 100%;
}

.tdesign--usb-filled {
  display: inline-block;
  width: 16px;
  height: 16px;
  --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 24 24'%3E%3Cpath fill='%23000' d='M4 2h16v9h2v11H2V11h2zm14 9V4H6v7zM8 6.496h2.004V8.5H8zm6 0h2.004V8.5H14z'/%3E%3C/svg%3E");
  background-color: currentColor;
  -webkit-mask-image: var(--svg);
  mask-image: var(--svg);
  -webkit-mask-repeat: no-repeat;
  mask-repeat: no-repeat;
  -webkit-mask-size: 100% 100%;
  mask-size: 100% 100%;
}

.bi--ethernet {
  display: inline-block;
  width: 16px;
  height: 16px;
  --svg: url("data:image/svg+xml,%3Csvg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 16 16'%3E%3Cg fill='%23000'%3E%3Cpath d='M14 13.5v-7a.5.5 0 0 0-.5-.5H12V4.5a.5.5 0 0 0-.5-.5h-1v-.5A.5.5 0 0 0 10 3H6a.5.5 0 0 0-.5.5V4h-1a.5.5 0 0 0-.5.5V6H2.5a.5.5 0 0 0-.5.5v7a.5.5 0 0 0 .5.5h11a.5.5 0 0 0 .5-.5M3.75 11h.5a.25.25 0 0 1 .25.25v1.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-1.5a.25.25 0 0 1 .25-.25m2 0h.5a.25.25 0 0 1 .25.25v1.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-1.5a.25.25 0 0 1 .25-.25m1.75.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v1.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25zM9.75 11h.5a.25.25 0 0 1 .25.25v1.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25v-1.5a.25.25 0 0 1 .25-.25m1.75.25a.25.25 0 0 1 .25-.25h.5a.25.25 0 0 1 .25.25v1.5a.25.25 0 0 1-.25.25h-.5a.25.25 0 0 1-.25-.25z'/%3E%3Cpath d='M2 0a2 2 0 0 0-2 2v12a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V2a2 2 0 0 0-2-2zM1 2a1 1 0 0 1 1-1h12a1 1 0 0 1 1 1v12a1 1 0 0 1-1 1H2a1 1 0 0 1-1-1z'/%3E%3C/g%3E%3C/svg%3E");
  background-color: currentColor;
  -webkit-mask-image: var(--svg);
  mask-image: var(--svg);
  -webkit-mask-repeat: no-repeat;
  mask-repeat: no-repeat;
  -webkit-mask-size: 100% 100%;
  mask-size: 100% 100%;
}

.container {
    width: 90%;
    background-color: white;
    box-shadow: 0 4px 8px rgba(0, 0, 0, 0.2);
    padding: 20px;
    text-align: center;
    margin-bottom: 10px;
}

.tab-container {
    width: 100px;
    height: 15px;
    background-color: #007bff;
    color: white;
    margin: 0 auto -3px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    position: relative;
    z-index: 1;
    box-shadow: 0 -5px 10px rgba(0, 0, 0, 0.1);
    font-size: 10px; /* Reduced font size for the tab text */
}

.icon-container {
    width: 100px;
    height: 50px;
    background-color: white;
    border-radius: 2px;
    margin: 0 auto 25px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    box-shadow: 0 2px 4px rgba(0, 0, 0, 0.1);
}

.icon-container .iconify {
    font-size: 18px; /* Reduced icon size */
    color: black;
}

.icon-container p {
    margin: 5px 0 0;
    font-size: 10px; /* Reduced font size for text under icon */
    color: black;
}

.status {
    text-align: left;
    margin-bottom: 20px;
    font-size: 12px; /* Reduced font size for status text */
    color: #555;
}

.status p {
    margin: 0px 0;
}

.buttons {
    display: flex;
    justify-content: space-between;
}

.buttons button {
    flex: 1;
    margin: 0 3px auto;
    padding: 6px 0px;
    font-size: 12px;
    border: none;
    border-radius: 3px;
    cursor: pointer;
    color: white;
}

.buttons button:hover {
    opacity: 0.9; /* Slight fade on hover */
}

.start {
    background-color: #28a745;
}

.stop {
    background-color: #dc3545;
}

.edit, .restart {
    background-color: #5e72e4;
}

.edit-form {
    display: none;
    position: fixed;
    z-index: 2;
    left: 50%;
    top: 40%;
    transform: translate(-50%, -50%);
    width: 300px;
    height: 280px;
    background-color: white;
    padding: 10px;
    box-sizing: border-box;
    border-radius: 2px;
    box-shadow: 0 4px 20px rgba(0, 0, 0, 0.3);
}

.edit-form h3 {
    margin: 20px 0;
    text-align: center;
    padding-left: 20px;
    font-size: 1em;
    font-weight: 550;
}
.edit-form p {
    margin: 2px 0;
    text-align: left;
    padding-left: 20px;
    font-size: 0.8em;
    font-weight: 500;
}

.edit-form textarea {
    width: 80%;
    margin: 0px 0;
    padding: 8px;
    border-radius: 3px;
    border: 1px solid #ccc;
    font-size: 10px;
    height: 30px;
}

.edit-form button {
    margin-top: 20px;
    margin-right: 10px;
    padding: 5px 10px;
    background-color: #007bff;
    color: white;
    border: none;
    border-radius: 2px;
    cursor: pointer;
    font-size: 14px;
    transition: background-color 0.3s ease;
    font-weight: 600;
}

.edit-form button:hover {
    background-color: #0056b3; /* Darker blue on hover */
}

/* Dark mode styles */
@media (prefers-color-scheme: dark) {
    body {
        background-color: transparent;
        color: #e0e0e0;
    }

    .container {
        background-color: #333;
        color: #e0e0e0;
    }

    .tab-container {
        background-color: #006bb3;
    }

    .icon-container {
        background-color: #444;
    }
    
    .icon-container p {
        color: #fff;
    }

    .icon-container .iconify {
        color: white;
    }

    .status {
        color: #bbb;
    }

    .buttons button {
        background-color: #555;
    }

    .buttons button:hover {
        opacity: 0.8;
    }

    .start {
        background-color: #4caf50 !important;
    }

    .stop {
        background-color: #f44336 !important;
    }

    .edit, .restart {
        background-color: #474f72 !important;
    }

    .edit-form {
        background-color: #333;
        color: #e0e0e0;
    }

    .edit-form button {
        background-color: #006bb3;
    }

    .edit-form button:hover {
        background-color: #0056b3;
    }
}
@media (min-width: 600px) {
    .tab-container, .icon-container {
        align-self: flex-start;
        margin-left: 20px;
    }

    .status {
        margin: auto;
        display: block;
        text-align: center;
        position: relative;
        top: -90px;
    }
    
    .buttons {
        position: relative;
        top: -70px;
        margin-bottom: -70px;
    }
    
    .edit-form {
         top: 30%;
    }
}
@media (min-width: 900px) {
    .tab-container, .icon-container {
        align-self: flex-start;
        margin-left: 20px;
    }

    .status {
        right: 100px;
        display: block;
        text-align: center;
        position: relative;
        top: -90px;
        margin-bottom: -90px;
    }
    .buttons {
        float: right;
        top: -48px;
        width: 300px;
    }
    
    .edit-form {
         top: 22%;
    }
}

    </style>
</head>
<body>
    <!-- WLAN Interface -->
    <div class="container" id="wlan-container">
        <div class="tab-container">
            <p><strong>Hotspot</strong></p>
        </div>
        <div class="icon-container">
            <span class="iconify basil--hotspot-outline"></span>
            <p>wlan+</p>
        </div>
        <div class="status" id="wlan-status">
            <p><strong>Status:</strong> <span id="wlan-status-text">Loading...</span></p>
            <p><strong>MAC:</strong> <span id="wlan-mac">Loading...</span></p>
            <p><strong>RX:</strong> <span id="wlan-rx">Loading...</span></p>
            <p><strong>TX:</strong> <span id="wlan-tx">Loading...</span></p>
            <p><strong>IPv4:</strong> <span id="wlan-ip">Loading...</span></p>
        </div>
        <div class="buttons">
            <button class="start" onclick="changeInterfaceStatus('wlan', 'enable')"><strong>START</strong></button>
            <button class="stop" onclick="changeInterfaceStatus('wlan', 'disable')"><strong>STOP</strong></button>
            <button class="edit" onclick="editInterfaceCommands('wlan')"><strong>EDIT</strong></button>
            <button class="restart" onclick="resetInterfaceCommands('wlan')"><strong>RESET</strong></button>
        </div>
        
        <!-- Edit Command Form -->
        <div id="wlan-edit-form" class="edit-form">
          <div style="text-align:center;">
            <h3>Edit Command For wlan+</h3>
            <p>Enable</p>
            <textarea id="wlan-enable-command" rows="3" placeholder="Contoh: service call tethering 4 null s16 random"></textarea><br>
            <p>Disable</p>
            <textarea id="wlan-disable-command" rows="3" placeholder="Contoh: su -c ifconfig wlan0 down"></textarea><br>
            <button onclick="saveInterfaceCommands('wlan')">Save</button>
            <button onclick="cancelEdit('wlan')">Cancel</button>
          </div>
        </div>
    </div>

    <!-- RNDIS Interface -->
    <div class="container" id="rndis-container">
        <div class="tab-container">
            <p><strong>USB Tethering</strong></p>
        </div>
        <div class="icon-container">
            <span class="iconify tdesign--usb-filled"></span>
            <p>rndis+</p>
        </div>
        <div class="status" id="rndis-status">
            <p><strong>Status:</strong> <span id="rndis-status-text">Loading...</span></p>
            <p><strong>MAC:</strong> <span id="rndis-mac">Loading...</span></p>
            <p><strong>RX:</strong> <span id="rndis-rx">Loading...</span></p>
            <p><strong>TX:</strong> <span id="rndis-tx">Loading...</span></p>
            <p><strong>IPv4:</strong> <span id="rndis-ip">Loading...</span></p>
        </div>
        <div class="buttons">
            <button class="start" onclick="changeInterfaceStatus('rndis', 'enable')"><strong>START</strong></button>
            <button class="stop" onclick="changeInterfaceStatus('rndis', 'disable')"><strong>STOP</strong></button>
            <button class="edit" onclick="editInterfaceCommands('rndis')"><strong>EDIT</strong></button>
            <button class="restart" onclick="resetInterfaceCommands('rndis')"><strong>RESET</strong></button>
        </div>
        
        <!-- Edit Command Form -->
        <div id="rndis-edit-form" class="edit-form">
           <div style="text-align:center;">
            <h3>Edit Command For rndis+</h3>
            <p>Enable</p>
            <textarea id="rndis-enable-command" rows="3" placeholder="Contoh: su -c svc usb setFunctions rndis"></textarea><br>
            <p>Disable</p>
            <textarea id="rndis-disable-command" rows="3" placeholder="Contoh: su -c svc usb setFunctions mtp"></textarea><br>
            <button onclick="saveInterfaceCommands('rndis')">Save</button>
            <button onclick="cancelEdit('rndis')">Cancel</button>
          </div>
        </div>
    </div>

    <!-- Ethernet Interface -->
    <div class="container" id="eth-container">
        <div class="tab-container">
            <p><strong>Ethernet</strong></p>
        </div>
        <div class="icon-container">
            <span class="iconify bi--ethernet"></span>
            <p>eth+</p>
        </div>
        <div class="status" id="eth-status">
            <p><strong>Status:</strong> <span id="eth-status-text">Loading...</span></p>
            <p><strong>MAC:</strong> <span id="eth-mac">Loading...</span></p>
            <p><strong>RX:</strong> <span id="eth-rx">Loading...</span></p>
            <p><strong>TX:</strong> <span id="eth-tx">Loading...</span></p>
            <p><strong>IPv4:</strong> <span id="eth-ip">Loading...</span></p>
        </div>
        <div class="buttons">
            <button class="start" onclick="changeInterfaceStatus('eth', 'enable')"><strong>START</strong></button>
            <button class="stop" onclick="changeInterfaceStatus('eth', 'disable')"><strong>STOP</strong></button>
            <button class="edit" onclick="editInterfaceCommands('eth')"><strong>EDIT</strong></button>
            <button class="restart" onclick="resetInterfaceCommands('eth')"><strong>RESET</strong></button>
        </div>
        
        <!-- Edit Command Form -->
        <div id="eth-edit-form" class="edit-form">
            <div style="text-align:center;">
            <h3>Edit Command For eth+</h3>
            <p>Enable</p>
            <textarea id="eth-enable-command" rows="3" placeholder="Contoh: su -c ifconfig eth0 up"></textarea><br>
            <p>Disable</p>
            <textarea id="eth-disable-command" rows="3" placeholder="Contoh: su -c ifconfig eth0 down"></textarea><br>
            <button onclick="saveInterfaceCommands('eth')">Save</button>
            <button onclick="cancelEdit('eth')">Cancel</button>
          </div>
        </div>
    </div>

    <script>
        function changeInterfaceStatus(interface, action) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'script.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    const response = JSON.parse(xhr.responseText);
                    updateStatus(interface, response);
                }
            };

            xhr.send(`interface=${interface}&action_type=${action}`);
        }

        function updateStatus(interface, data) {
            document.getElementById(`${interface}-status-text`).innerText = data.status;
            document.getElementById(`${interface}-mac`).innerText = data.mac;
            document.getElementById(`${interface}-rx`).innerText = data.rx;
            document.getElementById(`${interface}-tx`).innerText = data.tx;
            document.getElementById(`${interface}-ip`).innerText = data.ip;
        }
        
        function resetInterfaceCommands(interface) {
            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'script.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
            if (xhr.readyState == 4 && xhr.status == 200) {
            alert("Commands reset successfully!");
        }
           };

            xhr.send(`interface=${interface}&action_type=reset`);
        }

        function editInterfaceCommands(interface) {
            document.getElementById(`${interface}-edit-form`).style.display = 'block';

            // Ambil perintah
            const enableCommand = localStorage.getItem(`${interface}-enable-command`) || "";
            const disableCommand = localStorage.getItem(`${interface}-disable-command`) || "";

            // Isi kolom dengan perintah yang sesuai
            document.getElementById(`${interface}-enable-command`).value = enableCommand;
            document.getElementById(`${interface}-disable-command`).value = disableCommand;
        }

        function saveInterfaceCommands(interface) {
            const enableCommand = document.getElementById(`${interface}-enable-command`).value;
            const disableCommand = document.getElementById(`${interface}-disable-command`).value;

            const xhr = new XMLHttpRequest();
            xhr.open('POST', 'script.php', true);
            xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');

            xhr.onreadystatechange = function() {
                if (xhr.readyState == 4 && xhr.status == 200) {
                    alert("Commands saved successfully!");
                    cancelEdit(interface);
                }
            };

            xhr.send(`interface=${interface}&action_type=edit&enable_command=${encodeURIComponent(enableCommand)}&disable_command=${encodeURIComponent(disableCommand)}`);
        }

        function cancelEdit(interface) {
            document.getElementById(`${interface}-edit-form`).style.display = 'none';
        }

        setInterval(() => {
            const interfaces = ['wlan', 'rndis', 'eth'];
            interfaces.forEach(interface => {
                const xhr = new XMLHttpRequest();
                xhr.open('POST', 'script.php', true);
                xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
                
                xhr.onreadystatechange = function() {
                    if (xhr.readyState == 4 && xhr.status == 200) {
                        const response = JSON.parse(xhr.responseText);
                        updateStatus(interface, response);
                    }
                };

                xhr.send(`interface=${interface}&action_type=status`);
            });
        }, 1500);
    </script>
</body>
</html>