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
 * @copyright  Copyright (c) 2019 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_Common_Model_Config
{
    const XML_PATH_LOG_ALLOWED_IPS      = 'ffuenf_common/log/allowed_ips';
    const XML_PATH_LOG_SYSTEM_ACTIVE    = 'ffuenf_common/log/enable';
    const XML_PATH_LOG_PROFILE_ACTIVE   = 'ffuenf_common/log/profile_enable';
    const XML_PATH_LOG_EXCEPTION_ACTIVE = 'ffuenf_common/log/exception_enable';

    /**
     * Variable for if IP is allowed.
     *
     * @var bool
     */
    protected $_bLogAllowedIp;

    public function getLogDelimiter()
    {
        return ';';
    }

    public function getLogEnclosure()
    {
        return '"';
    }

    /**
     * Check to see if IP is allowed.
     *
     * @return bool
     */
    public function isCurrentIpAllowed($store = null)
    {
        $allowedIps = trim(Mage::getStoreConfig(self::XML_PATH_LOG_ALLOWED_IPS, $store), ' ,');
        if ($allowedIps) {
            $allowedIps = explode(',', str_replace(' ', '', $allowedIps));
            if (is_array($allowedIps) && !empty($allowedIps)) {
                $currentIp = Mage::helper('core/http')->getRemoteAddr();
                if (Mage::app()->getRequest()->getServer('HTTP_X_FORWARDED_FOR')) {
                    $currentIp = Mage::app()->getRequest()->getServer('HTTP_X_FORWARDED_FOR');
                }
                $this->_bLogAllowedIp = in_array($currentIp, $allowedIps);
                return $this->_bLogAllowedIp;
            }
        }
        return false;
    }

    /**
     * Check to see if logging is active.
     *
     * @return bool
     */
    public function isLogActive()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_LOG_SYSTEM_ACTIVE);
    }

    /**
     * Check to see if profile logging is active.
     *
     * @return bool
     */
    public function isLogProfileActive()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_LOG_PROFILE_ACTIVE);
    }

    /**
     * Check to see if exception logging is active.
     *
     * @return bool
     */
    public function isLogExceptionActive()
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_LOG_EXCEPTION_ACTIVE);
    }
}
