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

abstract class Ffuenf_Common_Block_Adminhtml_Log_Abstract extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_logType = null;

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'ffuenf_common';
        $this->_removeButton('add');
    }

    protected function _prepareLayout()
    {
        if (null !== $this->_logType) {
            $logFilePath = Ffuenf_Common_Model_Logger::getAbsoluteLogFilePath($this->_logType);
            if (Mage::helper('ffuenf_common/file')->exists($logFilePath)) {
                $this->_addButton(
                    'download',
                    array(
                        'label'   => $this->_getDownloadButtonLabel(),
                        'onclick' => 'setLocation(\'' . $this->_getDownloadUrl() . '\')',
                        'class'   => 'scalable'
                    ),
                    -1
                );
            } else {
                $this->_addButton(
                    'download',
                    array(
                        'label'    => $this->_getDownloadButtonLabel(),
                        'onclick'  => 'setLocation(\'' . $this->_getDownloadUrl() . '\')',
                        'class'    => 'scalable',
                        'disabled' => true
                    ),
                    -1
                );
            }
        }
        return parent::_prepareLayout();
    }

    protected function _getDownloadButtonLabel()
    {
        return $this->__('Download as CSV');
    }

    protected function _getDownloadUrl()
    {
        return $this->getUrl('*/*/download');
    }

    public function getHeaderCssClass()
    {
        return 'head-ffuenf-log ' . parent::getHeaderCssClass();
    }
}
