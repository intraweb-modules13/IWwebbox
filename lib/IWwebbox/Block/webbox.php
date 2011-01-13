<?php

/**
 * initialise block
 *
 * @author		Albert Pérez Monfort (intraweb)
 */
function IWwebbox_webboxblock_init() {
    // Security
    SecurityUtil::registerPermissionSchema('IWwebbox:webboxBlock:', 'Block title::');
}

/**
 * get information on block
 *
 * @author       Albert P�rez Monfort (intraweb@xtec.cat)
 * @return       array       The block information
 */
function IWwebbox_webboxblock_info() {
    // Values
    return array('text_type' => 'Webbox',
                 'func_edit' => 'webbox_edit',
                 'func_update' => 'webbox_update',
                 'module' => 'IWwebbox',
                 'text_type_long' => 'Contenidor de pàgines web',
                 'allow_multiple' => true,
                 'form_content' => false,
                 'form_refresh' => false,
                 'show_preview' => true);
}

/**
 * update block settings
 *
 * @author       Albert P�rez Monfort (intraweb@xtec.cat)
 * @param        array       $blockinfo     a blockinfo structure
 * @return       $blockinfo  the modified blockinfo structure
 */
function webbox_update($row) {
    $vars['weburlvalue'] = FormUtil::getPassedValue('weburlvalue', -1, 'POST');
    $vars['widthvalue'] = FormUtil::getPassedValue('widthvalue', -1, 'POST');
    $vars['heightvalue'] = FormUtil::getPassedValue('heightvalue', -1, 'POST');
    $vars['titlevalue'] = FormUtil::getPassedValue('titlevalue', -1, 'POST');
    $vars['notunregvalue'] = FormUtil::getPassedValue('notunregvalue', -1, 'POST');
    $vars['scrollvalue'] = FormUtil::getPassedValue('scrollvalue', -1, 'POST');

    $row['content'] = BlockUtil::varsToContent($vars);
    return $row;
}

/*
  function feeds_displayfeedblock_update($blockinfo)
  {
  $vars['feedid'] = FormUtil::getPassedValue('feedid', 1, 'POST');
  $vars['numitems'] = FormUtil::getPassedValue('numitems', 0, 'POST');
  $vars['displayimage'] = FormUtil::getPassedValue('displayimage', -1, 'POST');

  $blockinfo['content'] = BlockUtil::varsToContent($vars);

  return $blockinfo;
  }
 */

/**
 * modify block settings
 *
 * @author       Albert P�rez Monfort (intraweb@xtec.cat)
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output		the block form
 */
function webbox_edit($row) {
    // Get current content
    $vars = BlockUtil::varsFromContent($row['content']);

    if (!empty($vars['weburlvalue'])) {
        $IWwebbox['weburlvalue'] = $vars['weburlvalue'];
        $IWwebbox['widthvalue'] = $vars['widthvalue'];
        $IWwebbox['heightvalue'] = $vars['heightvalue'];
        $IWwebbox['titlevalue'] = $vars['titlevalue'];
        $IWwebbox['scrollvalue'] = $vars['scrollvalue'];
        $IWwebbox['notunregvalue'] = $vars['notunregvalue'];
    } else {
        $IWwebbox['weburlvalue'] = 'http://';
        $IWwebbox['widthvalue'] = '100';
        $IWwebbox['heightvalue'] = '600';
        $IWwebbox['titlevalue'] = '';
        $IWwebbox['scrollvalue'] = '1';
        $IWwebbox['notunregvalue'] = '';
    }

    $view = Zikula_View::getInstance('IWwebbox');

    $view->assign($IWwebbox);

    // get the block output from the template
    $blockinfo['content'] = $view->fetch('IWwebbox_block_edit.htm');

    // return the rendered block
    return BlockUtil::themeBlock($blockinfo);
}

/**
 * display block
 *
 * @author       Albert P�rez Monfort (intraweb@xtec.cat)
 * @param        array       $blockinfo     a blockinfo structure
 * @return       output      the rendered bock
 */
function IWwebbox_webboxblock_display($row) {
    // Get current content
    $vars = BlockUtil::varsFromContent($row['content']);

    // Security check
    if (!SecurityUtil::checkPermission('IWwebbox:webboxBlock:', $row['title'] . "::", ACCESS_READ)) {
        return false;
    }

    if ($vars['titlevalue'] == 1 && $vars['widthvalue'] > 98) {
        $vars['widthvalue'] = 98;
    }
    if ($vars['scrollvalue'] == 1) {
        $vars['scrolls'] = 'auto';
    } else {
        $vars['scrolls'] = 'no';
    }

    if (($vars['notunregvalue'] == 1 && !UserUtil::isLoggedIn()) || $vars['notunregvalue'] == '-1') {
        if ($vars['widthvalue'] != 0) {
            $output = '<p><iframe src="' . $vars['weburlvalue'] . '" width=' . $vars['widthvalue'] . '% height=' . $vars['heightvalue'] . ' scrolling=' . $scrolls . ' frameborder=0></iframe></p>';


            if ($vars['titlevalue'] == '1') {
                $row['title'] = '';
            }

            // Create output object
            $view = Zikula_View::getInstance('IWwebbox', false);

            // assign the block vars
            $view->assign($vars);

            if (($vars['notunregvalue'] == 1 && !UserUtil::isLoggedIn()) || $vars['notunregvalue'] == '-1') {
                // Populate block info and pass to theme
                $row['content'] = $view->fetch('IWwebbox_block_display.htm');
                return BlockUtil::themeBlock($row);
            }
        }
    }
    return false;
}