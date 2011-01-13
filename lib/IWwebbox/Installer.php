<?php
class IWwebbox_Installer extends Zikula_Installer {

    /**
     * Initialise the IWwebbox module creating module tables and module vars
     * @author Albert Pérez Monfort (intraweb@xtec.cat)
     * @return bool true if successful, false otherwise
     */
    public function install() {
        // Create module table
        if (!DBUtil::createTable('IWwebbox')) return false;

        //Create module vars
        ModUtil::setVar('IWwebbox', 'url', 'http://phobos.xtec.cat/intraweb');
        ModUtil::setVar('IWwebbox', 'width', '100');
        ModUtil::setVar('IWwebbox', 'height', '600');
        ModUtil::setVar('IWwebbox', 'scrolls', '1');
        ModUtil::setVar('IWwebbox', 'widthunit', '%');
        return true;
    }

    /**
     * Delete the IWwebbox module
     * @author Albert Pérez Monfort
     * @return bool true if successful, false otherwise
     */
    public function uninstall() {
        // Delete module table
        DBUtil::dropTable('IWwebbox');

        //Delete module vars
        ModUtil::delVar('IWwebbox', 'url');
        ModUtil::delVar('IWwebbox', 'width');
        ModUtil::delVar('IWwebbox', 'height');
        ModUtil::delVar('IWwebbox', 'scrolls');
        ModUtil::delVar('IWwebbox', 'widthunit');

        //Deletion successfull
        return true;
    }

}