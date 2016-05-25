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

class Ffuenf_Common_Adminhtml_SysinfoController extends Mage_Adminhtml_Controller_Action
{
    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/ffuenf/sysinfo')
            ->_addBreadcrumb($this->__('Ffuenf'), $this->__('Ffuenf'))
            ->_addBreadcrumb($this->__('System Information'), $this->__('System Information'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Ffuenf'))->_title($this->__('System Information'));
        $this->_initAction()
            ->renderLayout();
    }

    public function downloadAction()
    {
        $sysinfoData = Mage::helper('ffuenf_common/sysinfo')->getSysinfoData();
        $filename = str_replace(array('.', '/', '\\'), array('_'), Mage::getStoreConfig(Mage_Core_Model_Store::XML_PATH_UNSECURE_BASE_URL)).
            '_ffuenf_debug_'.Mage::getModel('core/date')->gmtTimestamp().'.dmp';
        $sysinfoData = base64_encode(serialize($sysinfoData));
        Mage::app()->getResponse()->setHeader('Content-type', 'application/base64');
        Mage::app()->getResponse()->setHeader('Content-disposition', 'attachment;filename='.$filename);
        Mage::app()->getResponse()->setBody($sysinfoData);
    }

    /**
     * check whether the current user is allowed to access this controller
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('ffuenf_common');
    }
}
