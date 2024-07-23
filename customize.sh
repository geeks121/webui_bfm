SKIPUNZIP=1

system_gid="1000"
system_uid="1000"
php_data_dir="/data/adb/php7"
php_bin_dir="${php_data_dir}/files/bin"

ui_print "********************************"
ui_print "*        PHP7 Webserver        *"
ui_print "*         by: nosignal         *"
ui_print "********************************"
ui_print ""

ui_print "Installing PHP7 Webserver..."
ui_print "[0] Creating directory..."
mkdir -p ${MODPATH}/system/bin
mkdir -p ${php_data_dir}


unzip -o "${ZIPFILE}" -x 'META-INF/*' -d $MODPATH >&2

ui_print "[20] Copying files..."

mv ${MODPATH}/files ${php_data_dir}
mv ${MODPATH}/scripts ${php_data_dir}

ui_print "[90] Setting Files Permission..."

set_perm_recursive ${MODPATH} 0 0 0755 0644
set_perm_recursive ${php_data_dir} 0 0 0755 0644
set_perm_recursive ${php_data_dir}/scripts 0 0 0755 0755
set_perm_recursive ${php_data_dir}/files/config 0 0 0755 0644
set_perm_recursive ${php_data_dir}/files/www ${system_uid} ${system_gid} 0755 0644
set_perm_recursive ${php_bin_dir} ${system_uid} ${system_gid} 0755 0755

set_perm  ${php_data_dir}/scripts/php_run  0  0  0755
set_perm  ${php_data_dir}/scripts/ttyd_run  0  0  0755
set_perm  ${php_data_dir}/scripts/sfa  0  0  0755
set_perm  ${php_data_dir}/scripts/php_inotifyd  0  0  0755
set_perm  ${php_data_dir}/files/bin/php  0  0  0755
set_perm  ${php_data_dir}/files/bin/ttyd  0  0  0755
set_perm  ${php_data_dir}/files/config/php.config ${system_uid} ${system_gid} 0755
set_perm  ${php_data_dir}/files/config/php.ini ${system_uid} ${system_gid} 0755
# chmod +x ${php_data_dir}/files

ui_print "[100] Done..."
ui_print " "
ui_print "-     Config Files      : ${php_data_dir}/files/config"
ui_print "-     Default htdocs    : ${php_data_dir}/files/www"
ui_print "-     Web Services      : http://127.0.0.1:80"
ui_print "-     TTYD Services     : http://127.0.0.1:3001"