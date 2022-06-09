<?php
require_once __DIR__.'/../vendor/autoload.php';

use wapmorgan\rpm\Packager;
use wapmorgan\rpm\Spec;

class PackagerTest extends PHPUnit_Framework_TestCase {
    public function setUp() {
        if (is_dir(__DIR__.'/package'))
            $this->removeDir(__DIR__.'/package');
        mkdir(__DIR__.'/package');
        if (is_dir(__DIR__.'/output'))
            $this->removeDir(__DIR__.'/output');
        mkdir(__DIR__.'/output');
    }

    public function tearDown() {
        $this->removeDir(__DIR__.'/package');
        $this->removeDir(__DIR__.'/output');
    }

    public function testComplex() {
        exec('command -v rpm', $output, $result);
        if (empty($output)) {
            $this->markTestSkipped('This test can not be performed on a system without rpm');
        }

        mkdir(__DIR__.'/package/test', 0755, true);
        touch(__DIR__.'/package/test/binary');
        chmod(__DIR__.'/package/test/binary', 0755);
        mkdir(__DIR__.'/package/test2');
        touch(__DIR__.'/package/test2/abc');

        $spec = new Spec();
        $spec->setPackageName('test-c')->setRelease(2);
        $packager = new Packager();
        $packager->setSpec($spec);

        $packager->setOutputPath(__DIR__.'/output');
        $packager->addMount(__DIR__.'/package/test/binary', '/usr/bin/binary');
        $packager->addMount(__DIR__.'/package/test2/', '/usr/lib/test/');
        $packager->run();

        $this->assertEquals('%autosetup -c package', $spec->prep);
        $this->assertEquals('rm -rf %{buildroot}'."\n".'mkdir -p %{buildroot}'."\n".'cp -rp * %{buildroot}', $spec->install);
        $this->assertEquals('%attr(0755,root,root) /usr/bin/binary'."\n".'/usr/lib/test/', $spec->files);
        $this->assertTrue(file_exists($_SERVER['HOME'].'/rpmbuild/SPECS/test-c.spec'));
        $this->assertTrue(file_exists($_SERVER['HOME'].'/rpmbuild/SOURCES/test-c.tar'));

        $phar = new PharData($_SERVER['HOME'].'/rpmbuild/SOURCES/test-c.tar');
        $this->assertTrue(isset($phar['usr/bin/binary']));
        $this->assertTrue(isset($phar['usr/lib/test/abc']));

        $command = $packager->build();
        $this->assertEquals('rpmbuild -bb '.$_SERVER['HOME'].'/rpmbuild/SPECS/test-c.spec', $command);
        exec($command, $_, $result);
        $this->assertEquals(0, $result);
        $this->assertTrue(file_exists($_SERVER['HOME'].'/rpmbuild/RPMS/noarch/test-c-0.1-2.noarch.rpm'));
        $this->assertTrue($packager->movePackage(__DIR__.'/test-c-0.1.rpm'));
        $this->assertTrue(file_exists(__DIR__.'/test-c-0.1.rpm'));
        $expected_files = array('/usr/bin/binary', '/usr/lib/test', '/usr/lib/test/abc');
        exec('rpm -qlp '.__DIR__.'/test-c-0.1.rpm', $actual_files, $_);
        $this->assertEquals($expected_files, $actual_files);

        unlink(__DIR__.'/test-c-0.1.rpm');
        unlink($_SERVER['HOME'].'/rpmbuild/SPECS/test-c.spec');
        unlink($_SERVER['HOME'].'/rpmbuild/SOURCES/test-c.tar');
    }

    public function testSimple() {
        exec('command -v rpm', $output, $result);
        if (empty($output)) {
            $this->markTestSkipped('This test can not be performed on a system without rpm');
        }

        mkdir(__DIR__.'/package/test', 0755, true);
        touch(__DIR__.'/package/test/binary');
        chmod(__DIR__.'/package/test/binary', 0755);
        mkdir(__DIR__.'/package/test2');
        touch(__DIR__.'/package/test2/abc');

        $spec = new Spec();
        $spec->setPackageName('test-s');
        $packager = new Packager();
        $packager->setSpec($spec);

        $packager->setOutputPath(__DIR__.'/output');
        $packager->addMount(__DIR__.'/package/', '/usr/share/test/');
        $packager->run();

        $this->assertEquals('%autosetup -c package', $spec->prep);
        $this->assertEquals('rm -rf %{buildroot}'."\n".'mkdir -p %{buildroot}'."\n".'cp -rp * %{buildroot}', $spec->install);
        $this->assertEquals('/usr/share/test/', $spec->files);
        $this->assertTrue(file_exists($_SERVER['HOME'].'/rpmbuild/SPECS/test-s.spec'));
        $this->assertTrue(file_exists($_SERVER['HOME'].'/rpmbuild/SOURCES/test-s.tar'));

        $phar = new PharData($_SERVER['HOME'].'/rpmbuild/SOURCES/test-s.tar');
        $this->assertTrue(isset($phar['usr/share/test/test/binary']));
        $this->assertTrue(isset($phar['usr/share/test/test2/abc']));

        $command = $packager->build();
        $this->assertEquals('rpmbuild -bb '.$_SERVER['HOME'].'/rpmbuild/SPECS/test-s.spec', $command);
        exec($command, $_, $result);
        $this->assertEquals(0, $result);
        $this->assertTrue(file_exists($_SERVER['HOME'].'/rpmbuild/RPMS/noarch/test-s-0.1-1.noarch.rpm'));
        $this->assertTrue($packager->movePackage(__DIR__.'/test-s-0.1.rpm'));
        $this->assertTrue(file_exists(__DIR__.'/test-s-0.1.rpm'));
        $expected_files = array('/usr/share/test', '/usr/share/test/test', '/usr/share/test/test/binary', '/usr/share/test/test2', '/usr/share/test/test2/abc');
        exec('rpm -qlp '.__DIR__.'/test-s-0.1.rpm', $actual_files, $_);
        $this->assertEquals($expected_files, $actual_files);

        unlink(__DIR__.'/test-s-0.1.rpm');
        unlink($_SERVER['HOME'].'/rpmbuild/SPECS/test-s.spec');
        unlink($_SERVER['HOME'].'/rpmbuild/SOURCES/test-s.tar');
    }

    private function removeDir($dir) {
        $dd = opendir($dir);
        while (($file = readdir($dd)) !== false) {
            if (in_array($file, array('.', '..'))) continue;
            if (is_dir($dir.'/'.$file))
                $this->removeDir($dir.'/'.$file);
            else
                unlink($dir.'/'.$file);
        }
        closedir($dd);
        rmdir($dir);
    }
}