<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
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
|	http://codeigniter.com/user_guide/general/routing.html
|
| -------------------------------------------------------------------------
| RESERVED ROUTES
| -------------------------------------------------------------------------
|
| There area two reserved routes:
|
|	$route['default_controller'] = 'welcome';
|
| This route indicates which controller class should be loaded if the
| URI contains no data. In the above example, the "welcome" class
| would be loaded.
|
|	$route['404_override'] = 'errors/page_missing';
|
| This route will tell the Router what URI segments to use if those provided
| in the URL cannot be matched to a valid route.
|
*/

/* 
 * Camper Routes
 *
 * This is where all of the different scripts and sections of the site get 
 * routed and identified. Modified from the original CI routes.php
 *
 * Version 1.5 (2014 02 25 1957)
 * Edited by Sean Wittmeyer (sean@zilifone.net)
 * 
 */

// Tests
$route['test'] = 'tests';

// Active Account Pages
$route['n/(:any)'] = 'account/n';
$route['n'] = 'account/n';

// At Camp / Staff Pages
$route['atcamp/(:num)/(:num)/(:any)/(:any)'] = 'staff/$3/$1/$2/$4';
$route['atcamp/(:num)/(:num)/(:any)'] = 'staff/$3/$1/$2';
$route['atcamp/(:num)/(:num)'] = 'staff/start/$1/$2';
$route['atcamp/(:num)'] = 'staff/choose_event/$1';
$route['atcamp'] = 'staff/choose_event';

// Public Pages
$route['public/events'] = 'events/public_view';
$route['email/(:any)'] = 'api/viewemail/$1';

// Authentication
$route['signout'] = 'auth/logout';
$route['signin'] = 'auth/login';
$route['auth/(:any)'] = 'auth/$1';
$route['auth'] = 'auth';

// Register & Signup Flow
$route['register/(:num)/(:num)/(:any)'] = 'register/register/$1/$2/$3'; /* new registration process, steps, get register details then signin/up */ 
$route['register/(:num)/(:num)'] = 'register/register/$1/$2'; 
$route['register/(:num)'] = 'register/register/$1'; 
$route['register'] = 'register/register'; 

// Leader Single Event Register Section
$route['registrations/set/(:num)/(:num)/(:any)'] = 'register/new_reg/$1/$2/$3'; /* set is for setting the reg cookie */  /* phase out soon */
$route['registrations/set/(:num)/(:num)'] = 'register/new_reg/$1/$2';  /* phase out soon */
$route['registrations/set/(:num)'] = 'register/new_reg/$1';  /* phase out soon */
$route['registrations/new/(:num)/(:num)/(:any)'] = 'register/new_reg/$1/$2/$3'; /* new is for creating a reg */
$route['registrations/new/(:num)/(:num)'] = 'register/new_reg/$1/$2';
$route['registrations/new/(:num)'] = 'register/new_reg/$1'; 
$route['registrations/(:num)/roster/create'] = 'register/create_roster/$1';
$route['registrations/(:num)/roster/(:num)'] = 'register/single_roster/$1/$2';
$route['registrations/(:num)/roster'] = 'register/roster/$1';
$route['registrations/(:num)/details'] = 'register/details/$1';
$route['registrations/(:num)'] = 'register/details/$1';
$route['registrations/details'] = 'register/details'; /* phase out soon */
$route['registrations/roster'] = 'register/roster'; /* unused, phase out soon */
$route['registrations/schedule'] = 'register/schedule'; /* unused, phase out soon */
$route['registrations/payments'] = 'register/payments'; /* unused, phase out soon */
$route['registrations/past'] = 'register/past';
$route['registrations'] = 'register';

// Active Account Pages
$route['me/edit'] = 'account/edit';
$route['me'] = 'account';

// Signup Pages
$route['start/invite/(:any)'] = 'account/createaccounts/$1';
$route['start'] = 'account/createaccounts';

// My Unit Section & Base Roster Details
$route['unit/edit'] = 'myunit'; /* phase out soon */
$route['unit/members/new'] = 'myunit/new_member';
$route['unit/members/(:num)/delete'] = 'myunit/delete_member/$1';
$route['unit/members/(:num)'] = 'myunit/members/$1';
$route['unit/members'] = 'myunit/members';
$route['unit/change_contact'] = 'myunit/change_contact';
$route['unit'] = 'myunit';

