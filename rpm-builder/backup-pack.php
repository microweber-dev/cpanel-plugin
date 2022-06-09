<?php
require_once __DIR__ . '/vendor/autoload.php';

$workdir = __DIR__ . '/../../workdir/core/rpm/cpanel-microweber-' . time();
if(!is_dir($workdir)){
    mkdir($workdir);
}

$spec = new \wapmorgan\rpm\Spec();
$spec
    ->setPackageName("cpanel-microweber")
    ->setVersion("0.0.1")
    ->setDescription("Create website with Microweber")
    ->setSummary('Drag and drop website builder')
    ->setRelease('1')
    ->setUrl('http://microweber.com')
    ->setPost('bash /usr/local/microweber/installer.sh');

$packager = new \wapmorgan\rpm\Packager();

$packager->setOutputPath($workdir);
$packager->setSpec($spec);

$packager->addMount(__DIR__ . '/rpm-source', '/usr/local/microweber');
$packager->addMount(__DIR__ . '/rpm-cpanel', '/usr/local/cpanel/bin');

//Creates folders using mount points
$packager->run();

//Get the rpmbuild command
exec($packager->build());