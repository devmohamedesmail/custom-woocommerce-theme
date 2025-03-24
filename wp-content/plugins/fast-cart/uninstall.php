<?php

// if uninstall.php is not called by WordPress, die
if (!defined('WP_UNINSTALL_PLUGIN')) {
    die;
}

$options_array = array(
	'fast_cart_option',
	'fast_cart_option_styling',
);

foreach ($options_array as $key => $option) {
	delete_option($option);
}
 