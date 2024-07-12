<?php
require '../autoload.php';
$Config = new Config();

$datas = array();

if (!(exec('/data/data/com.termux/files/usr/bin/df | tail -n +2 | awk \'{print $1","$2","$3","$4","$5","$6","$7}\'', $df)))
{
    $datas[] = array(
        'total'         => '-',
        'used'          => '-',
        'free'          => '-',
        'percent_used'  => 0,
        'mount'         => '-',
        'filesystem'    => '-',
    );
}
else
{
    $mounted_points = array();
    $key = 0;

    foreach ($df as $mounted)
    {
        list($filesystem, $total, $used, $free, $percent, $mount) = explode(',', $mounted);
        if (strpos($filesystem, 'tmpfs') !== false && $Config->get('disk:show_tmpfs') === false)
            continue;
        if (strpos($filesystem, 'overlay') !== false && $Config->get('disk:show_overlay') === false)
            continue;
        if (strpos($mount, '/apex') !== false && $Config->get('disk:show_apex') === false)
            continue;
        if (!in_array($mount, $mounted_points))
        {
            $mounted_points[] = trim($mount);

            $datas[$key] = array(
                'total'         => Misc::getSize($total * 1024),
                'used'          => Misc::getSize($used * 1024),
                'free'          => Misc::getSize($free * 1024),
                'percent_used'  => trim($percent, '%'),
                'mount'         => $mount,
            );

            if ($Config->get('disk:show_filesystem'))
                $datas[$key]['filesystem'] = $filesystem;
        }

        $key++;
    }

}


echo json_encode($datas);