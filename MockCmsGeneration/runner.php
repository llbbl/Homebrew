<?php
require( __DIR__ ."/config.php");

function buildTables($schema, $whitelist)
{
    $tables = array();

    foreach($schema as $changeSet)
    {
        $table = $changeSet->createTable;
        $name = (string) $table['tableName'];

        // check if tables is in whitelist
        if (!in_array($name, $whitelist))
        {
            continue;
        }

        // create add key with this table name
        $tables[$name] = array();

        // in whitelist, add columns
        foreach($table->column as $column)
        {
            $colName = (string) $column['name'];
            $colType = (string) $column['type'];

            // add to array
            $tables[$name][$colName] = $colType;
        }
    }

    return $tables;
}

$cms = simplexml_load_string(file_get_contents(XML_FILE));
$list = array(
    'dsplit'=>10,
    'dvdn'  =>10,
    'hsplit'=>5,
    'hvdn'  =>5,
    'hagent'=>5,
    'haglog'=>5,
    'htkgrp'=>5
);

$tableObj = buildTables($cms, array_keys($list));
echo print_r($tableObj, true);

