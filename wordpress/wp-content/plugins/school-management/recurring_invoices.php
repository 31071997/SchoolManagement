<?php
//--------- UPDATE PLUGIN PATH -------//
$root = dirname(dirname(dirname(dirname(__FILE__))));
if (file_exists($root.'/wp-load.php')) 
{
require_once($root.'/wp-load.php');
} 
else 
{
    require_once($root.'/wp-config.php');
}
mj_smgt_generate_recurring_invoice();
?>