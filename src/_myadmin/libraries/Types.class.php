<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * SQL data types definition
 *
 * @package PhpMyAdmin
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

/**
 * Generic class holding type definitions.
 *
 * @package PhpMyAdmin
 */
class PMA_Types
{
    /**
     * Returns list of unary operators.
     *
     * @return array
     */
    public function getUnaryOperators()
    {
        return array(
            'IS NULL',
            'IS NOT NULL',
            "= ''",
            "!= ''",
        );
    }

    /**
     * Check whether operator is unary.
     *
     * @param string $op operator name
     *
     * @return boolean
     */
    public function isUnaryOperator($op)
    {
        return in_array($op, $this->getUnaryOperators());
    }

    /**
     * Returns list of operators checking for NULL.
     *
     * @return array
     */
    public function getNullOperators()
    {
        return array(
            'IS NULL',
            'IS NOT NULL',
        );
    }

    /**
     * ENUM search operators
     *
     * @return array
     */
    public function getEnumOperators()
    {
        return array(
            '=',
            '!=',
        );
    }

    /**
     * TEXT search operators
     *
     * @return array
     */
    public function getTextOperators()
    {
        return array(
            'LIKE',
            'LIKE %...%',
            'NOT LIKE',
            '=',
            '!=',
            'REGEXP',
            'REGEXP ^...$',
            'NOT REGEXP',
            "= ''",
            "!= ''",
            'IN (...)',
            'NOT IN (...)',
            'BETWEEN',
            'NOT BETWEEN',
        );
    }

    /**
     * Number search operators
     *
     * @return array
     */
    public function getNumberOperators()
    {
        return array(
            '=',
            '>',
            '>=',
            '<',
            '<=',
            '!=',
            'LIKE',
            'LIKE %...%',
            'NOT LIKE',
            'IN (...)',
            'NOT IN (...)',
            'BETWEEN',
            'NOT BETWEEN',
        );
    }

    /**
     * Returns operators for given type
     *
     * @param string  $type Type of field
     * @param boolean $null Whether field can be NULL
     *
     * @return array
     */
    public function getTypeOperators($type, $null)
    {
        $ret = array();
        $class = $this->getTypeClass($type);

        if (strncasecmp($type, 'enum', 4) == 0) {
            $ret = array_merge($ret, $this->getEnumOperators());
        } elseif ($class == 'CHAR') {
            $ret = array_merge($ret, $this->getTextOperators());
        } else {
            $ret = array_merge($ret, $this->getNumberOperators());
        }

        if ($null) {
            $ret = array_merge($ret, $this->getNullOperators());
        }

        return $ret;
    }

    /**
     * Returns operators for given type as html options
     *
     * @param string  $type             Type of field
     * @param boolean $null             Whether field can be NULL
     * @param string  $selectedOperator Option to be selected
     *
     * @return string Generated Html
     */
    public function getTypeOperatorsHtml($type, $null, $selectedOperator = null)
    {
        $html = '';

        foreach ($this->getTypeOperators($type, $null) as $fc) {
            if (isset($selectedOperator) && $selectedOperator == $fc) {
                $html .= '<option value="' . htmlspecialchars($fc)  . '" selected="selected">'
                    . htmlspecialchars($fc)  . '</option>';
            } else {
                $html .= '<option value="' . htmlspecialchars($fc)  . '">'
                    . htmlspecialchars($fc)  . '</option>';
            }
        }

        return $html;
    }

    /**
     * Returns the data type description.
     *
     * @param string $type The data type to get a description.
     *
     * @return string
     *
     */
    public function getTypeDescription($type)
    {
        return '';
    }

    /**
     * Returns class of a type, used for functions available for type
     * or default values.
     *
     * @param string $type The data type to get a class.
     *
     * @return string
     *
     */
    public function getTypeClass($type)
    {
        return '';
    }

    /**
     * Returns array of functions available for a class.
     *
     * @param string $class The class to get function list.
     *
     * @return array
     *
     */
    public function getFunctionsClass($class)
    {
        return array();
    }

    /**
     * Returns array of functions available for a type.
     *
     * @param string $type The data type to get function list.
     *
     * @return array
     *
     */
    public function getFunctions($type)
    {
        $class = $this->getTypeClass($type);
        return $this->getFunctionsClass($class);
    }

