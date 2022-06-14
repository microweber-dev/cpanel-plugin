<?php
namespace App;

if (is_file('/usr/local/cpanel/php/WHM.php')) {
    require_once('/usr/local/cpanel/php/WHM.php');


    class WhmLayoutApi extends \WHM
    {

    }

} else {
    class WhmLayoutApi {

        public static  function header($params1,$params2,$params3) {

        }

        public static  function footer() {

        }
    }
}
