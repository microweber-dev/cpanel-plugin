<?php

require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config.php';

$workdir = __DIR__ . '/workdir/core/rpm/cpanel-microweber-' . time();
if(!is_dir($workdir)){
    mkdir_recursive($workdir);
}


$workdir_plugin = __DIR__ . '/workdir/core/rpm/cpanel-plugin/';
if(!is_dir($workdir_plugin)){
    mkdir_recursive($workdir_plugin);
}
$workdir_plugin = realpath($workdir_plugin);
if(!$workdir_plugin or !is_dir($workdir_plugin)){
    exit('error 18');
}
$workdir_plugin = $workdir_plugin.'/';
$cleanup = "rm -Rvf {$workdir_plugin}*";
print $cleanup."\n\n";


exec($cleanup);


$wget = "wget -q https://github.com/microweber-dev/cpanel-plugin/archive/master.zip -O {$workdir_plugin}master.zip";
print $wget."\n\n";
exec($wget);


$unzip = "unzip -qqo {$workdir_plugin}master.zip -d {$workdir_plugin} ";
print $unzip."\n\n";
exec($unzip);

$spec = new \wapmorgan\rpm\Spec();
$spec
    ->setPackageName("microweber-cms")
    ->setVersion($rpm_ver)
    ->setDescription("Create website with Microweber")
    ->setSummary('Drag and drop website builder')
    ->setRelease('1')
    ->setUrl('http://microweber.com')
    ->setPost('bash /usr/local/cpanel/microweber/install/installer.sh');

$packager = new \wapmorgan\rpm\Packager();

$packager->setOutputPath($workdir);
$packager->setSpec($spec);

//$packager->addMount(__DIR__ . '/rpm-source', '/usr/local/microweber');
$packager->addMount($workdir_plugin . '/cpanel-plugin-master', '/usr/local/cpanel/microweber');

//Creates folders using mount points
$packager->run();

//Get the rpmbuild command
shell_exec($packager->build());






//$workdir_plugin