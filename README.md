# Box for Magisk WebUI

Welcome to the **Box for Magisk WebUI** repository! This project provides a user-friendly web interface for BOX FOR ROOT or BOX FOR MAGISK modules, enhancing the usability and functionality of your rooted Android device.

## Overview

The **Box for Magisk WebUI** is designed to offer a comprehensive and intuitive interface for interacting with BOX FOR ROOT. This project leverages PHP and HTML to create a responsive and feature-rich web application, allowing you to manage your BOX FOR ROOT directly from your web browser without hasle.

### login detail
## user : admin
## password : 12345


## PHP Code Snippet to Generate Password Hash
save below code as pw.php

```markdown


<?php
// Generate a new hash
$password = '12345'; // Replace with your test password
$hash = password_hash($password, PASSWORD_DEFAULT);
echo "Generated hash: " . $hash;
?>
```

### Explanation:
This PHP code snippet demonstrates how to generate a password hash using PHP's `password_hash()` function. Here's a breakdown:

1. **Setting the Password:**
   ```php
   $password = '12345'; // Replace with your test password
   ```
   Replace `'12345'` with the actual password you want to hash.

2. **Generating the Hash:**
   ```php
   $hash = password_hash($password, PASSWORD_DEFAULT);
   ```
   This line generates a hash of the `$password` using the `PASSWORD_DEFAULT` algorithm, which is currently bcrypt.

3. **Displaying the Generated Hash:**
   ```php
   echo "Generated hash: " . $hash;
   ```
   Finally, the code outputs the generated hash to the screen.

4. **Access it througt browser:**
   ip_gateway/pw.php
   ```php
   echo "Generated hash: " . $hash;
   ```
   Finally, the code outputs the generated hash to the screen.
5. **put into login.php:**
   then put your hashed password into login.php enjoy.

### Notes:
- It's important to replace `'12345'` with the password you want to hash in your actual implementation.
- Always ensure to store the generated hash securely, as it is irreversible.

### KSU Supported
- **KSU module works** We supported for KSU included already don't worry

### Key Features

- **Responsive Design**: The web interface is designed to be responsive, ensuring a seamless experience across various devices and screen sizes.
- **User-Friendly Navigation**: The sidebar menu is positioned under the navbar for better visibility and usability, with easy access to all functionalities.
- **Dynamic Content Loading**: Utilize iframes to load different content sections dynamically without reloading the entire page.
- **Dark Mode Support**: Customizable dark mode with white text for better readability during nighttime usage.
- **PHP Webserver**: This module doesn't require php from termux, thanks for nosignal repository [Nosignal magisk php7 webserver](https://github.com/nosignals/magisk-php7-webserver) .

### Included Functionalities
- **Login**: Login and Logout Added for security reason.
- **Clash Submenu**: Access the "Logs" and "Command" links within the Clash submenu for managing Clash functionalities.
- **Log Viewing**: Dedicated `logs.php` file for viewing logs directly from the web interface.
- **Admin Dashboard**: An admin panel just for useful article to make your android phone powerfull.
- **Terminal Emulator**: A UI that resembles a regular terminal emulator, capable of using nano or vim for editing files directly from the web interface powered by ttyd.
- **FileManager**: Use Tinyfm to do file management like edit remove etc thourgh WEBUI.
- **Command**: use this to start stop your own BOX FOR ROOT service without using APK.
- **Monitor**: to monitoring your device, swap, cpu etc.
- **Reboot**: reboot your device from WebUI.

### Installation
- **Download**: download this repo as zip file.
- **check**: check if your download file is still folder, if yes extract first.
- **select**: select all files in webui_bfm file folder.
- **re-zip**: zip again and flash the module.
- **check again**: make sure when you download the module is not only folder name.

### ScreenShot
![image](https://github.com/user-attachments/assets/1ab47b63-bd7d-4af3-b30c-7e021e3b786d)

![image](https://github.com/user-attachments/assets/88338b68-348c-4a26-8a0a-f3999d82b314)

### Contributing

We welcome contributions from the community!  feel free to customize your own dashboard and making a pull request to make this dashboard awesome.

### License

This project is licensed under the MIT License.

---

Feel free to customize this description further to suit your specific project details and requirements.
