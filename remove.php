<?php
error_reporting(E_ALL);

// Hopefully we have the goodies.
if (file_exists(dirname(__FILE__) . '/SSI.php') && !defined('SMF'))
{
	$using_ssi = true;
	require_once(dirname(__FILE__) . '/SSI.php');
}
elseif (!defined('SMF'))
	exit('<b>Error:</b> Cannot install - please verify you put this in the same place as SMF\'s index.php.');

global $db_prefix, $modSettings, $func, $smcFunc;
$modSettings['disableQueryCheck'] = true;

// SleePy is lazy, so I will just check to see if db_query exists
if(!function_exists('db_query'))
{
	db_extend('Packages');
	function db_query($query, $file, $line)
	{
		global $smcFunc;
		return $smcFunc['db_query']('', $query, array('db_error_skip' => true));
	}
}
// All work here is for back support for SMF 1.1, It is easier to support 2.0 and backport.
else
{
	$smcFunc = $func;
	$smcFunc['db_num_rows'] = 'mysql_num_rows';
	$smcFunc['db_free_result'] = 'mysql_free_result';
	$smcFunc['db_fetch_assoc'] = 'mysql_fetch_assoc';
	$smcFunc['db_list_columns'] = 'mysql_show_columns';
	$smcFunc['db_add_column'] = 'mysql_create_columns';

	// Quickly emulate these functions.
	function mysql_show_columns($table_name)
	{
		global $smcFunc, $db_prefix;

		$result = db_query("SHOW FIELDS FROM {$table_name}", __FILE__, __LINE__);;
		$columns = array();
		while ($row = $smcFunc['db_fetch_assoc']($result))
			$columns[] = $row['Field'];
		return $columns;
	}
	function mysql_create_columns($table_name, $column_info)
	{
		global $db_prefix;

		return db_query('ALTER TABLE ' . $table_name . '
			ADD ' . $column_info['name'] . ' ' . $column_info['type'] . ' ' . (empty($column_info['null']) ? 'NOT NULL' : '') . ' ' .
		(empty($column_info['default']) ? '' : 'default \'' . $column_info['default'] . '\'') . ' ' .
		(empty($column_info['auto']) ? '' : 'auto_increment') . ' ', __FILE__, __LINE__);
	}
}

// Fields to add
$new_fields = array(
	'alias_cat' => array('name'=> 'alias_cat', 'type'=>'VARCHAR', 'size' => '255'),
	'alias_child' => array('name'=> 'alias_child', 'type'=>'VARCHAR', 'size' => '255'),
);

// Do you already got my goodies!
$table_columns = $smcFunc['db_list_columns']($db_prefix . "boards");

// Do the loopy, loop, loe.
foreach ($new_fields as $column_name => $column_attributes)
{
	// Dang, I guess I must share.
	if(!in_array($column_name, $table_columns))
		$smcFunc['db_remove_column']($db_prefix . "boards", $column_attributes['name'], array('no_prefix' => TRUE));
}

if(!empty($using_ssi))
	echo 'If no errors, Success!';
?>