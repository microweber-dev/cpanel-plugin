<?php
namespace App;

if (is_file('/usr/local/cpanel/php/cpanel.php')) {

    require_once('/usr/local/cpanel/php/cpanel.php');

    class CpanelLayoutApi
    {
        public static  function header() {

        }

        public static  function footer() {

        }
    }

} else {
    class CpanelLayoutApi {

        public static  function header() {

        }

        public static  function footer() {

        }
    }
}