    /**
     * Returns array of all functions available.
     *
     * @return array
     *
     */
    public function getAllFunctions()
    {
        $ret = array_merge(
            $this->getFunctionsClass('CHAR'),
            $this->getFunctionsClass('NUMBER'),
            $this->getFunctionsClass('DATE'),
            $this->getFunctionsClass('UUID')
        );
        sort($ret);
        return $ret;
    }

    /**
     * Returns array of all attributes available.
     *
     * @return array
     *
     */
    public function getAttributes()
    {
        return array();
    }

    /**
     * Returns array of all column types available.
     *
     * @return array
     *
     */
    public function getColumns()
    {
        // most used types
        return array(
            'INT',
            'VARCHAR',
            'TEXT',
            'DATE',
        );
    }
}

/**
 * Class holding type definitions for MySQL.
 *
 * @package PhpMyAdmin
 */
class PMA_Types_MySQL extends PMA_Types
{
    /**
     * Returns the data type description.
     *
     * @param string $type The data type to get a description.
     *
     * @return string
     *
     */
    public function getTypeDescription($type)
    {
        $type = strtoupper($type);
        switch ($type) {
        case 'TINYINT':
            return __('A 1-byte integer, signed range is -128 to 127, unsigned range is 0 to 255');
        case 'SMALLINT':
            return __('A 2-byte integer, signed range is -32,768 to 32,767, unsigned range is 0 to 65,535');
        case 'MEDIUMINT':
            return __('A 3-byte integer, signed range is -8,388,608 to 8,388,607, unsigned range is 0 to 16,777,215');
        case 'INT':
            return __('A 4-byte integer, signed range is -2,147,483,648 to 2,147,483,647, unsigned range is 0 to 4,294,967,295.');
        case 'BIGINT':
            return __('An 8-byte integer, signed range is -9,223,372,036,854,775,808 to 9,223,372,036,854,775,807, unsigned range is 0 to 18,446,744,073,709,551,615');
        case 'DECIMAL':
            return __('A fixed-point number (M, D) - the maximum number of digits (M) is 65 (default 10), the maximum number of decimals (D) is 30 (default 0)');
        case 'FLOAT':
            return __('A small floating-point number, allowable values are -3.402823466E+38 to -1.175494351E-38, 0, and 1.175494351E-38 to 3.402823466E+38');
        case 'DOUBLE':
            return __('A double-precision floating-point number, allowable values are -1.7976931348623157E+308 to -2.2250738585072014E-308, 0, and 2.2250738585072014E-308 to 1.7976931348623157E+308');
        case 'REAL':
            return __('Synonym for DOUBLE (exception: in REAL_AS_FLOAT SQL mode it is a synonym for FLOAT)');
        case 'BIT':
            return __('A bit-field type (M), storing M of bits per value (default is 1, maximum is 64)');
        case 'BOOLEAN':
            return __('A synonym for TINYINT(1), a value of zero is considered false, nonzero values are considered true');
        case 'SERIAL':
            return __('An alias for BIGINT UNSIGNED NOT NULL AUTO_INCREMENT UNIQUE');
        case 'DATE':
            return sprintf(__('A date, supported range is %1$s to %2$s'), '1000-01-01', '9999-12-31');
        case 'DATETIME':
            return sprintf(__('A date and time combination, supported range is %1$s to %2$s'), '1000-01-01 00:00:00', '9999-12-31 23:59:59');
        case 'TIMESTAMP':
            return __('A timestamp, range is 1970-01-01 00:00:01 UTC to 2038-01-09 03:14:07 UTC, stored as the number of seconds since the epoch (1970-01-01 00:00:00 UTC)');
        case 'TIME':
            return sprintf(__('A time, range is %1$s to %2$s'), '-838:59:59', '838:59:59');
        case 'YEAR':
            return __("A year in four-digit (4, default) or two-digit (2) format, the allowable values are 70 (1970) to 69 (2069) or 1901 to 2155 and 0000");
        case 'CHAR':
            return __('A fixed-length (0-255, default 1) string that is always right-padded with spaces to the specified length when stored');
        case 'VARCHAR':
            return sprintf(__('A variable-length (%s) string, the effective maximum length is subject to the maximum row size'), '0-65,535');
        case 'TINYTEXT':
            return __('A TEXT column with a maximum length of 255 (2^8 - 1) characters, stored with a one-byte prefix indicating the length of the value in bytes');
        case 'TEXT':
            return __('A TEXT column with a maximum length of 65,535 (2^16 - 1) characters, stored with a two-byte prefix indicating the length of the value in bytes');
        case 'MEDIUMTEXT':
            return __('A TEXT column with a maximum length of 16,777,215 (2^24 - 1) characters, stored with a three-byte prefix indicating the length of the value in bytes');
        case 'LONGTEXT':
            return __('A TEXT column with a maximum length of 4,294,967,295 or 4GiB (2^32 - 1) characters, stored with a four-byte prefix indicating the length of the value in bytes');
        case 'BINARY':
            return __('Similar to the CHAR type, but stores binary byte strings rather than non-binary character strings');
        case 'VARBINARY':
            return __('Similar to the VARCHAR type, but stores binary byte strings rather than non-binary character strings');
        case 'TINYBLOB':
            return __('A BLOB column with a maximum length of 255 (2^8 - 1) bytes, stored with a one-byte prefix indicating the length of the value');
        case 'MEDIUMBLOB':
            return __('A BLOB column with a maximum length of 16,777,215 (2^24 - 1) bytes, stored with a three-byte prefix indicating the length of the value');
        case 'BLOB':
            return __('A BLOB column with a maximum length of 65,535 (2^16 - 1) bytes, stored with a two-byte prefix indicating the length of the value');
        case 'LONGBLOB':
            return __('A BLOB column with a maximum length of 4,294,967,295 or 4GiB (2^32 - 1) bytes, stored with a four-byte prefix indicating the length of the value');
        case 'ENUM':
            return __("An enumeration, chosen from the list of up to 65,535 values or the special '' error value");
        case 'SET':
            return __("A single value chosen from a set of up to 64 members");
        case 'GEOMETRY':
            return __('A type that can store a geometry of any type');
        case 'POINT':
            return __('A point in 2-dimensional space');
        case 'LINESTRING':
            return __('A curve with linear interpolation between points');
        case 'POLYGON':
            return __('A polygon');
        case 'MULTIPOINT':
            return __('A collection of points');
        case 'MULTILINESTRING':
            return __('A collection of curves with linear interpolation between points');
        case 'MULTIPOLYGON':
            return __('A collection of polygons');
        case 'GEOMETRYCOLLECTION':
            return __('A collection of geometry objects of any type');
        }
        return '';
    }

