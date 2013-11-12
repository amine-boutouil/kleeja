<?php
	
include '../includes/classes/hooks.php';


#plugin file
$hooks->add_filter('before_name', 'plugin_function_test1');
function plugin_function_test1($name)
{
	return $name .' khaked';
}
$hooks->add_action('after_filter', 'plugin_function_test2');
function plugin_function_test2()
{
	print 'g';
}



#kleeja files
echo 'hi';
$name = 'majed';
$name = $hooks->apply_filters('before_name', $name);
$hooks->do_action('after_filter');
echo ' ' . $name;

