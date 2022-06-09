<?php
require_once __DIR__.'/../vendor/autoload.php';

use wapmorgan\rpm\Spec;

class SpecTest extends PHPUnit_Framework_TestCase {
    public function testSimple() {
        $spec = new Spec();
        $spec->setPackageName('simplepackage')
            ->setVersion('1.0.0')
            ->setRelease('1');
        $this->assertEquals(<<<SPEC
Name: simplepackage
Version: 1.0.0
Release: 1
Summary: ...
License: free
BuildArch: noarch

%description


%prep
%autosetup

%build


%install


%files


%changelog


SPEC
        , (string)$spec);
    }

    public function testComplex() {
        $spec = new Spec();
        $spec->setPackageName('simplepackage')
            ->setVersion('1.0.0')
            ->setRelease('1')
            ->setSummary('test')
            ->setGroup('group')
            ->setLicense('GPL')
            ->setUrl('url')
            ->setBuildRequires('buildRequires')
            ->setBuildArch('noarch')
            ->setRequires('requires')
            ->setDescription('Long..........
Very long')
            ->setPrep('%autosetup -c package')
            ->setBuild('')
            ->setInstall('rm -rf %{buildroot}
mkdir -p %{buildroot}%{_bindir}
mkdir -p %{buildroot}%{_libdir}/%{name}
cp -p binary %{buildroot}%{_bindir}/binary
cp -p rpm-source/* %{buildroot}%{_libdir}/%{name}/')
            ->setFiles('%{buildroot}%{bindir}/binary
%{buildroot}%{_libdir}/%{name}/*')
            ->setChangelog('- 1.0.0.');
        $this->assertEquals(<<<SPEC
Name: simplepackage
Version: 1.0.0
Release: 1
Summary: test
Group: group
License: GPL
URL: url
BuildRequires: buildRequires
BuildArch: noarch
Requires: requires

%description
Long..........
Very long

%prep
%autosetup -c package

%build


%install
rm -rf %{buildroot}
mkdir -p %{buildroot}%{_bindir}
mkdir -p %{buildroot}%{_libdir}/%{name}
cp -p binary %{buildroot}%{_bindir}/binary
cp -p rpm-source/* %{buildroot}%{_libdir}/%{name}/

%files
%{buildroot}%{bindir}/binary
%{buildroot}%{_libdir}/%{name}/*

%changelog
- 1.0.0.

SPEC
        , (string)$spec);
    }
}