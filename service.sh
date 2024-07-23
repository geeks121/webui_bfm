#!/bin/bash

# Wait for the boot animation to finish
until [ "$(getprop init.svc.bootanim)" = "stopped" ]; do
    sleep 5
done

service_path=`realpath $0`
module_dir=`dirname ${service_path}`
scripts_dir="${module_dir}/scripts"
php_data_dir="/data/adb/php7"

php_scripts_dir="${php_data_dir}/scripts"
php_files_dir="${php_data_dir}/files"

php_tmp_path="${php_files_dir}/tmp"

# Function to check if a command exists
command_exists() {
    command -v "$1" >/dev/null 2>&1
}

# Check for Magisk or KernelSU busybox
if command_exists /data/adb/magisk/busybox; then
    busybox_path=/data/adb/magisk/busybox
    echo "Using Magisk busybox at $busybox_path"
elif command_exists /data/adb/ksu/bin/busybox; then
    busybox_path=/data/adb/ksu/bin/busybox
    echo "Using KernelSU busybox at $busybox_path"
else
    echo "No suitable busybox found. Exiting."
    exit 1
fi

if [ -f ${php_pid_file} ]; then
    rm -rf ${php_pid_file}
fi

if [ -f ${ttyd_pid_file} ]; then
    rm -rf ${ttyd_pid_file}
fi
# Use busybox for crond command if necessary
# Uncomment the following line if you need to use crond
# nohup ${busybox_path} crond -c ${php_tmp_path} > /dev/null 2>&1 &

${php_scripts_dir}/php_run -s
${php_scripts_dir}/ttyd_run -s
inotifyd ${php_scripts_dir}/php_inotifyd ${module_dir} >> /dev/null &
