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

class Ffuenf_Common_Adminhtml_Log_ExceptionController extends Mage_Adminhtml_Controller_Action
{
    const LOG_TYPE = 'exception';

    protected function _getConfig()
    {
        return Mage::getSingleton('ffuenf_common/config');
    }

    protected function _getCollection()
    {
        return Mage::getModel('ffuenf_common/log_collection')->setLogType(self::LOG_TYPE);
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/ffuenf/log/exception')
            ->_addBreadcrumb($this->__('Ffuenf'), $this->__('Ffuenf'))
            ->_addBreadcrumb($this->__('Logs'), $this->__('Logs'))
            ->_addBreadcrumb($this->__('Exceptions'), $this->__('Exceptions'));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Ffuenf'))->_title($this->__('Logs'))->_title($this->__('Exceptions'));
        $this->_initAction()
            ->renderLayout();
    }

    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $log = $this->_getCollection()->getItemById($id);
        if (is_object($log) && $log->getId()) {
            $this->_title($this->__('Ffuenf'))->_title($this->__('Logs'))->_title($this->__('Exceptions'))->_title($this->__('Details'));
            $this->_initAction();
            $this->_addContent($this->getLayout()->createBlock('ffuenf_common/adminhtml_log_exception_view')->setLog($log));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ffuenf_common')->__('Log does not exist'));
            $this->_redirect('*/*/');
        }
    }

    public function downloadAction()
    {
        $io = new Varien_Io_File();
        $logFileName = Ffuenf_Common_Model_Logger::getLogFileName(self::LOG_TYPE);
        $logDirPath = Ffuenf_Common_Model_Logger::getAbsoluteLogDirPath(self::LOG_TYPE);
        $logFilePath = Ffuenf_Common_Model_Logger::getAbsoluteLogFilePath(self::LOG_TYPE);
        $columnMapping = Ffuenf_Common_Model_Logger::getColumnMapping(self::LOG_TYPE);
        $logDelimiter = $this->_getConfig()->getLogDelimiter();
        $logEnclosure = $this->_getConfig()->getLogEnclosure()
        if ($io->fileExists($logFilePath, true)) {
            $io->open(array('path' => $logDirPath));
            $output = implode($logDelimiter, $columnMapping) . "\n";
            $io->streamOpen($logFileName, 'r');
            while (false !== ($row = $io->streamReadCsv($logDelimiter, $logEnclosure))) {
                $fileOutput = array('id' => ++$id);
                foreach ($columnMapping as $index => $columnName) {
                    $fileOutput[$columnName] = isset($row[$index]) ? $row[$index] : '';
                }
            }
            $io->close();
            $output .= implode($this->_getConfig()->getLogDelimiter(), $fileOutput) . "\n";
            Mage::app()->getResponse()->setHeader('Content-type', 'text/csv');
            Mage::app()->getResponse()->setHeader('Content-disposition', 'attachment;filename=' . $logFileName);
            Mage::app()->getResponse()->setHeader('Content-Length', filesize($logFilePath));
            Mage::app()->getResponse()->setBody($output);
        } else {
            $this->_redirect('*/*/');
        }
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
