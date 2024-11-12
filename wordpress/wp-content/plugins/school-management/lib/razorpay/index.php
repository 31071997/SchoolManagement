<?php
include_once( ABSPATH . 'wp-admin/includes/plugin.php' );

$razorpay_key =get_option('razorpay__key');
return $razorpay_key;