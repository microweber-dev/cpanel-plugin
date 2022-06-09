<?php

require_once __DIR__ . '/config.php';


if (!is_dir($yum_repo)) {
    mkdir_recursive($yum_repo);
}

$src = $_SERVER['HOME'] . '/rpmbuild' . "/*";
$dest = $yum_repo;
shell_exec("cp -r $src $dest");


shell_exec("createrepo --update $dest");
shell_exec("cd $dest && gpg --detach-sign --armor repodata/repomd.xml");


$ReDirectory = new \RecursiveDirectoryIterator($dest);
$ReIterator = new \RecursiveIteratorIterator($ReDirectory);
$files = array();


while ($ReIterator->valid()) {

    if ($ReIterator->getFilename() and !$ReIterator->isDot()) {

        $fn = $ReIterator->getFilename();
        $path = $ReIterator->getPathname();


        if (strstr($fn, $rpm_package_name . '-' . $rpm_ver)) {

            $files[] = realpath($path);
        }

        //  echo '<a href="http://www.example.com/' . $ReIterator->getFilename() . '">' . $search . '</a>';

    }

    $ReIterator->next();
}


$newstamp = 0;
$newname = '';
if ($files) {
    foreach ($files as $filename) {
        if (($timedat = filemtime($filename) > $newstamp)) {
            $newname = $filename;
            $newstamp = $timedat;
        }
    }
}

if ($newname and is_file($newname)) {
    copy($newname, $yum_repo . '/microweber-cpanel.rpm');
}

var_dump($newname);

//createrepo --update /srv/my/repo




