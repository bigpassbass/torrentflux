<?php

/* $Id$ */

/*******************************************************************************

 LICENSE

 This program is free software; you can redistribute it and/or
 modify it under the terms of the GNU General Public License (GPL)
 as published by the Free Software Foundation; either version 2
 of the License, or (at your option) any later version.

 This program is distributed in the hope that it will be useful,
 but WITHOUT ANY WARRANTY; without even the implied warranty of
 MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 GNU General Public License for more details.

 To read the license please visit http://www.gnu.org/copyleft/gpl.html

*******************************************************************************/

// prevent direct invocation
if ((!isset($cfg['user'])) || (isset($_REQUEST['cfg']))) {
	@ob_end_clean();
	@header("location: ../../../index.php");
	exit();
}

/******************************************************************************/

$newUser = tfb_getRequestVar('newUser');
$pass1 = tfb_getRequestVar('pass1');
$pass2 = tfb_getRequestVar('pass2');
$userType = tfb_getRequestVar('userType');

// check username
$usernameCheck = checkUsername($newUser);
	
// check password
$passwordCheck = checkPassword($pass1, $pass2);
	
// new user ?
$newUser = strtolower($newUser);
if (($usernameCheck === true) && ($passwordCheck === true)) {
	addNewUser($newUser, $pass1, $userType);
	AuditAction($cfg["constants"]["admin"], $cfg['_NEWUSER'].": ".$newUser);
	@header("location: admin.php?op=showUsers");
	exit();
}

// init template-instance
tmplInitializeInstance($cfg["theme"], "page.admin.addUser.tmpl");

// set vars
$tmpl->setvar('newUser', $newUser);

// error
	
// backward-compat-vars
$tmpl->setvar('_TRYDIFFERENTUSERID', $cfg['_TRYDIFFERENTUSERID']);
$tmpl->setvar('_HASBEENUSED', $cfg['_HASBEENUSED']);

// error-vars
$tmpl->setvar('errUsername', ($usernameCheck !== true) ? 1 : 0);
$tmpl->setvar('errMsgUsername', ($usernameCheck !== true) ? $usernameCheck : '');
$tmpl->setvar('errPassword', ($passwordCheck !== true) ? 1 : 0);
$tmpl->setvar('errMsgPassword', ($passwordCheck !== true) ? $passwordCheck : '');
	
//
tmplSetTitleBar("Administration - Add User");
tmplSetAdminMenu();
tmplSetFoot();
tmplSetIidVars();

// parse template
$tmpl->pparse();

?>