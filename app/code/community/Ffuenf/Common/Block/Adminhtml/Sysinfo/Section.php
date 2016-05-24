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
 * @copyright  Copyright (c) 2016 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_Common_Block_Adminhtml_Sysinfo_Section extends Mage_Adminhtml_Block_Template
{
    protected $_id = null;
    protected $_sysinfoArea = 'general';
    protected $_showKeys = true;

    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ffuenf/common/sysinfo/section.phtml');
    }

    public function getSysinfoData()
    {
        return Mage::helper('ffuenf_common/sysinfo')->getSysinfoData($this->_sysinfoArea);
    }

    public function getSectionId()
    {
        if (null === $this->_id) {
            $this->_id = 'ffuenf-common-sysinfo-section-'.uniqid();
        }
        return $this->_id;
    }

    public function setSysinfoArea($sysinfoArea)
    {
        $this->_sysinfoArea = $sysinfoArea;
        return $this;
    }

    public function setShowKeys($showKeys)
    {
        $this->_showKeys = (bool)$showKeys;
        return $this;
    }

    public function showKeys()
    {
        return $this->_showKeys;
    }

    public function formatOutput($value)
    {
        if (false === $value) {
            return 'No';
        }
        if (true === $value) {
            return 'Yes';
        }
        return $value;
    }
}
