<?php

class IWwebbox_Installer extends Zikula_AbstractInstaller {

    /**
     * Initialise the IWwebbox module creating module tables and module vars
     * @author Albert Pérez Monfort (intraweb@xtec.cat)
     * @return bool true if successful, false otherwise
     */
    public function install() {
        // Create module table
        if (!DBUtil::createTable('IWwebbox'))
            return false;

        //Create module vars
        $this->setVar('url', 'http://phobos.xtec.cat/intraweb')
                ->setVar('width', '100')
                ->setVar('height', '600')
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

    /**
     * Update the IWwebbox module
     * @author Albert Pérez Monfort (aperezm@xtec.cat)
     * @author Jaume Fernàndez Valiente (jfern343@xtec.cat)
     * @return bool true if successful, false otherwise
     */
    public function upgrade($oldversion) {


        $prefix = $GLOBALS['ZConfig']['System']['prefix'];

        //Rename table
        if (!DBUtil::renameTable('iw_webbox', 'IWwebbox'))
            return false;

        // Update z_blocs table

        $c = "UPDATE {$prefix}_blocks SET z_bkey = 'Webbox' WHERE z_bkey = 'webbox'";
        if (!DBUtil::executeSQL($c)) {
            return false;
        }

        // Update module_vars table

        //Update the name (keeps old var value)
        $c = "UPDATE {$prefix}_module_vars SET z_modname = 'IWwebbox' WHERE z_bkey = 'iw_webbox'";
        if (!DBUtil::executeSQL($c)) {
            return false;
        }

        //Array de noms
        $oldVarsNames = DBUtil::selectFieldArray("module_vars", 'name', "`z_modname` = 'IWwebbox'", '', false, '');

        $newVarsNames = Array('url', 'width', 'height', 'scrolls', 'widthunit');

        $newVars = Array('url' => 'http://phobos.xtec.cat/intraweb',
            'width' => '100',
            'height' => '600',
            'scrolls' => '1',
            'widthunit' => '%');    

        // Delete unneeded vars
        $del = array_diff($oldVarsNames, $newVarsNames);
        foreach ($del as $i) {
            $this->delVar($i);
        }

        // Add new vars
        $add = array_diff($newVarsNames, $oldVarsNames);
        foreach ($add as $i) {
            $this->setVar($i, $newVars[$i]);
        }

        return true;
    }

}