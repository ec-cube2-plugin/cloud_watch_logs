<?php

$file = DATA_REALDIR . 'class/util/GC_Utils.php';
if (file_exists($file)) {
    $source = file_get_contents($file);
    $source = str_replace(['<?php', '?>'], '', $source);
    $source = str_replace('class GC_Utils', 'class GC_Utils_Base', $source);

    eval($source);
}

$file = CLASS_EX_REALDIR . 'util_extends/GC_Utils_Ex.php';
if (file_exists($file)) {
    $source = file_get_contents($file);
    $source = str_replace(['<?php', '?>'], '', $source);
    $source = str_replace('require_once CLASS_REALDIR . \'util/GC_Utils.php\';', '', $source);

    eval($source);
}
