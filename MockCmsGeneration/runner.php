<?php
require( __DIR__ ."/config.php");

/*
 * --== Start Functions ==--
 */
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

/**
 * Shuffle and array and return the first row
 * @param $arr
 * @return mixed
 */
function shuffleSelect($arr)
{
    shuffle($arr);
    return $arr[0];
}


/**
 * Generate a configurable number of rows
 *
 * @param $columns
 * @param $numOfRows
 * @return array
 */
function generateRows($columns, $numOfRows)
{
    $rows = array();
    for($i=0; $i<$numOfRows; $i++)
    {
        $rows[$i] = generateRow($columns);
    }

    return $rows;
}

/**
 * Generate a single row of data for this table
 * @param $tableName
 * @param $columns
 * @return array
 */
function generateRow($columns)
{
    $colValues = array();
    foreach($columns as $name=>$type)
    {
        $value = generateValue($name, $type);
        $colValues[$name] = $value;
    }

    return $colValues;
}


/**
 * Generate a random value from these inputs
 *
 * @param $name
 * @param $type
 * @return int|mixed|string
 * @throws Exception
 */
function generateValue($name, $type)
{
    $value = false;

    if ($name == 'row_date')
    {
        $value = BASE_DATE;
    }
    elseif ($name == 'starttime')
    {
        $value = 'todo';
    }
    elseif ($name == 'starttime_utc')
    {
        $value = 'todo';
    }
    elseif($name == 'logid')
    {
        $value = (string) shuffleSelect(range(STARTING_LOG_ID, STARTING_LOG_ID + MAX_DISTINCT_AGENT));
    }
    elseif ($name == 'intrvl')
    {
        $value = BASE_INTERVAL;
    }
    elseif ($name == 'acd')
    {
        $value = shuffleSelect(array(1, 9));
    }
    elseif ($name == 'split')
    {
        $value = shuffleSelect(array(507,52,505,50,53,506));
    }
    elseif ($name == 'extension')
    {
        $value = shuffleSelect(array('2601','2618','2624','2625','2614','2699',
                                     '2698','2615','2611','2600','2617','2612','2616'));
    }
    elseif ($name == 'vdn')
    {
        $value = shuffleSelect(array('1205',
            '1212',
            '1217',
            '1860',
            '2505',
            '2506',
            '2507',
            '2511',
            '2512',
            '2517',
            '2690'));
    }
    elseif ($name == 'vector')
    {
        $value = shuffleSelect(array(
            1,
            53,
            1860,
            2517,
            5050,
            5058,
            5111,
            5121,
            5171,
            5172,
            5173,
            6901
        ));
    }
    else if ($type == 'SMALLINT')
    {
       $value = rand(0, 32000);
    }
    else if ($type == 'INT' || $type == 'INTEGER')
    {
        $value = rand(0, 64000);
    }
    else if ($type == 'CHAR(15)')
    {
        $value = (string) rand(0, 1000); // completely arbitrary
    }
    else if ($type == 'CHAR(1)')
    {
        $value = (string) rand(0, 9); // completely arbitrary
    }
    else if (strtoupper($type) == 'DATE')
    {
        $value = BASE_DATE; // may want to mix this up a bit
    }
    else
    {
        throw new Exception("Unknown name ($name) or type ($type)");
    }

    return $value;
}


/**
 * Add quotes around necessary values
 *
 * Create an insert statement
 * @param $tableName
 * @param $row
 */
function generateSqlString($tableName, $row)
{
    $sql = 'INSERT INTO [' . $tableName . '] (';
    $sql .= implode(',', array_keys($row));
    $sql .= ') VALUES (';
    $sql .= implode(',', array_values($row));
    $sql .= ");\n";

    return $sql;
}

/*
 * --== Start Initialization ==--
 */


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

/*
 * --== Start Implementation ==--
 */

$tableObj = buildTables($cms, array_keys($list));

// loop through each
foreach($list as $name=>$cnt)
{
    $rows = generateRows($tableObj[$name], $cnt);
    $fp = fopen(OUT_DIR . $name . '.sql', 'w+');

    foreach($rows as $row)
    {
        fwrite($fp, generateSqlString($name, $row));
    }

    fclose($fp);
}




