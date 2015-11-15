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

class Ffuenf_Common_Helper_Core extends Mage_Core_Helper_Abstract
{
    const CONFIG_EXTENSION_LOG_ACTIVE = 'ffuenf_common/log/enable';
    const CONFIG_EXTENSION_LOG_FILE = 'ffuenf_common/log/file';
    const CONFIG_EXTENSION_LOG_FORCE = 'ffuenf_common/log/force';

    /**
     * Get a store flag value and set to against the object.
     *
     * @param string $sStoreFlagPath
     * @param string $sStoreFlagAttribute
     *
     * @return bool
     */
    public function getStoreFlag($sStoreFlagPath, $sStoreFlagAttribute)
    {
        return (bool) $this->getStoreConfig($sStoreFlagPath, $sStoreFlagAttribute);
    }
    /**
     * Get a store config value and set against the object.
     *
     * @param string $sStoreConfigPath
     * @param string $sStoreConfigAttribute
     *
     * @return string
     */
    public function getStoreConfig($sStoreConfigPath, $sStoreConfigAttribute)
    {
        if ($this->$sStoreConfigAttribute === null) {
            $this->$sStoreConfigAttribute = Mage::getStoreConfig($sStoreConfigPath);
        }

        return $this->$sStoreConfigAttribute;
    }

    /**
     * Logs the given message in the specified log file..
     *
     * @param  mixed $message Log Message
     * @return Ffuenf_Common_Helper_Core
     */
    public function log($message)
    {
        $logFile = Mage::getStoreConfig(self::CONFIG_EXTENSION_LOG_FILE);
        $forceLog = Mage::getStoreConfigFlag(self::CONFIG_EXTENSION_LOG_FORCE);
        if ($logFile && strlen($logFile) > 0) {
            Mage::log($message, Zend_Log::DEBUG, $logFile, $forceLog);
        }
        
        return $this;
    }
}
