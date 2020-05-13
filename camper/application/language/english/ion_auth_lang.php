<?php  if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
* Name:  Ion Auth Lang - English
*
* Author: Ben Edmunds
* 		  ben.edmunds@gmail.com
*         @benedmunds
*
* Location: http://github.com/benedmunds/ion_auth/
*
* Created:  03.14.2010
*
* Description:  English language file for Ion Auth messages and errors
*
*/

// Account Creation
$lang['account_creation_successful'] 	  	 = '<i class="icon-ok teal"></i> Account Successfully Created';
$lang['account_creation_unsuccessful'] 	 	 = 'Unable to Create Account';
$lang['account_creation_duplicate_email'] 	 = 'Email Already Used or Invalid';
$lang['account_creation_duplicate_username'] = 'Username Already Used or Invalid';

// Password
$lang['password_change_successful'] 	 	 = '<i class="icon-ok teal"></i> Password Successfully Changed';
$lang['password_change_unsuccessful'] 	  	 = 'Unable to Change Password';
$lang['forgot_password_successful'] 	 	 = '<i class="icon-ok teal"></i> Password Reset Email Sent';
$lang['forgot_password_unsuccessful'] 	 	 = 'Unable to Reset Password';

// Activation
$lang['activate_successful'] 		  	     = '<i class="icon-ok teal"></i> Your account is now active, sign in below.';
$lang['activate_unsuccessful'] 		 	     = '<i class="icon-info-sign red"></i> We couldn\'t activate your account, please contact us for more information.';
$lang['deactivate_successful'] 		  	     = '<i class="icon-ok teal"></i> Your account has been de-activated';
$lang['deactivate_unsuccessful'] 	  	     = '<i class="icon-info-sign red"></i> Unable to De-Activate Account';
$lang['activation_email_successful'] 	  	 = '<i class="icon-ok teal"></i> Activation Email Sent';
$lang['activation_email_unsuccessful']   	 = '<i class="icon-info-sign red"></i> Unable to Send Activation Email';

// Login / Logout
$lang['login_successful'] 		  	         = 'You\'ve been signed in, welcome back.';
$lang['login_unsuccessful'] 		  	     = '<i class="icon-info-sign red"></i> Your email or password was not correct, please try again.';
$lang['login_unsuccessful_not_active'] 		 = '<i class="icon-info-sign red"></i> Your account is not active, please check your email for an activation link (if you just created an account) or contact us for more information.';
$lang['login_timeout']                       = '<i class="icon-remove red"></i> You have been temporarily Locked Out.  Please try again later.';
$lang['logout_successful'] 		 	         = '<i class="icon-ok teal"></i> You have signed out successfully';

// Account Changes
$lang['update_successful'] 		 	         = '<i class="icon-ok teal"></i> Account Information Successfully Updated';
$lang['update_unsuccessful'] 		 	     = '<i class="icon-info-sign red"></i> Unable to Update Account Information';
$lang['delete_successful']               = '<i class="icon-ok teal"></i> User Deleted';
$lang['delete_unsuccessful']           = '<i class="icon-info-sign red"></i> Unable to Delete User';

// Groups
$lang['group_creation_successful']  = 'Group created Successfully';
$lang['group_already_exists']       = 'Group name already taken';
$lang['group_update_successful']    = 'Group details updated';
$lang['group_delete_successful']    = 'Group deleted';
$lang['group_delete_unsuccessful'] 	= 'Unable to delete group';
$lang['group_name_required'] 		= 'Group name is a required field';

// Activation Email
$lang['email_activation_subject']            = 'Account Activation';
$lang['email_activate_heading']    = 'Activate account for %s';
$lang['email_activate_subheading'] = 'Please click this link to %s.';
$lang['email_activate_link']       = 'Activate Your Account';

// Forgot Password Email
$lang['email_forgotten_password_subject']    = 'Forgotten Password Verification';
$lang['email_forgot_password_heading']    = 'Reset Password for %s';
$lang['email_forgot_password_subheading'] = 'Please click this link to %s.';
$lang['email_forgot_password_link']       = 'Reset Your Password';

// New Password Email
$lang['email_new_password_subject']          = 'New Password';
$lang['email_new_password_heading']    = 'New Password for %s';
$lang['email_new_password_subheading'] = 'Your password has been reset to: %s';
