<?php
	# Mantis - a php based bugtracking system
	# Copyright (C) 2002 - 2004  Mantis Team   - mantisbt-dev@lists.sourceforge.net
	# Copyright (C) 2004  Gerrit Beine - gerrit.beine@pitcom.de
	# This program is distributed under the terms and conditions of the GPL
	# See the README and LICENSE files for details

	# --------------------------------------------------------
	# $Id: bug_report_mail.php,v 1.17 2010/04/13 00:14:32 SL-Server\SC Kruiper Exp $
	# --------------------------------------------------------

	# This page receives an E-Mail via POP3 and IMAP and generates a new issue

	$GLOBALS[ 'g_bypass_headers' ] = 1;

	# Make sure this script doesn't run via the webserver
	$t_mail_secured_script = plugin_config_get( 'mail_secured_script' );
	if( php_sapi_name() !== 'cli' && $t_mail_secured_script )
	{
		echo "bug_report_mail.php is not allowed to run through the webserver.\n";
		exit( 1 );
	}

	require_once( plugin_config_get( 'path_erp', NULL, TRUE ) . 'core/mail_api.php' );

	$t_mailboxes  = plugin_config_get( 'mailboxes' );

	$t_mail_mantisbt_url_fix = plugin_config_get( 'mail_mantisbt_url_fix', '' );
	if ( php_sapi_name() === 'cli' && !is_blank( $t_mail_mantisbt_url_fix ) )
	{
		config_set_global( 'path', $t_mail_mantisbt_url_fix );
	}

	$t_mailbox_api = new ERP_mailbox_api;

	foreach ( $t_mailboxes as $t_mailbox )
	{
		set_time_limit( 30 );

		$t_mailbox_api->process_mailbox( $t_mailbox );
	}

	echo "\n\n" . 'Done checking all mailboxes' . "\n";

	exit( 0 );
?>

