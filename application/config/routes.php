<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Clean URL to Gradebook
$route['gradebook'] = 'gradebook/view';
$route['gradebook/(:any)'] = 'gradebook/$1';

// Clean URL to dashboard view
$route['dashboard'] = 'dashboard/view';

// Clean URL to Calendar View
$route['calendar'] = 'calendar/view';
$oute['calendar/(:any)'] = 'calendar/$1';

// User System URLS
$route['default_controller'] = 'users';
$route['(:any)'] = 'users/$1';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

?>
