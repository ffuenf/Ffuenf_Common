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

abstract class Ffuenf_Common_Controller_AbstractController extends Mage_Adminhtml_Controller_Action
{
    const LOG_TYPE   = 'system';
    const TITLE_PATH = 'System';

    /**
     * @return Ffuenf_Common_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('ffuenf_common/config');
    }

    /**
     * @return Ffuenf_Common_Model_Log_Collection
     */
    protected function _getCollection()
    {
        return Mage::getModel('ffuenf_common/log_collection')->setLogType(self::LOG_TYPE);
    }

    protected function _initAction()
    {
        $this->loadLayout()
            ->_setActiveMenu('system/ffuenf/log/' . self::LOG_TYPE)
            ->_addBreadcrumb($this->__('Ffuenf'), $this->__('Ffuenf'))
            ->_addBreadcrumb($this->__('Logs'), $this->__('Logs'))
            ->_addBreadcrumb($this->__(self::TITLE_PATH), $this->__(self::TITLE_PATH));
        return $this;
    }

    public function indexAction()
    {
        $this->_title($this->__('Ffuenf'))->_title($this->__('Logs'))->_title($this->__(self::TITLE_PATH));
        $this->_initAction()
            ->renderLayout();
    }

    public function viewAction()
    {
        $id = $this->getRequest()->getParam('id');
        $log = $this->_getCollection()->getItemById($id);
        if (is_object($log) && $log->getId()) {
            $this->_title($this->__('Ffuenf'))->_title($this->__('Logs'))->_title($this->__(self::TITLE_PATH))->_title($this->__('Details'));
            $this->_initAction();
            $this->_addContent($this->getLayout()->createBlock('ffuenf_common/adminhtml_log_' . self::LOG_TYPE . '_view')->setLog($log));
            $this->renderLayout();
        } else {
            Mage::getSingleton('adminhtml/session')->addError(Mage::helper('ffuenf_common')->__('Log does not exist'));
            $this->_redirect('*/*/');
        }
    }
    
    /**
     * Export log grid to CSVi format
     */
    public function exportCsvEnhancedAction()
    {
        $fileName   = self::LOG_TYPE . '-' . gmdate('YmdHis') . '.csv';
        $grid = $this->getLayout()->createBlock('ffuenf_common/adminhtml_log_' . self::LOG_TYPE . '_grid');
        $this->_prepareDownloadResponse($fileName, $grid->getCsvFileEnhanced());
    }

    public function downloadAction()
    {
        $io            = new Varien_Io_File();
        $logFileName   = Ffuenf_Common_Model_Logger::getLogFileName(self::LOG_TYPE);
        $logDirPath    = Ffuenf_Common_Model_Logger::getAbsoluteLogDirPath();
        $logFilePath   = Ffuenf_Common_Model_Logger::getAbsoluteLogFilePath(self::LOG_TYPE);
        $columnMapping = Ffuenf_Common_Model_Logger::getColumnMapping(self::LOG_TYPE);
        $logDelimiter  = $this->_getConfig()->getLogDelimiter();
        $logEnclosure  = $this->_getConfig()->getLogEnclosure();
        if ($io->fileExists($logFilePath, true)) {
            $io->open(array('path' => $logDirPath));
            $output = implode($logDelimiter, $columnMapping) . "\n";
            $io->streamOpen($logFileName, 'r');
            $fileOutput = array();
            $id = 0;
            while (false !== ($row = $io->streamReadCsv($logDelimiter, $logEnclosure))) {
                $fileOutput = array('id' => ++$id);
                foreach ($columnMapping as $index => $columnName) {
                    $fileOutput[$columnName] = isset($row[$index]) ? $row[$index] : '';
                }
            }
            $filesize = $io->streamStat('size');
            $io->close();
            $output .= implode($this->_getConfig()->getLogDelimiter(), $fileOutput) . "\n";
            Mage::app()->getResponse()->setHeader('Content-type', 'text/csv');
            Mage::app()->getResponse()->setHeader('Content-disposition', 'attachment;filename=' . $logFileName);
            Mage::app()->getResponse()->setHeader('Content-Length', $filesize);
            Mage::app()->getResponse()->setBody($output);
        } else {
            $this->_redirect('*/*/');
        }
    }

    /**
     * ACL checking
     *
     * @return bool
     */
    protected function _isAllowed()
    {
        return Mage::getSingleton('admin/session')->isAllowed('system/ffuenf/log');
    }
}