    /**
     * Returns class of a type, used for functions available for type
     * or default values.
     *
     * @param string $type The data type to get a class.
     *
     * @return string
     *
     */
    public function getTypeClass($type)
    {
        $type = strtoupper($type);
        switch ($type) {
        case 'TINYINT':
        case 'SMALLINT':
        case 'MEDIUMINT':
        case 'INT':
        case 'BIGINT':
        case 'DECIMAL':
        case 'FLOAT':
        case 'DOUBLE':
        case 'REAL':
        case 'BIT':
        case 'BOOLEAN':
        case 'SERIAL':
            return 'NUMBER';

        case 'DATE':
        case 'DATETIME':
        case 'TIMESTAMP':
        case 'TIME':
        case 'YEAR':
            return 'DATE';

        case 'CHAR':
        case 'VARCHAR':
        case 'TINYTEXT':
        case 'TEXT':
        case 'MEDIUMTEXT':
        case 'LONGTEXT':
        case 'BINARY':
        case 'VARBINARY':
        case 'TINYBLOB':
        case 'MEDIUMBLOB':
        case 'BLOB':
        case 'LONGBLOB':
        case 'ENUM':
        case 'SET':
            return 'CHAR';

        case 'GEOMETRY':
        case 'POINT':
        case 'LINESTRING':
        case 'POLYGON':
        case 'MULTIPOINT':
        case 'MULTILINESTRING':
        case 'MULTIPOLYGON':
        case 'GEOMETRYCOLLECTION':
            return 'SPATIAL';
        }

        return '';
    }