// Units and Users Section
$route['units/change_contact'] = 'users/changecontact';
$route['units/edit/(:num)'] = 'users/editunit/$1'; /* phase out soon */
$route['units/new'] = 'users/newunit';
$route['units/(:num)/members/new'] = 'users/new_member/$1';
$route['units/(:num)/members/(:num)/delete'] = 'users/delete_member/$1/$2';
$route['units/(:num)/members/(:num)'] = 'users/members/$1/$2';
$route['units/(:num)/members'] = 'users/members/$1';
$route['units/(:num)/registrations'] = 'users/registrations/$1';
$route['units/(:num)/payments'] = 'users/payments/$1';
$route['units/(:num)/edit'] = 'users/editunit/$1';
$route['units/(:num)'] = 'users/editunit/$1';
$route['units'] = 'users/listunits';
$route['users/new'] = 'users/newuser';
$route['users/pending'] = 'users/pending_invites';
$route['users/(:num)/individual'] = 'users/individual/$1'; 
$route['users/deactivate/(:num)'] = 'users/deactivate/$1'; /* phase out soon */
$route['users/(:num)/deactivate'] = 'users/deactivate/$1';
$route['users/activate/(:num)'] = 'users/activate/$1'; /* phase out soon */
$route['users/(:num)/activate'] = 'users/activate/$1';
$route['users/edit/(:num)'] = 'users/edituser/$1'; /* phase out soon */
$route['users/(:num)/edit'] = 'users/edituser/$1';
$route['users/(:num)'] = 'users/edituser/$1'; 
$route['users'] = 'users/listusers';

// Leader Events Section
$route['events/past'] = 'events/past';
$route['events/all'] = 'events/all';
$route['events'] = 'events/all';


// Admin Event Section
$route['event/(:num)/message'] = 'event/message/$1';
$route['event/(:num)/details'] = 'event/details/$1';
$route['event/(:num)/sessions'] = 'event/sessions/$1';
$route['event/(:num)/options'] = 'event/eventoptions/$1';
$route['event/(:num)/custom'] = 'event/customoptions/$1';
$route['event/(:num)/classes/(:num)'] = 'event/class_rosters/$1/$2';
$route['event/(:num)/classes'] = 'event/classes/$1';
$route['event/(:num)/registrations/(:num)/roster/(:num)'] = 'event/single_roster/$1/$2/$3';
$route['event/(:num)/registrations/(:num)/roster'] = 'event/editregistration/$1/$2';
$route['event/(:num)/registrations/(:num)/edit'] = 'event/editregistration/$1/$2';
$route['event/(:num)/registrations/(:num)'] = 'event/editregistration/$1/$2';
$route['event/(:num)/registrations'] = 'event/registrations/$1';
$route['event/(:num)/edit'] = 'event/edit/$1';
$route['event/(:num)'] = 'event/details/$1';
$route['event/activities/new'] = 'event/new_activity';
$route['event/activities/(:num)/delete'] = 'event/delete_activity/$1';
$route['event/activities/(:num)'] = 'event/activities/$1';
$route['event/activities'] = 'event/activities';
$route['event/new'] = 'event/newevent';
$route['event/past'] = 'event/past';
$route['event'] = 'event/index';

// API Payments v1
$route['api/v1/pay/(:any)'] = 'api_payments/$1';

// API v2
$route['api/v2/build/pdf/(:any)'] = 'lightweight/build_pdf/$1';
$route['api/v2/build/html/(:any)'] = 'lightweight/build_html/$1';
$route['api/v2/update/(:any)'] = 'api/update/$1';
$route['api/v2/new/(:any)'] = 'api/new/$1'; /* action, send the rest via post */
$route['api/v2/delete/(:any)/(:any)'] = 'api/delete/$1/$2'; /* action and id */
$route['api/v2/reports/run/(:num).csv'] = 'api/view_report/csv/$1'; /* re-runs a fresh version of the report */
$route['api/v2/reports/(:num).csv'] = 'api/view_report/csv/$1'; /* fetches the output of the last run report from the database */

