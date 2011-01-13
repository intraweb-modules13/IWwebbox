<?php

function smarty_function_iwwebboxmenulinks($params, &$smarty) {
    // set some defaults
    if (!isset($params['start'])) {
        $params['start'] = '[';
    }
    if (!isset($params['end'])) {
        $params['end'] = ']';
    }
    if (!isset($params['seperator'])) {
        $params['seperator'] = '|';
    }
    if (!isset($params['class'])) {
        $params['class'] = 'pn-menuitem-title';
    }

    $webboxmenulinks = "<span class=\"" . $params['class'] . "\">" . $params['start'] . " ";

    if (SecurityUtil::checkPermission('IWwebbox::', "::", ACCESS_ADMIN)) {
        $webboxmenulinks .= "<a href=\"" . DataUtil::formatForDisplayHTML(ModUtil::url('IWwebbox', 'admin', 'main')) . "\">" . __('Show existing URL') . "</a> ";
    }
    if (SecurityUtil::checkPermission('IWwebbox::', "::", ACCESS_ADMIN)) {
        $webboxmenulinks .= $params['seperator'] . " <a href=\"" . DataUtil::formatForDisplayHTML(ModUtil::url('IWwebbox', 'admin', 'newitem')) . "\">" . __('Add new URL') . "</a> ";
    }
    if (SecurityUtil::checkPermission('IWwebbox::', "::", ACCESS_ADMIN)) {
        $webboxmenulinks .= $params['seperator'] . " <a href=\"" . DataUtil::formatForDisplayHTML(ModUtil::url('IWwebbox', 'admin', 'conf')) . "\">" . __('Module configuration') . "</a> ";
    }

    $webboxmenulinks .= $params['end'] . "</span>\n";

    return $webboxmenulinks;
}
