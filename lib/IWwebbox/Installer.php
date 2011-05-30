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

    public function upgrade($oldversion) {


        $prefix = $GLOBALS['ZConfig']['System']['prefix'];

        //Rename table
        if (!DBUtil::renameTable('iw_webbox', 'IWwebbox'))
            return false;

        // Update z_blocs table
        //Suposo que els noms de les columnes de la taula z_blocks ja han estat actualitzats

        $c = "UPDATE {$prefix}_blocks SET z_bkey = 'Webbox' WHERE z_bkey = 'webbox'";
        if (!DBUtil::executeSQL($c)) {
            return false;
        }
        
        // Update module_vars table
        
        // Array de d'arrays de parells [name][value]
        $oldVars = DBUtil::selectObjectArray("module_vars", "`z_modname` = 'iw_webbox'", '', -1, -1, '', null, null, array('name', 'value'));

        //Array de noms
        $oldVarsNames = DBUtil::selectFieldArray("module_vars", 'name', "`z_modname` = 'iw_webbox'", '', false, '');

        $newVarsNames = Array('url', 'width', 'height', 'scrolls', 'widthunit');

        $newVars = Array('url' => 'http://phobos.xtec.cat/intraweb',
            'width' => '100',
            'height' => '600',
            'scrolls' => '1',
            'widthunit', '%');


        //Delete unneeded vars and update the rest
        foreach ($oldVarsNames as $old) {
            // echo ($old . '<br>');
            ModUtil::delVar('iw_webbox', $old);
            if ($newVars[$old]) {
                //     echo ($old . ' ' . $newVars[$old]);
                $this->addVar($old, $oldVars[$old]);
            }
        }

        //Add new vars
        $add = array_diff($newVarsNames, $oldVarsNames);
        foreach ($add as $i) {
            //  echo($i . ': ' . 'afegida '/*$newVars[$i]*/);
            $this->setVar($i, $newVars[$i]);
        }
        
        
        return true;
    }

}