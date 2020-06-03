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
$route['default_controller'] = 'reset_web';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
$route['api/login'] = 'Login/login';
$route['api/create-org'] = 'Reset_web/create_org';
$route['api/create-workplace'] = 'Reset_web/create_workplace';
$route['api/get-address'] = 'Reset_web/get_address';
$route['api/workdetails'] = 'Reset_web/get_workplace_details';
$route['api/excel-import'] = 'Reset_web/excel_import';
$route['api/send_email'] = 'Reset_web/send_email';
$route['api/reset-password'] = 'Login/reset_password';
$route['api/create-function'] = 'Reset_web/create_function';
$route['api/create-subfunction'] = 'Reset_web/create_subfunction';
$route['api/create-subfunction2'] = 'Reset_web/create_subfunction2';
$route['api/emp-self-decl'] = 'Reset_web/emp_self_decl';
$route['api/emp-family-decl'] = 'Reset_web/emp_family_decl';
$route['api/export-emp-self-decl'] = 'Reset_web/export_self_decl';
$route['api/export-emp-details'] = 'Reset_web/export_employee_details';
$route['api/export-emp-functions'] = 'Reset_web/export_employe_functions';
$route['api/get-dep-list'] = 'Reset_web/get_department_list';
$route['api/report'] = 'Reset_web/report';
$route['api/get-org'] = 'Reset_web/get_org';
$route['api/get-subfunctionvalue'] = 'Reset_web/get_subfunction_name';
$route['api/get-empfunctionvalue'] = 'Reset_web/get_emp_function_name';
$route['api/get-workplaceName'] = 'Reset_web/get_workplaceName';
$route['api/get_functionNamefromWorkplace'] = 'Reset_web/get_functionNamefromWorkplace';
$route['api/get_subfunctionNamefromWorkplace'] = 'Reset_web/get_subfunctionNamefromWorkplace';