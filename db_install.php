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
db_extend('Packages');

// Add the "alias_board" column to the boards table
$smcFunc['db_add_column'](
	'{db_prefix}boards', 
	array(
		'name' => 'alias_board', 
		'size' => 5, 
		'type' => 'smallint', 
		'null' => false, 
		'default' => 0
	)
);

// Change the redirects so that when the mod is uninstalled, the board "alias" becomes a redirect:
$smcFunc['db_query']('', '
	UPDATE {db_prefix}boards
	SET redirect = CONCAT("' . $boardurl . '/index.php?board=", alias_board, ".0") 
	WHERE alias_board > {int:no_alias}',
	array(
		'no_alias' => 0,
	)
);

if(!empty($using_ssi))
	echo 'If no errors, Success!';
?>