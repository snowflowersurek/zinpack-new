<?php
/* vim: set expandtab sw=4 ts=4 sts=4: */
/**
 * The MyISAM storage engine
 *
 * @package PhpMyAdmin-Engines
 */
if (! defined('PHPMYADMIN')) {
    exit;
}

if (! defined('PHPMYADMIN')) {
    exit;
}

/**
 * The MyISAM storage engine
 *
 * @package PhpMyAdmin-Engines
 */
class PMA_StorageEngine_myisam extends PMA_StorageEngine
{
    /**
     * Returns array with variable names dedicated to MyISAM storage engine
     *
     * @return array   variable names
     */
    function getVariables()
    {
        return array(
            'myisam_data_pointer_size' => array(
                'title' => __('Data pointer size'),
                'desc'  => __('The default pointer size in bytes, to be used by CREATE TABLE for MyISAM tables when no MAX_ROWS option is specified.'),
                'type'  => PMA_ENGINE_DETAILS_TYPE_SIZE,
            ),
            'myisam_recover_options' => array(
                'title' => __('Automatic recovery mode'),
                'desc'  => __('The mode for automatic recovery of crashed MyISAM tables, as set via the --myisam-recover server startup option.'),
            ),
            'myisam_max_sort_file_size' => array(
                'title' => __('Maximum size for temporary sort files'),
                'desc'  => __('The maximum size of the temporary file MySQL is allowed to use while re-creating a MyISAM index (during REPAIR TABLE, ALTER TABLE, or LOAD DATA INFILE).'),
                'type'  => PMA_ENGINE_DETAILS_TYPE_SIZE,
            ),
            'myisam_max_extra_sort_file_size' => array(
                'title' => __('Maximum size for temporary files on index creation'),
                'desc'  => __('If the temporary file used for fast MyISAM index creation would be larger than using the key cache by the amount specified here, prefer the key cache method.'),
                'type'  => PMA_ENGINE_DETAILS_TYPE_SIZE,
            ),
            'myisam_repair_threads' => array(
                'title' => __('Repair threads'),
                'desc'  => __('If this value is greater than 1, MyISAM table indexes are created in parallel (each index in its own thread) during the repair by sorting process.'),
                'type'  => PMA_ENGINE_DETAILS_TYPE_NUMERIC,
            ),
            'myisam_sort_buffer_size' => array(
                'title' => __('Sort buffer size'),
                'desc'  => __('The buffer that is allocated when sorting MyISAM indexes during a REPAIR TABLE or when creating indexes with CREATE INDEX or ALTER TABLE.'),
                'type'  => PMA_ENGINE_DETAILS_TYPE_SIZE,
            ),
            'myisam_stats_method' => array(
            ),
            'delay_key_write' => array(
            ),
            'bulk_insert_buffer_size' => array(
                'type'  => PMA_ENGINE_DETAILS_TYPE_SIZE,
            ),
            'skip_external_locking' => array(
            ),
        );
    }
}

?>