    /**
     * Returns array of functions available for a class.
     *
     * @param string $class The class to get function list.
     *
     * @return array
     *
     */
    public function getFunctionsClass($class)
    {
        switch ($class) {
        case 'CHAR':
            return array(
                'BIN',
                'CHAR',
                'COMPRESS',
                'CURRENT_USER',
                'DATABASE',
                'DAYNAME',
                'DES_DECRYPT',
                'DES_ENCRYPT',
                'ENCRYPT',
                'HEX',
                'INET_NTOA',
                'LOAD_FILE',
                'LOWER',
                'LTRIM',
                'MD5',
                'MONTHNAME',
                'OLD_PASSWORD',
                'PASSWORD',
                'QUOTE',
                'REVERSE',
                'RTRIM',
                'SHA1',
                'SOUNDEX',
                'SPACE',
                'TRIM',
                'UNCOMPRESS',
                'UNHEX',
                'UPPER',
                'USER',
                'UUID',
                'VERSION',
            );

        case 'DATE':
            return array(
                'CURRENT_DATE',
                'CURRENT_TIME',
                'DATE',
                'FROM_DAYS',
                'FROM_UNIXTIME',
                'LAST_DAY',
                'NOW',
                'SEC_TO_TIME',
                'SYSDATE',
                'TIME',
                'TIMESTAMP',
                'UTC_DATE',
                'UTC_TIME',
                'UTC_TIMESTAMP',
                'YEAR',
            );

        case 'NUMBER':
            $ret = array(
                'ABS',
                'ACOS',
                'ASCII',
                'ASIN',
                'ATAN',
                'BIT_LENGTH',
                'BIT_COUNT',
                'CEILING',
                'CHAR_LENGTH',
                'CONNECTION_ID',
                'COS',
                'COT',
                'CRC32',
                'DAYOFMONTH',
                'DAYOFWEEK',
                'DAYOFYEAR',
                'DEGREES',
                'EXP',
                'FLOOR',
                'HOUR',
                'INET_ATON',
                'LENGTH',
                'LN',
                'LOG',
                'LOG2',
                'LOG10',
                'MICROSECOND',
                'MINUTE',
                'MONTH',
                'OCT',
                'ORD',
                'PI',
                'QUARTER',
                'RADIANS',
                'RAND',
                'ROUND',
                'SECOND',
                'SIGN',
                'SIN',
                'SQRT',
                'TAN',
                'TO_DAYS',
                'TO_SECONDS',
                'TIME_TO_SEC',
                'UNCOMPRESSED_LENGTH',
                'UNIX_TIMESTAMP',
                'UUID_SHORT',
                'WEEK',
                'WEEKDAY',
                'WEEKOFYEAR',
                'YEARWEEK',
            );
            // remove functions that are unavailable on current server
            if (PMA_MYSQL_INT_VERSION < 50500) {
                $ret = array_diff($ret, array('TO_SECONDS'));
            }
            if (PMA_MYSQL_INT_VERSION < 50120) {
                $ret = array_diff($ret, array('UUID_SHORT'));
            }
            return $ret;

        case 'SPATIAL':
            return array(
                'GeomFromText',
                'GeomFromWKB',

                'GeomCollFromText',
                'LineFromText',
                'MLineFromText',
                'PointFromText',
                'MPointFromText',
                'PolyFromText',
                'MPolyFromText',

                'GeomCollFromWKB',
                'LineFromWKB',
                'MLineFromWKB',
                'PointFromWKB',
                'MPointFromWKB',
                'PolyFromWKB',
                'MPolyFromWKB',
            );
        }
        return array();
    }

    /**
     * Returns array of all attributes available.
     *
     * @return array
     *
     */
    public function getAttributes()
    {
        return array(
            '',
            'BINARY',
            'UNSIGNED',
            'UNSIGNED ZEROFILL',
            'on update CURRENT_TIMESTAMP',
        );
    }