// API v1
$route['api/v1/payments.json'] = 'api/get_payments'; /* unused */
$route['api/v1/list/(:any).json'] = 'api/showlist/$1';
$route['api/v1/data/(:any).json'] = 'api/search/$1';
$route['api/v1/views/events/(:any).html'] = 'api/eventsview/$1';
$route['api/v1/views/event/(:num).html'] = 'api/eventview/$1';
$route['api/v1/notifications/markread'] = 'api/read_notifications';
$route['api/v1/registration/activate'] = 'api/activate_reg';
$route['api/v1/registration/deactivate'] = 'api/deactivate_reg';
$route['api/v1/registration/delete'] = 'api/delete_reg';
$route['api/v1/registration/new'] = 'api/register';
$route['api/v1/register/unit/(:num)/(:num)/(:any)'] = 'api/register_for_session/unit/$1/$2/$3';
$route['api/v1/register/unit/(:num)/(:num)'] = 'api/register_for_session/unit/$1/$2';
$route['api/v1/register/individual/(:num)/(:num)/(:any)'] = 'api/register_for_session/individual/$1/$2/$3';
$route['api/v1/register/individual/(:num)/(:num)'] = 'api/register_for_session/individual/$1/$2';
$route['api/v1/unitroster/(:num).pdf'] = 'api/build_unit_roster/$1';
$route['api/v1/bluecard/(:any)/(:num)/(:num).pdf'] = 'api/build_blue_cards/$1/$2/$3';
$route['api/v1/bluecard/(:any)/(:num).pdf'] = 'api/build_blue_cards/$1/$2';
$route['api/v1/rosters/schedule/(:num).pdf'] = 'api/build_single_roster/$1/pdf';
$route['api/v1/rosters/invoice/(:num).pdf'] = 'api/build_single_roster/$1/pdf/invoice';
$route['api/v1/reports/birthdays/(:num).pdf'] = 'api/build_birthdays_report/$1';
$route['api/v1/reports/preorders/(:num).pdf'] = 'api/build_preorders_report/$1';
$route['api/v1/reports/preorders/all/(:num).pdf'] = 'api/build_preorders_report/$1/true';
$route['api/v1/reports/conditions/(:num).pdf'] = 'api/build_conditions_report/$1';
$route['api/v1/reports/option/(:num)/(:num).pdf'] = 'api/build_options_report/$1/$2';
$route['api/v1/reports/checkin/session/(:num).pdf'] = 'api/build_checkin_form/$1/session';
$route['api/v1/reports/checkin/reg/(:num).pdf'] = 'api/build_checkin_form/$1/reg';
$route['api/v1/reports/classregs/reg/(:num).csv'] = 'api/export_classregs/$1/reg';
$route['api/v1/classroster/session/(:num).pdf'] = 'api/build_classroster/$1';
$route['api/v1/roster/delete'] = 'api/delete_roster';
$route['api/v1/classregs/update'] = 'api/update_class_regs';
$route['api/v1/classes/create'] = 'api/create_class';
$route['api/v1/classes/delete'] = 'api/delete_class';
$route['api/v1/classes/edit'] = 'api/edit_class';
$route['api/v1/classes/(:num).json'] = 'api/single_class/$1';
$route['api/v1/roster/discounts/update'] = 'api/update_single_discount';
$route['api/v1/roster/discounts/(:num).json'] = 'api/single_discount/$1';
$route['api/v1/invites/resend'] = 'api/resend_invite';
$route['api/v1/invites/delete'] = 'api/delete_invite';
$route['api/v1/event/close'] = 'api/close_event';
$route['api/v1/event/open'] = 'api/open_event';
$route['api/v1/(:any).json'] = 'api/$1';
$route['api/v1/(:any).csv'] = 'api/$1';
$route['api/v1/feedback'] = 'api/feedback';
$route['api/v1/help'] = 'api/help';
$route['api/v1/(:any)'] = 'api/$1';

// Dashboard and Home
$route['dashboard'] = 'dashboard';
$route['home'] = 'leader/home';

// Admin Payments
$route['payments/checkform/(:any)'] = 'finance/checkform/$1';
$route['payments/new'] = 'finance/adminnew';
$route['payments/(:any)'] = 'finance/details/$1';
$route['payments'] = 'finance/adminlanding';

// Admin Reports
$route['reports'] = 'reports/index';
$route['reports/new'] = 'reports/new_report';
$route['reports/(:num)'] = 'reports/view_report/$1';

// System Pages
$route['feedback'] = 'api/feedback';

// Static Pages
$route['default_controller'] = 'pages/index';
$route['(:any)'] = 'pages/page/$1';


/* End of file routes.php */
/* Location: ./application/config/routes.php */