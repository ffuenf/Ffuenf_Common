<?php
/**
 * Ffuenf_Common extension.
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the MIT License
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/mit-license.php
 *
 * @category   Ffuenf
 *
 * @author     Achim Rosenhagen <a.rosenhagen@ffuenf.de>
 * @copyright  Copyright (c) 2015 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_Common_Helper_File extends Mage_Core_Helper_Abstract
{
    /**
     * Method to check to see if a file exists or not
     *
     * @param string $file
     * @return bool
     */
    public function exists($file)
    {
        $validator = new Zend_Validate_File_Exists();
        if ($validator->isValid($file) == true) {
            return true;
        }
        return false;
    }

    /**
     * Method to check to see if a file is writable or not
     *
     * @param $file
     *
     * @return bool
     */
    public function isWritable($file)
    {
        $fileIo = new Varien_Io_File;
        return $fileIo->isWriteable($file);
    }

    /**
     * Method to check to see if a file is writable or not
     *
     * @param $file
     *
     * @return bool
     */
    public function isWritableDir($file)
    {
        $fileIo = new Varien_Io_File;
        $fileHandler = new Varien_File_Object($file);
        return $fileIo->isWriteable($fileHandler->getDirName());
    }

    /**
     * Method to return the modification time of a file
     *
     * @param $file
     *
     * @return int
     */
    public function getModificationTime($file)
    {
        $fileHandler = new Varien_File_Object($file);
        return $fileHandler->getCTime();
    }

    /**
     * Method to check if a $file1 is newer than a $file2
     *
     * @param $file1
     * @param $file2
     *
     * @return bool
     */
    public function isNewerThan($file1, $file2)
    {
        $file1ModificationTime = $this->getModificationTime($file1);
        $file2ModificationTime = $this->getModificationTime($file2);
        if($file1ModificationTime > $file2ModificationTime) {
            return true;
        }
        return false;
    }

    /**
     * Method to check if a $file is greater than $maxFilesize
     *
     * @param $file
     * @param $maxFilesize
     *
     * @return bool
     */
    public function isBiggerThan($file, $maxFilesize)
    {
        $testMaxFilesize = new Zend_Validate_File_Size(array(
            'min' => $maxFilesize
        ));
        if ($testMaxFilesize->isValid($file)) {
            return true;
        }
        return false;
    }
}
