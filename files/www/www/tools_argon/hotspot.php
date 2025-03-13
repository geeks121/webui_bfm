<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Ganti Hotspot Android</title>
    <link rel="stylesheet" href="../tools/ocgen/data/fontawesome6/css/all.css" />
    <style>
        body {
            font-family: 'Segoe UI', sans-serif;
            background-color: transparent;
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            margin: 0;
        }
        h1 {
            text-align: center;
            color: #000;
            font-size: 20px;
        }
        h5 {
            margin-top: 100px;
            text-align: center;
            color: #000;
            font-size: 12px;
        }
        .note {
            text-align: center;
            color: #777;
            font-size: 12px;
        }

    header {
      padding: 0;
      text-align: center;
      position: relative;
      width: 100%;
    }
    .header-top {
      background-color: #transparent;
      padding: 5px;
    }
    .header-bottom {
      background-color: transparent;
      padding: 5px;
    }
    header h1 {
      margin: 0;
      font-size: 0.8em;
      color: #transparent;
    }
    .new-container {
      position: absolute;
      top: 50%;
      left: 50%;
      transform: translate(-50%, -50%);
      margin-bottom: 100px;
      border-radius: 5px;
      width: 90%;
      height: 100%;
      padding: 10px;
      box-sizing: border-box;
      background-color: #ffffff;
      color: #000;
      text-align: center;
      z-index: 2;
    }
    .new-container p {
      text-align: left;
      font-size: 1em;
      color: #555;
      margin-top: 5px;
      margin-left: 10px;
      font-weight: bold;
    }
    .container {
      border-radius: 5px;
      padding: 10px;
      margin-bottom: 20px;
      margin-top: 30px;
      width: 85%;
      height: 100%;
      background-color: #fff;
      box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
        label {
            margin-top: 5px;
            display: block;
            font-weight: bold;
            font-size: 12px;
            color: #555;
        }
        input[type="text"], input[type="password"] {
            width: 93%;
            padding: 12px;
            margin-top: 5px;
            border: 1px solid #ccc;
            border-radius: 10px;
        }
        input[type="submit"] {
            background-color: #5cb85c;
            color: white;
            border: none;
            padding: 12px;
            border-radius: 3px;
            cursor: pointer;
            width: auto;
            margin: 20px auto;
            display: block;
            font-size: 16px;
            transition: background-color 0.3s;
        }
        input[type="submit"]:hover {
            background-color: #4cae4c;
        }
        .result {
            margin-top: 15px;
            padding: 8px;
            background-color: #e7f3fe;
            border: 1px solid #b3d7ff;
            border-radius: 3px;
            color: #31708f;
            display: none;
        }
        
        /* New loading indicator */
        .loading-indicator {
            position: fixed;
            top: 30%;
            left: 50%;
            transform: translate(-50%, -50%);
            background-color: rgba(0, 0, 0, 0.8);
            color: #ffffff;
            padding: 12px;
            border-radius: 5px;
            box-shadow: 0 4px 6px rgba(0,0,0,0.5);
            z-index: 2;
            display: flex;
            align-items: center;
            justify-content: center;
        }

        .password-container {
            position: relative;
        }
        .toggle-password {
            position: absolute;
            right: 10px;
            top: 56%; 
            transform: translateY(-50%);
            cursor: pointer;
            color: silver;
        }

/* Dark Mode Styles */
@media (prefers-color-scheme: dark) {
    body {
        background-color: transparent;
        color: #fff;
    }

    h1 {
        color: #fff;
    }

    .note {
        color: #aaa;
    }

    .new-container, .new-container p {
        background-color: #2a2a2a;
        color: #e0e0e0;
    }

    .container {
        background-color: #2a2a2a;
        color: #fff;
        box-shadow: 0 4px 6px rgba(0, 0, 0, 0.3);
    }

    label {
        color: #ccc;
    }

    input[type="text"], input[type="password"] {
        background-color: #444;
        color: #fff;
        border: 1px solid #666;
    }

    input[type="submit"] {
        background-color: #388e3c;
    }

    input[type="submit"]:hover {
        background-color: #2c6e2c;
    }

    .result {
        background-color: #333;
        border: 1px solid #444;
        color: #9e9e9e;
    }

    .loading-indicator {
        background-color: rgba(0, 0, 0, 0.8);
        color: #fff;
    }

    .toggle-password {
        color: #ccc;
    }
}

    </style>
</head>
<body>
<header>
    <div class="new-container">
        <p>Wireless</p>
    </div>
    <div class="header-top">
        <h1>p</h1>
    </div>
    <div class="header-bottom">
        <h1>p</h1>
    </div>
</header>
    <div class="container">
        <h1>Konfigurasi Hotspot</h1>
        <p class="note">Isi data untuk mengatur hotspot Wi-Fi Anda</p>
        <form id="hotspotForm" action="process_hotspot.php" method="POST">
            <label for="ssid">SSID:</label>
            <input type="text" id="ssid" name="ssid" placeholder="1-15 karakter" required minlength="1" maxlength="15" pattern="^[^\s]*$" title="Tidak boleh mengandung spasi.">
            
            <label for="password">Password:</label>
            <div class="password-container">
                <input type="password" id="password" name="password" placeholder="8-15 karakter" required minlength="8" maxlength="15" pattern="^[^\s]*$" title="Tidak boleh mengandung spasi." autocomplete="off">
                <span id="togglePassword" class="toggle-password"><i class="fas fa-eye-slash"></i></span>
            </div>
            
            <input type="submit" value="Atur Hotspot">
        </form>
        <div class="result" id="resultMessage" aria-live="polite"></div>
    </div>
    <h5>note!! Jangan gunakan fitur ini jika devicemu tidak ada layar, Karena takutnya saat proses pergantian mengalami eror.</h5>
    <h6>Tidak semua device work</h6>
    <script>
        const passwordInput = document.getElementById('password');
        const togglePassword = document.getElementById('togglePassword');
        const form = document.getElementById('hotspotForm');
        const resultMessage = document.getElementById('resultMessage');

        togglePassword.addEventListener('click', function () {
            const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
            passwordInput.setAttribute('type', type);
            this.innerHTML = type === 'password' ? '<i class="fas fa-eye-slash"></i>' : '<i class="fas fa-eye"></i>';
        });

        form.addEventListener('submit', function(event) {
            event.preventDefault(); // Prevent default form submission
            
            const password = passwordInput.value.trim();
            const ssid = document.getElementById('ssid').value.trim();

            // Validate input
            if (password.includes(' ') || ssid.includes(' ')) {
                resultMessage.textContent = "SSID dan Password tidak boleh mengandung spasi.";
                resultMessage.style.display = 'block';
                return;
            }

            // Show loading indicator
            const loadingIndicator = document.createElement('div');
            loadingIndicator.classList.add('loading-indicator');
            loadingIndicator.innerHTML = '<i class="fas fa-spinner fa-spin"></i> Loading...';
            document.body.appendChild(loadingIndicator);

            // Simulate saving data (offline)
            localStorage.setItem('ssid', ssid);
            localStorage.setItem('password', password);

            // Provide feedback to the user
            resultMessage.textContent = "Hotspot berhasil diatur dengan SSID: " + ssid;
            resultMessage.style.display = 'block';

            // Optionally, you can still send the data to the server
            const formData = new FormData(form);
            fetch('exec/process_hotspot.php', {
                method: 'POST',
                body: formData
            })
            .then(response => response.text())
            .then(data => {
                document.querySelector('.loading-indicator').remove(); // Hide loader
                resultMessage.textContent += "\n" + data; // Show server response
            })
            .catch(error => {
                document.querySelector('.loading-indicator').remove(); // Hide loader
                resultMessage.textContent += "\nTerjadi kesalahan: " + error.message;
            });

            // Clear the form
            form.reset();
        });

        // Autofill form with local storage data if available
        window.onload = function() {
            const savedSsid = localStorage.getItem('ssid');
            const savedPassword = localStorage.getItem('password');
            if (savedSsid) {
                document.getElementById('ssid').value = savedSsid;
            }
            if (savedPassword) {
                passwordInput.value = savedPassword;
            }
        };
    </script>
</body>
</html>
