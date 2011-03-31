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
        $this->setVar('url', 'http://phobos.xtec.cat/intraweb')
             ->setVar('width', '100')
             ->setVar('scrolls', '1')
             ->setVar('widthunit', '%');
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
        $this->delVar('url')
             ->delVar('width')
             ->delVar('height')
             ->delVar('scrolls')
             ->delVar('widthunit');

        //Deletion successfull
        return true;
    }

    public function  upgrade($oldversion) {
        return true;
    }
}