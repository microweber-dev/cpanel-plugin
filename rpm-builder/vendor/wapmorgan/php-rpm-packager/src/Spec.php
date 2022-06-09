<?php
namespace wapmorgan\rpm;

class Spec {
    private $_keys = array(
        'Name' => '',
        'Version' => '0.1',
        'Release' => '1',
        'Summary' => '...',
        'Group' => '',
        'License' => 'free',
        'URL' => '',
        'BuildRequires' => '',
        'BuildArch' => 'noarch',
        'Requires' => '',
    );

    private $_blocks = array(
        'description' => '',
        'prep' => '%autosetup',
        'build' => '',
        'install' => '',
        'files' => '',
        'changelog' => '',
        'post' => ''
    );

    public function setPackageName($name) {
        $this->_keys['Name'] = $name;
        return $this;
    }

    public function setVersion($version) {
        $this->_keys['Version'] = $version;
        return $this;
    }

    public function setRelease($release) {
        $this->_keys['Release'] = $release;
        return $this;
    }

    public function setSummary($summary) {
        $this->_keys['Summary'] = $summary;
        return $this;
    }

    public function setGroup($group) {
        $this->_keys['Group'] = $group;
        return $this;
    }

    public function setLicense($license) {
        $this->_keys['License'] = $license;
        return $this;
    }

    public function setUrl($url) {
        $this->_keys['URL'] = $url;
        return $this;
    }

    public function setBuildRequires($buildRequires) {
        $this->_keys['BuildRequires'] = $buildRequires;
        return $this;
    }

    public function setBuildArch($buildArch) {
        $this->_keys['BuildArch'] = $buildArch;
        return $this;
    }

    public function setRequires($requires) {
        $this->_keys['Requires'] = $requires;
        return $this;
    }

    public function setDescription($description) {
        $this->_blocks['description'] = $description;
        return $this;
    }

    public function setPrep($prep) {
        $this->_blocks['prep'] = $prep;
        return $this;
    }

    public function setBuild($build) {
        $this->_blocks['build'] = $build;
        return $this;
    }

    public function setInstall($install) {
        $this->_blocks['install'] = $install;
        return $this;
    }

    public function setPost($post) {
        $this->_blocks['post'] = $post;
        return $this;
    }

    public function setFiles($files) {
        $this->_blocks['files'] = $files;
        return $this;
    }

    public function setChangelog($changelog) {
        $this->_blocks['changelog'] = $changelog;
        return $this;
    }

    public function __get($prop) {
        if (array_key_exists($prop, $this->_keys))
            return $this->_keys[$prop];
        else if (array_key_exists($prop, $this->_blocks))
            return $this->_blocks[$prop];
    }

    public function setKey($prop, $value) {
        $this->_keys[$prop] = $value;
        return $this;
    }

    public function __toString() {
        $spec = '';
        foreach ($this->_keys as $key => $value) {
            if ($value === '')
                continue;
            $spec .= sprintf('%s: %s'."\n", $key, $value);
        }

        foreach ($this->_blocks as $block => $value) {
            $spec .= "\n".'%'.$block."\n".$value."\n";
        }
        return $spec;
    }
}