    /**
     * Returns array of all column types available.
     *
     * VARCHAR, TINYINT, TEXT and DATE are listed first, based on
     * estimated popularity.
     *
     * @return array
     *
     */
    public function getColumns()
    {
        $ret = parent::getColumns();
        // numeric
        $ret[_pgettext('numeric types', 'Numeric')] = array(
            'TINYINT',
            'SMALLINT',
            'MEDIUMINT',
            'INT',
            'BIGINT',
            '-',
            'DECIMAL',
            'FLOAT',
            'DOUBLE',
            'REAL',
            '-',
            'BIT',
            'BOOLEAN',
            'SERIAL',
        );


        // Date/Time
        $ret[_pgettext('date and time types', 'Date and time')] = array(
            'DATE',
            'DATETIME',
            'TIMESTAMP',
            'TIME',
            'YEAR',
        );

        // Text
        $ret[_pgettext('string types', 'String')] = array(
            'CHAR',
            'VARCHAR',
            '-',
            'TINYTEXT',
            'TEXT',
            'MEDIUMTEXT',
            'LONGTEXT',
            '-',
            'BINARY',
            'VARBINARY',
            '-',
            'TINYBLOB',
            'MEDIUMBLOB',
            'BLOB',
            'LONGBLOB',
            '-',
            'ENUM',
            'SET',
        );

        $ret[_pgettext('spatial types', 'Spatial')] = array(
            'GEOMETRY',
            'POINT',
            'LINESTRING',
            'POLYGON',
            'MULTIPOINT',
            'MULTILINESTRING',
            'MULTIPOLYGON',
            'GEOMETRYCOLLECTION',
        );

        return $ret;
    }
}

/**
 * Class holding type definitions for Drizzle.
 *
 * @package PhpMyAdmin
 */
class PMA_Types_Drizzle extends PMA_Types
{
    /**
     * Returns the data type description.
     *
     * @param string $type The data type to get a description.
     *
     * @return string
     *
     */
    public function getTypeDescription($type)
    {
        $type = strtoupper($type);
        switch ($type) {
        case 'INTEGER':
            return __('A 4-byte integer, range is -2,147,483,648 to 2,147,483,647');
        case 'BIGINT':
            return __('An 8-byte integer, range is -9,223,372,036,854,775,808 to 9,223,372,036,854,775,807');
        case 'DECIMAL':
            return __('A fixed-point number (M, D) - the maximum number of digits (M) is 65 (default 10), the maximum number of decimals (D) is 30 (default 0)');
        case 'DOUBLE':
            return __("A system's default double-precision floating-point number");
        case 'BOOLEAN':
            return __('True or false');
        case 'SERIAL':
            return __('An alias for BIGINT NOT NULL AUTO_INCREMENT UNIQUE');
        case 'UUID':
            return __('Stores a Universally Unique Identifier (UUID)');
        case 'DATE':
            return sprintf(__('A date, supported range is %1$s to %2$s'), '0001-01-01', '9999-12-31');
        case 'DATETIME':
            return sprintf(__('A date and time combination, supported range is %1$s to %2$s'), '0001-01-01 00:00:0', '9999-12-31 23:59:59');
        case 'TIMESTAMP':
            return __("A timestamp, range is '0001-01-01 00:00:00' UTC to '9999-12-31 23:59:59' UTC; TIMESTAMP(6) can store microseconds");
        case 'TIME':
            return sprintf(__('A time, range is %1$s to %2$s'), '00:00:00', '23:59:59');
        case 'VARCHAR':
            return sprintf(__('A variable-length (%s) string, the effective maximum length is subject to the maximum row size'), '0-16,383');
        case 'TEXT':
            return __('A TEXT column with a maximum length of 65,535 (2^16 - 1) characters, stored with a two-byte prefix indicating the length of the value in bytes');
        case 'VARBINARY':
            return __('A variable-length (0-65,535) string, uses binary collation for all comparisons');
        case 'BLOB':
            return __('A BLOB column with a maximum length of 65,535 (2^16 - 1) bytes, stored with a two-byte prefix indicating the length of the value');
        case 'ENUM':
            return __("An enumeration, chosen from the list of defined values");
        }
        return '';
    }

    /**
     * Returns class of a type, used for functions available for type
     * or default values.
     *
     * @param string $type The data type to get a class.
     *
     * @return string
     *
     */
    public function getTypeClass($type)
    {
        $type = strtoupper($type);
        switch ($type) {
        case 'INTEGER':
        case 'BIGINT':
        case 'DECIMAL':
        case 'DOUBLE':
        case 'BOOLEAN':
        case 'SERIAL':
            return 'NUMBER';

        case 'DATE':
        case 'DATETIME':
        case 'TIMESTAMP':
        case 'TIME':
            return 'DATE';

        case 'VARCHAR':
        case 'TEXT':
        case 'VARBINARY':
        case 'BLOB':
        case 'ENUM':
            return 'CHAR';

        case 'UUID':
            return 'UUID';
        }
        return '';
    }

