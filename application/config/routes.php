<?php
defined('BASEPATH') or exit('No direct script access allowed');

$route['loginMe'] = 'login/loginMe'; // view: login
$route['dashboard'] = 'Dashboard_Controller/dashboard'; // view: dashboard
$route['loadChangePass'] = "Dashboard_Controller/loadChangePass"; // view: changePassword
$route['logout'] = 'Dashboard_Controller/logout'; // redirect login
$route['changePassword'] = "Dashboard_Controller/changePassword"; // redirect loadChangePass

// ============== [ api ] ==============
$route['api/getOTP'] = 'API_Controller/getOTP';
$route['api/sendMessage'] = 'API_Controller/sendMessage';

// ============== [ contacts ] ==============
$route['contacts/all'] = 'Contacts_Controller/all';
$route['contacts/view'] = "Contacts_Controller/view"; 
$route['contacts/add'] = 'Contacts_Controller/add'; 
$route['contacts/edit'] = "Contacts_Controller/edit"; 
$route['contacts/update'] = "Contacts_Controller/update"; 
$route['contacts/save'] = 'Contacts_Controller/save'; 
$route['contacts/delete'] = 'Contacts_Controller/delete';
$route['contacts/deleteSelected'] = 'Contacts_Controller/deleteSelected';
$route['contacts/import'] = 'Contacts_Controller/import';
$route['contacts/importAction'] = 'Contacts_Controller/importAction'; 

// ============== [ message ] ==============
$route['messages/testsms'] = 'Messages_Controller/testsms'; 
$route['messages/all'] = 'Messages_Controller/all'; 
$route['messages/view'] = "Messages_Controller/view";
$route['messages/send'] = 'Messages_Controller/send'; 
$route['messages/save'] = 'Messages_Controller/save'; 
$route['messages/delete'] = 'Messages_Controller/delete'; 
$route['messages/deleteSelected'] = 'Messages_Controller/deleteSelected';
$route['messages/import'] = 'Messages_Controller/import'; 
$route['messages/importAction'] = 'Messages_Controller/importAction'; 


$route['default_controller'] = 'login';
$route['404_override'] = '';
$route['translate_uri_dashes'] = FALSE;
