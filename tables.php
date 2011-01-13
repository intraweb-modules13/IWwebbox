<?php

/**
 * PostNuke Application Framework
 *
 * @copyright (c) 2002, PostNuke Development Team
 * @link http://www.postnuke.com
 * @version $Id: pntables.php 22139 2007-06-01 10:57:16Z markwest $
 * @license GNU/GPL - http://www.gnu.org/copyleft/gpl.html
 * @package PostNuke_Value_Addons
 * @subpackage Weebbox
 */

/**
 * Define module tables
 * @author Albert Pérez Monfort (intraweb@xtec.cat)
 * @return module tables information
 */
function IWwebbox_tables() {
    // Initialise table array
    $pntable = array();

    // IWwebbox table definition
    $pntable['IWwebbox'] = DBUtil::getLimitedTablename('IWwebbox');
    $pntable['IWwebbox_column'] = array('pid' => 'iw_pid',
                                        'url' => 'iw_url',
                                        'ref' => 'iw_ref',
                                        'scrolls' => 'iw_scrolls',
                                        'description' => 'iw_description',
                                        'width' => 'iw_width',
                                        'height' => 'iw_height',
                                        'widthunit' => 'iw_widthunit');

    $pntable['IWwebbox_column_def'] = array('pid' => "I NOTNULL AUTO PRIMARY",
                                            'url' => "C(255) NOTNULL DEFAULT ''",
                                            'ref' => "C(10) NOTNULL DEFAULT ''",
                                            'scrolls' => "I(1) NOTNULL DEFAULT '1'",
                                            'description' => "C(255) NOTNULL DEFAULT ''",
                                            'width' => "I NOTNULL DEFAULT '100'",
                                            'height' => "I NOTNULL DEFAULT '600'",
                                            'widthunit' => "C(10) NOTNULL DEFAULT '%'");

    ObjectUtil::addStandardFieldsToTableDefinition($pntable['IWwebbox_column'], 'pn_');
    ObjectUtil::addStandardFieldsToTableDataDefinition($pntable['IWwebbox_column_def'], 'iw_');

    // Return the table information
    return $pntable;
}