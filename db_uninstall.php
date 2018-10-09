<?php
global $db_prefix, $modSettings, $func, $smcFunc;

// Hopefully we have the goodies.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$using_ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

$smcFunc['db_query']('', '
	DELETE FROM {db_prefix}boards 
	WHERE alias_board > 0',
	array()
);


$smcFunc['db_query']('', '
	ALTER TABLE ' . $db_prefix . 'boards DROP `alias_board`'
);

if(!empty($using_ssi))
	echo 'If no errors, Success!';
?>