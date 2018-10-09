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

// Fields to add
$new_fields = array(
	'alias_board' => array('name'=> 'alias_board', 'type'=>'VARCHAR(255)'),
);

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

if(!empty($using_ssi))
	echo 'If no errors, Success!';
?>