<?php


$rpm_ver = "0.0.1";
$rpm_package_name = "microweber-cms";

$workdir = __DIR__ . '/../../workdir/core/rpm/cpanel-microweber';
$yum_repo = __DIR__ . '/../../public_html/ready/rpm/cpanel';



if(!is_dir($yum_repo)){
    mkdir_recursive($yum_repo);
}
