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

class Ffuenf_Common_Model_Adminhtml_System_Config_Source_Loglevel
{
    /**
     * Options getter.
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            Zend_Log::EMERG  => Mage::helper('ffuenf_common')->__('Emergency'),
            Zend_Log::ALERT  => Mage::helper('ffuenf_common')->__('Alert'),
            Zend_Log::CRIT   => Mage::helper('ffuenf_common')->__('Critical'),
            Zend_Log::ERR    => Mage::helper('ffuenf_common')->__('Error'),
            Zend_Log::WARN   => Mage::helper('ffuenf_common')->__('Warning'),
            Zend_Log::NOTICE => Mage::helper('ffuenf_common')->__('Notice'),
            Zend_Log::INFO   => Mage::helper('ffuenf_common')->__('Informational'),
            Zend_Log::DEBUG  => Mage::helper('ffuenf_common')->__('Debug')
        );
    }
}