    /**
     * Returns array of functions available for a class.
     *
     * @param string $class The class to get function list.
     *
     * @return array
     *
     */
    public function getFunctionsClass($class)
    {
        switch ($class) {
        case 'CHAR':
            $ret = array(
                'BIN',
                'CHAR',
                'COMPRESS',
                'CURRENT_USER',
                'DATABASE',
                'DAYNAME',
                'HEX',
                'LOAD_FILE',
                'LOWER',
                'LTRIM',
                'MD5',
                'MONTHNAME',
                'QUOTE',
                'REVERSE',
                'RTRIM',
                'SCHEMA',
                'SPACE',
                'TRIM',
                'UNCOMPRESS',
                'UNHEX',
                'UPPER',
                'USER',
                'UUID',
                'VERSION',
            );

            // check for some functions known to be in modules
            $functions = array(
                'MYSQL_PASSWORD',
                'ROT13',
            );

            // add new functions
            $sql = "SELECT upper(plugin_name) f
                FROM data_dictionary.plugins
                WHERE plugin_name IN ('" . implode("','", $functions) . "')
                  AND plugin_type = 'Function'
                  AND is_active";
            $drizzle_functions = PMA_DBI_fetch_result($sql, 'f', 'f');
            if (count($drizzle_functions) > 0) {
                $ret = array_merge($ret, $drizzle_functions);
                sort($ret);
            }

            return $ret;

        case 'UUID':
            return array(
                'UUID',
            );

        case 'DATE':
            return array(
                'CURRENT_DATE',
                'CURRENT_TIME',
                'DATE',
                'FROM_DAYS',
                'FROM_UNIXTIME',
                'LAST_DAY',
                'NOW',
                'SYSDATE',
                //'TIME', // https://bugs.launchpad.net/drizzle/+bug/804571
                'TIMESTAMP',
                'UTC_DATE',
                'UTC_TIME',
                'UTC_TIMESTAMP',
                'YEAR',
            );

        case 'NUMBER':
            return array(
                'ABS',
                'ACOS',
                'ASCII',
                'ASIN',
                'ATAN',
                'BIT_COUNT',
                'CEILING',
                'CHAR_LENGTH',
                'CONNECTION_ID',
                'COS',
                'COT',
                'CRC32',
                'DAYOFMONTH',
                'DAYOFWEEK',
                'DAYOFYEAR',
                'DEGREES',
                'EXP',
                'FLOOR',
                'HOUR',
                'LENGTH',
                'LN',
                'LOG',
                'LOG2',
                'LOG10',
                'MICROSECOND',
                'MINUTE',
                'MONTH',
                'OCT',
                'ORD',
                'PI',
                'QUARTER',
                'RADIANS',
                'RAND',
                'ROUND',
                'SECOND',
                'SIGN',
                'SIN',
                'SQRT',
                'TAN',
                'TO_DAYS',
                'TIME_TO_SEC',
                'UNCOMPRESSED_LENGTH',
                'UNIX_TIMESTAMP',
                //'WEEK', // same as TIME
                'WEEKDAY',
                'WEEKOFYEAR',
                'YEARWEEK',
            );
        }
        return array();
    }

    /**
     * Returns array of all attributes available.
     *
     * @return array
     *
     */
    public function getAttributes()
    {
        return array(
            '',
            'on update CURRENT_TIMESTAMP',
        );
    }

    /**
     * Returns array of all column types available.
     *
     * @return array
     *
     */
    public function getColumns()
    {
        $types_num = array(
            'INTEGER',
            'BIGINT',
            '-',
            'DECIMAL',
            'DOUBLE',
            '-',
            'BOOLEAN',
            'SERIAL',
            'UUID',
        );
        $types_date = array(
            'DATE',
            'DATETIME',
            'TIMESTAMP',
            'TIME',
        );
        $types_string = array(
            'VARCHAR',
            'TEXT',
            '-',
            'VARBINARY',
            'BLOB',
            '-',
            'ENUM',
        );
        if (PMA_MYSQL_INT_VERSION >= 20120130) {
            $types_string[] = '-';
            $types_string[] = 'IPV6';
        }

        $ret = parent::getColumns();
        // numeric
        $ret[_pgettext('numeric types', 'Numeric')] = $types_num;

        // Date/Time
        $ret[_pgettext('date and time types', 'Date and time')] = $types_date;

        // Text
        $ret[_pgettext('string types', 'String')] = $types_string;

        return $ret;
    }
}




