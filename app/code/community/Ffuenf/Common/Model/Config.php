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

class Ffuenf_Common_Model_Config
{
    const XML_PATH_LOG_ALLOWED_IPS = 'ffuenf_common/log/allowed_ips';
    const XML_PATH_LOG_ACTIVE      = 'ffuenf_common/log/enable';

    protected $_config = array();
    protected $_globalData = null;
    protected $_merchantValues = null;

    public function isLoggingActive($store = null)
    {
        return Mage::getStoreConfigFlag(self::XML_PATH_LOG_ACTIVE, $store);
    }

    public function getLogDelimiter()
    {
        return ';';
    }

    public function getLogEnclosure()
    {
        return '"';
    }

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
                return in_array($currentIp, $allowedIps);
            }
        }
        return true;
    }
}