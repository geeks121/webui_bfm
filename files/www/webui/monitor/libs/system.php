<?php
require '../autoload.php';

// device
$device = shell_exec('getprop ro.product.manufacturer').' '.shell_exec('getprop ro.product.model').' ('.shell_exec('getprop ro.product.device').')';

// OS
if (!($os = shell_exec('/data/data/com.termux/files/usr/bin/lsb_release -ds | cut -d= -f2 | tr -d \'"\'')))
{
    if(!($os = shell_exec('cat /etc/system-release | cut -d= -f2 | tr -d \'"\''))) 
    {
        if (!($os = shell_exec('find /etc/*-release -type f -exec cat {} \; | grep PRETTY_NAME | tail -n 1 | cut -d= -f2 | tr -d \'"\'')))
        {
             if (file_exists('/system/build.prop'))
                {
                    $os = 'Android '.shell_exec('getprop ro.build.version.release').' ('.php_uname('m').')';
                }else{
                    $os = '-';
                }
        }
    }
}
$os = trim($os, '"');
$os = str_replace("\n", '', $os);

// Kernel
if (!($kernel = shell_exec('/data/data/com.termux/files/usr/bin/uname -r')))
{
    $kernel = '-';
}

// Uptime
if (!($totalSeconds = shell_exec('/data/data/com.termux/files/usr/bin/cut -d. -f1 /proc/uptime')))
{
    $uptime = '-';
}
else
{
    $uptime = Misc::getHumanTime($totalSeconds);
}

// Last boot
if (!($upt_tmp = shell_exec('cat /proc/uptime')))
{
    $last_boot = '-';
}
else
{
    $upt = explode(' ', $upt_tmp);
    $last_boot = date('d-m-Y H:i:s T', time() - intval($upt[0]));
}

// Server datetime
if (!($server_date = shell_exec('/data/data/com.termux/files/usr/bin/date.bak')))
{
    $server_date = date('d-m-Y H:i:s T');
}


$datas = array(
    'device'      => $device,
    'os'            => $os,
    'kernel'        => $kernel,
    'uptime'        => $uptime,
    'last_boot'     => $last_boot,
    'server_date'   => $server_date,
);

echo json_encode($datas);