<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/*
| -------------------------------------------------------------------------
| URI ROUTING
| -------------------------------------------------------------------------
| This file lets you re-map URI requests to specific controller functions.
|
| Typically there is a one-to-one relationship between a URL string
| and its corresponding controller class/method. The segments in a
| URL normally follow this pattern:
|
|	example.com/class/method/id/
|
| In some instances, however, you may want to remap this relationship
| so that a different class/function is called than the one
| corresponding to the URL.
|
| Please see the user guide for complete details:
|
|	https://codeigniter.com/userguide3/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There are three reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router which controller/method to use if those
| provided in the URL cannot be matched to a valid route.
|
|	$route['translate_uri_dashes'] = FALSE;
|
| This is not exactly a route, but allows you to automatically route
| controller and method names that contain dashes. '-' isn't a valid
| class or method name character, so it requires translation.
| When you set this option to TRUE, it will replace ALL dashes in the
| controller and method URI segments.
|
| Examples:	my-controller/index	-> my_controller/index
|		my-controller/my-method	-> my_controller/my_method
*/
$route['default_controller'] = 'audiometri';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;

/*
| -------------------------------------------------------------------------
| AUDIOMETRI ROUTES
| -------------------------------------------------------------------------
*/

// Canonical routes relative to APP_BASE_URL (which already includes /audiometri/).
$route['dashboard'] = 'audiometri/index';
$route['doctor_view'] = 'audiometri/doctor_view';
$route['review_dokter/(:num)'] = 'audiometri/review_dokter/$1';
$route['create'] = 'audiometri/create';
$route['edit/(:num)'] = 'audiometri/edit/$1';
$route['view/(:num)'] = 'audiometri/view/$1';
$route['delete/(:num)'] = 'audiometri/delete/$1';
$route['list'] = 'audiometri/list_tests';
$route['list_tests'] = 'audiometri/list_tests';
$route['search'] = 'audiometri/search_by_no_rm';
$route['search_by_no_rm'] = 'audiometri/search_by_no_rm';
$route['export_pdf/(:num)'] = 'audiometri/export_pdf/$1';
$route['export_excel'] = 'audiometri/export_excel';
$route['laporan'] = 'audiometri/laporan';
$route['update_chart'] = 'audiometri/update_chart';

// Legacy routes remain compatible with existing bookmarks and POST requests.
// Main routes
$route['audiometri'] = 'audiometri/index';
$route['audiometri/dashboard'] = 'audiometri/index';

// Doctor view and review routes
$route['audiometri/doctor_view'] = 'audiometri/doctor_view';
$route['audiometri/review_dokter/(:num)'] = 'audiometri/review_dokter/$1';

// CRUD routes
$route['audiometri/create'] = 'audiometri/create';
$route['audiometri/edit/(:num)'] = 'audiometri/edit/$1';
$route['audiometri/view/(:num)'] = 'audiometri/view/$1';
$route['audiometri/delete/(:num)'] = 'audiometri/delete/$1';

// List and search routes
$route['audiometri/list'] = 'audiometri/list_tests';
$route['audiometri/list_tests'] = 'audiometri/list_tests';
$route['audiometri/search'] = 'audiometri/search_by_no_rm';

// Export routes
$route['audiometri/export_pdf/(:num)'] = 'audiometri/export_pdf/$1';
$route['audiometri/export_excel'] = 'audiometri/export_excel';
$route['audiometri/laporan'] = 'audiometri/laporan';

// AJAX routes
$route['audiometri/update_chart'] = 'audiometri/update_chart';
$route['audiometri/search_by_no_rm'] = 'audiometri/search_by_no_rm';
