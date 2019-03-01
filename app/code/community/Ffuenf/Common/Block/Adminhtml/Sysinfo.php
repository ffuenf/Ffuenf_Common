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

class Ffuenf_Common_Block_Adminhtml_Sysinfo extends Mage_Adminhtml_Block_Template
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('ffuenf/common/sysinfo.phtml');
    }

    protected function _prepareLayout()
    {
        $accordion = $this->getLayout()->createBlock('adminhtml/widget_accordion')->setId('ffuenfCommonSysinfo');
        $accordion->addItem(
            'general',
            array(
                'title'   => Mage::helper('ffuenf_common')->__('General Info'),
                'content' => $this->getLayout()->createBlock('ffuenf_common/adminhtml_sysinfo_section')->setSysinfoArea('general')->toHtml()
            )
        );
        $accordion->addItem(
            'stores',
            array(
                'title'   => Mage::helper('ffuenf_common')->__('Stores'),
                'content' => $this->getLayout()->createBlock('ffuenf_common/adminhtml_sysinfo_section_table')->setSysinfoArea('stores')->toHtml()
            )
        );
        $accordion->addItem(
            'magento_general',
            array(
                'title'   => Mage::helper('ffuenf_common')->__('Magento Settings'),
                'content' => $this->getLayout()->createBlock('ffuenf_common/adminhtml_sysinfo_section_table')->setSysinfoArea('magento_general')->toHtml()
            )
        );
        $accordion->addItem(
            'cronjobs',
            array(
                'title'   => Mage::helper('ffuenf_common')->__('Cronjobs'),
                'content' => $this->getLayout()->createBlock('ffuenf_common/adminhtml_sysinfo_section_table')->setSysinfoArea('cronjobs')->setShowKeys(false)->toHtml()
            )
        );
        $accordion->addItem(
            'magento_patches',
            array(
                'title'   => Mage::helper('ffuenf_common')->__('Installed Magento Patches'),
                'content' => $this->getLayout()->createBlock('ffuenf_common/adminhtml_sysinfo_section_table')->setSysinfoArea('magento_patches')->setShowKeys(false)->toHtml()
            )
        );
        $accordion->addItem(
            'magento_extensions',
            array(
                'title'   => Mage::helper('ffuenf_common')->__('Installed Magento extensions'),
                'content' => $this->getLayout()->createBlock('ffuenf_common/adminhtml_sysinfo_section')->setSysinfoArea('magento_extensions')->toHtml()
            )
        );
        $accordion->addItem(
            'php_modules',
            array(
                'title'   => Mage::helper('ffuenf_common')->__('Installed PHP modules'),
                'content' => $this->getLayout()->createBlock('ffuenf_common/adminhtml_sysinfo_section')->setSysinfoArea('php_modules')->toHtml()
            )
        );
        $this->setChild('sysinfo_data', $accordion);
        return parent::_prepareLayout();
    }

    public function getDownloadUrl()
    {
        return $this->getUrl('*/*/download');
    }
}
