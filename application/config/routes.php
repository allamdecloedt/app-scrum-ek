<?php
defined('BASEPATH') or exit('No direct script access allowed');

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
|	https://codeigniter.com/user_guide/general/routing.html
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
$route['default_controller'] = 'home';
$route['404_override'] = '';
$route['translate_uri_dashes'] = TRUE;

$route['api/login'] = 'api/Admin/login';
$route['api/menu'] = 'api/Admin/menu';


$route['api/edit'] = 'api/Admin/editProfile';

$route['api/editPassword'] = 'api/Admin/updatePassword';


$route['api/getDashboard'] = 'api/Admin/get_dashboard_data';


$route['api/getexpenses/(:num)'] = 'api/Admin/expense/$1';


$route['api/getinvoices/(:num)'] = 'api/Admin/invoices/$1';



$route['api/onlineadmission'] = 'api/Admin/onlineadmission';
$route['api/onlineadmissionList/(:num)'] = 'api/Admin/onlineadmissionList/$1';
$route['api/onlineadmissionDelete/(:num)'] = 'api/Admin/onlineadmissionDelete/$1';
$route['api/onlineadmissionListApproved/(:num)'] = 'api/Admin/onlineadmissionListApproved/$1';
$route['api/onlineadmissionListApprovedAndDesapproved/(:num)'] = 'api/Admin/onlineadmissionListApprovedAndDesapproved/$1';
$route['api/approveOnlineAdmissions/(:num)']['PUT'] = 'api/Admin/approveOnlineAdmissions/$1';
$route['api/deactivate/(:num)']['PUT'] = 'api/Admin/deactivate/$1';
$route['api/activate/(:num)']['PUT'] = 'api/Admin/activate/$1';
$route['api/onlineadmissionEdit/(:num)']['PUT'] = 'api/Admin/onlineadmissionEdit/$1';
$route['api/onlineadmission'] = 'api/Admin/onlineadmission';
$route['api/fetchStudentsByName/(:num)/(:any)'] = 'api/Admin/fetchStudentsByName/$1/$2';




$route['api/getinvoices/(:num)'] = 'api/Admin/invoice/$1';

$route['api/school/create']= 'api/Admin/create_school'; 
$route['api/school/update/(:num)'] = 'api/Admin/update_school/$1';
$route['api/school/delete/(:num)'] = 'api/Admin/delete_school/$1'; 
$route['api/schools'] = 'api/Admin/schools';

