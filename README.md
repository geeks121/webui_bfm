# BOX UI

![logo](https://github.com/user-attachments/assets/252391a0-9f95-4a8f-8e29-cb0ff071559f)


## Overview

BOX UI is a web-based interface for managing Box for Android tools. It supports both Magisk and KernelSU modules and provides a comprehensive suite of features to control and monitor your Android device access your device without touch it.
## login

- **Users**: admin
- **Password**: 12345

## Features

- **Clash Dashboard**: Comprehensive interface for managing Clash configurations.
- **System Info**: View detailed information about your device.
- **Tiny FM**: File manager for modifying files and configuration files.
- **TTyd**: Terminal manager that requires Termux and Termux:Boot installed.
- **BOX Settings**: Manage BFR settings to run core Clash, SingBox, XRay, V2Fly, and select kernel (Mihomo or Premium).
- **SMS Inbox**: Read Android SMS via the web UI.
- **Start/Stop Commands**: Execute commands to start or stop BOX settings.
- **Config Generator**: Generate Clash configurations and import Vmess, Vless, Trojan, and Shadowsocks via the UI.
- **BOX Logs**: View logs for BOX activities.
- **Documentation**: Simple and clear documentation.
- **Reboot Device**: Reboot your device or reboot to TWRP.
- **Authentication**: Login, logout, and reset password functionalities.

## Current Version

[Current Release V.1.0.3](https://github.com/geeks121/webui_bfm/releases)

## Installation

### Requirements

- Termux
- Termux:Boot
- TTyd

### Step-by-Step Guide to run TTyd

1. Install Termux and Termux:Boot from the Google Play Store or F-Droid.
2. Open Termux and run the following command to install TTyd:

    ```sh
    pkg install ttyd
    ```

3. Create the `ttyd.sh` script to start TTyd on boot:

    ```sh
    touch ~/.termux/boot/ttyd.sh
    ```

4. Add the following content copy and paste to `ttyd.sh`:

    ```sh
    #!/data/data/com.termux/files/usr/bin/sh
    termux-wake-lock
    echo "Running script at boot..."
    # Your commands go here
    ttyd -p 3001 -W -t enableTrzsz=true bash
    termux-wake-unlock
    ```

5. Make the script executable:

    ```sh
    chmod +x ~/.termux/boot/ttyd.sh
    ```

6. Follow the instructions in the BOX UI documentation to set up the webserver and other features.

## Usage

### Accessing BOX UI

Once BOX UI is set up and running, access it via the following addresses:
- [http://127.0.0.1:80](http://127.0.0.1:80)
- [http://127.0.0.1](http://127.0.0.1)

### Our main feature
### Managing BOX Settings

Access BOX settings through the web interface to configure BFR settings, select kernels, and manage Clash or SingBox settings.

### SMS Inbox

Read and manage your Android SMS directly from the web UI.

### Config Generator

Generate Clash configurations and import Vmess, Vless, Trojan, and Shadowsocks through the UI.

## Credits

- **PHP7 Webserver**: [nosignals/magisk-php7-webserver](https://github.com/nosignals/magisk-php7-webserver)
- **BOX for Magisk**: [taamarin/box_for_magisk](https://github.com/taamarin/box_for_magisk)
- **Config Generator**: [mitralola716/ocgen](https://github.com/mitralola716/ocgen)

### Installation
- **Download**: download this repo as zip file.
- **check**: check if your download file is still folder, if yes extract first.
- **select**: select all files in webui_bfm file folder.
- **re-zip**: zip again and flash the module.
- **check again**: make sure when you download the module is not only folder name.

### ScreenShot
![image](https://github.com/user-attachments/assets/342b79e5-3169-40cc-b5c6-18a791396a5a)
![image](https://github.com/user-attachments/assets/0f7abb32-8834-461d-9704-2c407b1425a4)
![image](https://github.com/user-attachments/assets/6176ae44-2f9b-4674-bda9-333b57faf50f)
![image](https://github.com/user-attachments/assets/6bdc4d0b-b7c8-45c5-8564-0840d706abea)
![image](https://github.com/user-attachments/assets/add3e96e-0d57-44b5-8762-7d4f96833abb)
![image](https://github.com/user-attachments/assets/fa8fbcea-0532-4fd0-9628-268002b4a277)
![image](https://github.com/user-attachments/assets/335ca85a-bf05-4c7d-a753-b518efd970d0)
![image](https://github.com/user-attachments/assets/35b393c5-00a7-44b3-b961-cdde41ccd121)
![image](https://github.com/user-attachments/assets/0b62b405-33e6-41db-8dba-3ceccc1cc09a)
![image](https://github.com/user-attachments/assets/cdfbaee3-6ff1-484a-b604-598c37ed1d2a)


## License

BOX UI is licensed under the latest MIT LICENSE.

## Contributors

A big thanks to all the contributors who have helped make BOX UI what it is today!
