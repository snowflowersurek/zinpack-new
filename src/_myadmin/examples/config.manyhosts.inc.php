<?php

/**
 * This example configuration shows how to configure phpMyAdmin for
 * many hosts that all have identical configuration otherwise. To add
 * a new host, just drop it into $hosts below. Contributed by
 * Matthew Hawkins.
 */

$i=0;
$hosts = array (
    "foo.example.com",
    "bar.example.com",
    "baz.example.com",
    "quux.example.com",
);

foreach ($hosts as $host) {
    $i++;
    $cfg['Servers'][$i]['host']     = $host;
    $cfg['Servers'][$i]['port']     = '';
    $cfg['Servers'][$i]['socket']   = '';
    $cfg['Servers'][$i]['connect_type']     = 'tcp';
    $cfg['Servers'][$i]['extension']        = 'mysql';
    $cfg['Servers'][$i]['compress'] = false;
    $cfg['Servers'][$i]['controluser']      = 'pma';
    $cfg['Servers'][$i]['controlpass']      = 'pmapass';
    $cfg['Servers'][$i]['auth_type']        = 'cookie';
    $cfg['Servers'][$i]['user']     = '';
    $cfg['Servers'][$i]['password'] = '';
    $cfg['Servers'][$i]['only_db']  = '';
    $cfg['Servers'][$i]['verbose']  = '';
    $cfg['Servers'][$i]['pmadb']    = 'phpmyadmin';
    $cfg['Servers'][$i]['bookmarktable'] = 'pma__bookmark';
    $cfg['Servers'][$i]['relation'] = 'pma__relation';
    $cfg['Servers'][$i]['table_info'] = 'pma__table_info';
    $cfg['Servers'][$i]['table_coords'] = 'pma__table_coords';
    $cfg['Servers'][$i]['pdf_pages'] = 'pma__pdf_pages';
    $cfg['Servers'][$i]['column_info'] = 'pma__column_info';
    $cfg['Servers'][$i]['history'] = 'pma__history';
    $cfg['Servers'][$i]['table_uiprefs'] = 'pma__table_uiprefs';
    $cfg['Servers'][$i]['tracking'] = 'pma__tracking';
    $cfg['Servers'][$i]['designer_coords'] = 'pma__designer_coords';
    $cfg['Servers'][$i]['userconfig'] = 'pma__userconfig';
    $cfg['Servers'][$i]['recent'] = 'pma__recent';
}




