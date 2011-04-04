<?php

class IWwebbox_Controller_Admin extends Zikula_AbstractController {
    /**
     * Show the list of references created
     *
     * @author		Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @return		The list the references created
     */
    public function main() {
        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "::", ACCESS_ADMIN)) {
            return LogUtil::registerError($this->__('Sorry! No authorization to access this module.'), 403);
        }

        // Create output object
        $view = Zikula_View::getInstance('IWwebbox', false);

        // Get records from the database
        $IWwebbox = ModUtil::apiFunc('IWwebbox', 'user', 'getall');

        $view->assign('IWwebbox', $IWwebbox);

        // Return the output generated
        return $view->fetch('IWwebbox_admin_main.htm');
    }

    /**
     * Show the from necessari to create a new reference
     *
     * @author		Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @return		The form for create a new reference
     */
    public function newitem() {
        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "::", ACCESS_ADD)) {
            return LogUtil::registerError($this->__('Sorry! No authorization to access this module.'), 403);
        }

        // Create output object
        $view = Zikula_View::getInstance('IWwebbox', false);

        // Return the output generated
        return $view->fetch('IWwebbox_admin_newitem.htm');
    }

    /**
     * Create a new reference
     *
     * @author       Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @param        args	Array with the values post from the formulari
     */
    public function create($args) {
        // Get parameters from whatever input we need
        $webbox = FormUtil::getPassedValue('webbox', isset($args['webbox']) ? $args['webbox'] : null, 'POST');
        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "$pid::", ACCESS_ADD)) {
            return LogUtil::registerPermissionError();
        }
        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('IWwebbox', 'admin', 'main'));
        }
        // get the given reference
        $ref = ModUtil::apiFunc('IWwebbox', 'user', 'getref',
                        array('ref' => $webbox['ref']));
        if ($ref) {
            LogUtil::registerError($this->__('The given reference exists. Choose a different name for the reference.'));
            return System::redirect(ModUtil::url('IWwebbox', 'admin', 'new'));
        }
        // avoid extrange characters in the ref. Only valid az-09
        if (preg_match("/[^A-Za-z0-9]/", $webbox['ref'])) {
            LogUtil::registerError($this->__('The given reference contain no valid characters.'));
            return System::redirect(ModUtil::url('IWwebbox', 'admin', 'new'));
        }
        $lid = ModUtil::apiFunc('IWwebbox', 'admin', 'create', $webbox);
        if ($lid != false) {
            // Success
            LogUtil::registerStatus($this->__('A new URL reference has been created'));
        }
        return System::redirect(ModUtil::url('IWwebbox', 'admin', 'main'));
    }

    /**
     * Show the from necessari to modify a reference
     *
     * @author		Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @param		pid (required)	Id of the reference that have to be modified
     * @return		The form for modify a reference
     */
    public function modify($args) {
        $pid = FormUtil::getPassedValue('pid', isset($args['pid']) ? $args['pid'] : null, 'GET');

        // Get the reference information
        $item = ModUtil::apiFunc('IWwebbox', 'user', 'get',
                                  array('pid' => $pid));
        if (!$item) {
            // Not reference has been found
            return LogUtil::registerError($this->__('No such item found.'), 404);
        }
        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "$pid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }
        // Create output object
        $view = Zikula_View::getInstance('IWwebbox', false);
        // Assign the item
        $view->assign($item);
        // Return the output that has been generated by this function
        return $view->fetch('IWwebbox_admin_edit.htm');
    }

    /**
     * update the reference
     *
     * @author       Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @param        args	The reference values posted from the form
     */
    public function update($args) {
        $webbox = FormUtil::getPassedValue('webbox', isset($args['webbox']) ? $args['webbox'] : null, 'POST');
        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "$pid::", ACCESS_EDIT)) {
            return LogUtil::registerPermissionError();
        }
        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('IWwebbox', 'admin', 'main'));
        }
        // get the given reference
        $ref = ModUtil::apiFunc('IWwebbox', 'user', 'getref',
                                 array('ref' => $webbox['ref']));
        if ($ref && $ref['pid'] != $webbox['pid']) {
            LogUtil::registerError($this->__('The given reference exists. Choose a different name for the reference.'));
            return System::redirect(ModUtil::url('IWwebbox', 'admin', 'main'));
        }
        // avoid extrange characters in the ref. Only valid az-09
        if (preg_match("/[^A-Za-z0-9]/", $webbox['ref'])) {
            LogUtil::registerError($this->__('The given reference contain no valid characters.'));
            return System::redirect(ModUtil::url('IWwebbox', 'admin', 'main'));
        }
        // Update URL reference
        if (ModUtil::apiFunc('IWwebbox', 'admin', 'update', $webbox)) {
            // Success
            LogUtil::registerStatus($this->__('URL reference updated'));
        }

        return System::redirect(ModUtil::url('IWwebbox', 'admin', 'main'));
    }

    /**
     * Delete a reference
     *
     * @author       Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @param        pid	Id of the reference that has to be deleted
     */
    public function delete($args) {
        $pid = FormUtil::getPassedValue('pid', isset($args['pid']) ? $args['pid'] : null, 'REQUEST');
        $confirmation = FormUtil::getPassedValue('confirmation', null, 'POST');

        // Get the URL reference
        $item = ModUtil::apiFunc('IWwebbox', 'user', 'get',
                                  array('pid' => $pid));

        if (!$item) {
            return LogUtil::registerError($this->__('URL not found'), 404);
        }
        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "$pid::", ACCESS_DELETE)) {
            return LogUtil::registerError($this->__('Sorry! No authorization to access this module.'), 403);
        }

        // Check for confirmation.
        if (empty($confirmation)) {
            // No confirmation yet
            // Create output object
            $view = Zikula_View::getInstance('IWwebbox', false);
            // Add a hidden field for the item ID to the output
            $view->assign('pid', $item['pid']);
            //Add the URL reference value
            $view->assign('ref', $item['ref']);
            // Return the output generated
            return $view->fetch('IWwebbox_admin_delete.htm');
        }

        // Confirmed the action
        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('IWwebbox', 'admin', 'main'));
        }

        // delete the faq
        if (ModUtil::apiFunc('IWwebbox', 'admin', 'delete',
                              array('pid' => $pid))) {
            // Success
            LogUtil::registerStatus($this->__('URL reference removed'));
        }

        return System::redirect(ModUtil::url('IWwebbox', 'admin', 'main'));
    }

    /**
     * Modify the configuration of the module
     *
     * @author       Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @return       output       The configuration form
     */
    public function conf() {
        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "::", ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        $item = array('url' => ModUtil::getVar('IWwebbox', 'url'),
                      'width' => ModUtil::getVar('IWwebbox', 'width'),
                      'height' => ModUtil::getVar('IWwebbox', 'height'),
                      'scrolls' => ModUtil::getVar('IWwebbox', 'scrolls'),
                      'widthunit' => ModUtil::getVar('IWwebbox', 'widthunit'));

        // Create output object
        $view = Zikula_View::getInstance('IWwebbox', false);

        // Assign the item
        $view->assign($item);

        return $view->fetch('IWwebbox_admin_config.htm');
    }

    /**
     * Update configuration
     *
     * @author      Albert Pï¿œrez Monfort (intraweb@xtec.cat)
     * @param       The values posted from the form
     */
    public function update_conf($args) {
        // Get the values posted
        $webbox = FormUtil::getPassedValue('webbox', isset($args['webbox']) ? $args['webbox'] : null, 'POST');

        // Security check
        if (!SecurityUtil::checkPermission('IWwebbox::', "::", ACCESS_ADMIN)) {
            return LogUtil::registerPermissionError();
        }

        // Confirm authorisation code
        if (!SecurityUtil::confirmAuthKey()) {
            return LogUtil::registerAuthidError(ModUtil::url('FAQ', 'admin', 'view'));
        }

        // Set module vars
        ModUtil::setVar('IWwebbox', 'url', $webbox['url']);
        ModUtil::setVar('IWwebbox', 'width', $webbox['width']);
        ModUtil::setVar('IWwebbox', 'height', $webbox['height']);
        ModUtil::setVar('IWwebbox', 'scrolls', $webbox['scrolls']);
        ModUtil::setVar('IWwebbox', 'widthunit', $webbox['widthunit']);

        LogUtil::registerStatus($this->__('Done! Module configuration updated.'));

        return System::redirect(ModUtil::url('IWwebbox', 'admin', 'main'));
    }

    /**
     * Show the information about the module
     * @author:     Albert Pï¿œrez Monfort (aperezm@xtec.cat)
     * @return:	The information about this module
     */
    public function module($args) {
        // Create output object
        $view = Zikula_View::getInstance('IWwebbox', false);

        $module = ModUtil::func('IWmain', 'user', 'module_info',
                                 array('module_name' => 'IWwebbox',
                                       'type' => 'admin'));

        $view->assign('module', $module);
        return $view->fetch('IWwebbox_admin_module.htm');
    }
}