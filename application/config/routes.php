<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Clean URL to dashboard view
$route['dashboard'] = 'dashboard/view';
$route['test'] = 'dashboard/test';

// SimplePages Handled views
$route['calendar'] = 'simplepages/view/calendar';

// User System URLS
$route['default_controller'] = 'users/login';
$route['(:any)'] = 'users/login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

?>
