<?php
error_reporting(E_ERROR | E_PARSE);
date_default_timezone_set("Asia/Jakarta");

function eSMAutoload($class)
{
    include __DIR__.'/libs/Utils/'.$class.'.php';
}

spl_autoload_register('eSMAutoload');