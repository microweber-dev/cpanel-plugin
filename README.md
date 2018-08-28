# cPanel Microweber Plugin

## Installation

### Using RPM

Run the following commands:

```
wget http://download.microweberapi.com/cpanel/microweber-cpanel.rpm
sudo yum install microweber-cpanel.rpm
```
 
### Manual installation

* Get the plugin source code from [microweber-dev/cpanel-plugin](https://github.com/microweber-dev/cpanel-plugin).
* Place the files in `/usr/local/cpanel/microweber`.
* Run the following script:

```
/usr/local/cpanel/microweber/install/installer.sh
```

## Find The Plugin

* Login to WHM, search for "Microweber" and open the plugin settings page.
* Add the "Microweber" feature to plans you wish to have Microweber installed with them.
* Login to cPanel and open the plugin under "Software". From that page Microweber can be manually installed to any of the user's domains.


### Update 

```
wget http://download.microweberapi.com/cpanel/microweber-cpanel.rpm
sudo rpm -Uvh microweber-cpanel.rpm
```

### Uninstall
 
* Run the following script:

```
/usr/local/cpanel/microweber/install/uninstall.sh
```