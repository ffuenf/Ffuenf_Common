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

class Ffuenf_Common_Model_Log_Collection extends Varien_Data_Collection
{

    protected $_logType = 'exception';

    /**
     * @return Ffuenf_Common_Model_Config
     */
    protected function _getConfig()
    {
        return Mage::getSingleton('ffuenf_common/config');
    }

    /**
     * @return null|array
     */
    protected function _applyFilters($log)
    {
        if (!empty($this->_filters)) {
            foreach ($log as $field => $value) {
                if ($filter = $this->getFilter($field)) {
                    $filterValue = $filter->getValue();
                    if (isset($filterValue['like'])) {
                        if (strpos(strtolower($value), strtolower(trim((string)($filterValue['like']), ' %\''))) === false) {
                            return null;
                        }
                    }
                }
            }
        }
        return $log;
    }

    protected function _loadData()
    {
        if ($this->isLoaded()) {
            return $this;
        }
        $io            = new Varien_Io_File();
        $logFileName   = Ffuenf_Common_Model_Logger::getLogFileName($this->_logType);
        $logDirPath    = Ffuenf_Common_Model_Logger::getAbsoluteLogDirPath();
        $logFilePath   = Ffuenf_Common_Model_Logger::getAbsoluteLogFilePath($this->_logType);
        $columnMapping = Ffuenf_Common_Model_Logger::getColumnMapping($this->_logType);
        if ($io->fileExists($logFilePath, true)) {
            $logArray = array();
            $id = 0;
            $io->open(array('path' => $logDirPath));
            $io->streamOpen($logFileName, 'r');
            while (false !== ($row = $io->streamReadCsv($this->_getConfig()->getLogDelimiter(), $this->_getConfig()->getLogEnclosure()))) {
                $log = array('id' => ++$id);
                foreach ($columnMapping as $index => $columnName) {
                    $log[$columnName] = isset($row[$index]) ? $row[$index] : '';
                }
                if ($log = $this->_applyFilters($log)) {
                    $logArray[] = new Varien_Object($log);
                }
            }
            if (!empty($logArray)) {
                krsort($logArray);
                $this->_totalRecords = count($logArray);
                $this->_setIsLoaded();
                $from = ($this->getCurPage() - 1) * $this->getPageSize();
                $to = $from + $this->getPageSize() - 1;
                $isPaginated = $this->getPageSize() > 0;
                $count = 0;
                foreach ($logArray as $log) {
                    $count++;
                    if ($isPaginated && ($count < $from || $count > $to)) {
                        continue;
                    }
                    $this->addItem($log);
                }
            }
        }
        return $this;
    }

    protected function _sortArray()
    {
        if (!empty($this->_items)) {
            krsort($this->_items);
        }
        return $this;
    }

    /**
     * @param mixed $attribute
     * @param mixed $condition
     */
    public function addAttributeToFilter($attribute, $condition = null)
    {
        $this->addFilter($attribute, $condition);
        return $this;
    }

    /**
     * @param mixed $attribute
     * @param mixed $condition
     */
    public function addFieldToFilter($attribute, $condition = null)
    {
        return $this->addAttributeToFilter($attribute, $condition);
    }

    public function loadData($printQuery = false, $logQuery = false)
    {
        return $this->_loadData();
    }

    public function setLogType($logType)
    {
        $this->_logType = $logType;
        return $this;
    }
}
