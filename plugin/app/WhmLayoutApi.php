<?php
namespace App;

if (is_file('/usr/local/cpanel/php/WHM.php')) {
    require_once('/usr/local/cpanel/php/WHM.php');
} else {
    class WHM {
        
    }
}

class WhmLayoutApi extends \WHM
{

}
