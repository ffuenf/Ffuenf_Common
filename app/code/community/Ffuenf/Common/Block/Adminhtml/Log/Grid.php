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

class Ffuenf_Common_Block_Adminhtml_Log_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    /**
     * Type of log
     *
     * @var string
     */
    protected $_logType;

    /**
     * Rows per page for export
     *
     * @var int
     */
    protected $_exportPageSize = 500;

    public function __construct()
    {
        parent::__construct();
        $this->setId('ffuenf_common_log_'.$this->_logType.'_grid');
        $this->setDefaultSort('timestamp');
        $this->setDefaultDir('desc');
        $this->setFilterVisibility(true);
        $this->setSaveParametersInSession(true);
    }

    /**
     * Prepare grid collection object
     *
     * @return Ffuenf_Common_Model_Log_Collection
     */
    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ffuenf_common/log_collection')->setLogType($this->_logType);
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    /**
     * Prepare grid columns object
     *
     * @return Mage_Adminhtml_Block_Widget_Grid
     */
    protected function _prepareColumns()
    {
        $this->addExportType('*/*/exportCsvEnhanced', Mage::helper('ffuenf_common')->__('CSVe'));
        return parent::_prepareColumns();
    }

    /**
     * Get URL to detail view
     *
     * @return string
     */
    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getId()));
    }

    /**
     * Get css class for header
     *
     * @return string
     */
    public function getHeaderCssClass()
    {
        return 'head-ffuenf-icon';
    }

    /**
     * Get grid csv download
     *
     * @return array<string,string|boolean>
     */
    public function getCsvFileEnhanced()
    {
        $this->_isExport = true;
        $this->_prepareGrid();
        $io   = new Varien_Io_File();
        $path = Mage::getBaseDir('var').DS.'export'.DS;
        $name = md5(microtime());
        $file = $path.DS.$name.'.csv';
        while (file_exists($file)) {
            sleep(1);
            $name = md5(microtime());
            $file = $path.DS.$name.'.csv';
        }
        $io->setAllowCreateFolders(true);
        $io->open(array('path' => $path));
        $io->streamOpen($file, 'w+');
        $io->streamLock(true);
        $io->streamWriteCsv($this->_getExportHeaders());
        $this->_exportIterateCollectionEnhanced('_exportCsvItem', array($io));
        if ($this->getCountTotals()) {
            $io->streamWriteCsv($this->_getExportTotals());
        }
        $io->streamUnlock();
        $io->streamClose();
        return array(
            'type'  => 'filename',
            'value' => $file,
            'rm'    => true
        );
    }

    /**
     * @param string $callback
     * @param array $args
     */
    public function _exportIterateCollectionEnhanced($callback, array $args)
    {
        $originalCollection = $this->getCollection();
        $count = null;
        $page  = 1;
        $lPage = null;
        $break = false;
        $ourLastPage = 10;
        while ($break !== true) {
            $collection = clone $originalCollection;
            $collection->setPageSize($this->_exportPageSize);
            $collection->setCurPage($page);
            $collection->load();
            if (is_null($count)) {
                $count = $collection->getSize();
                $lPage = $collection->getLastPageNumber();
            }
            if ($lPage == $page || $ourLastPage == $page) {
                $break = true;
            }
            $page++;
            foreach ($collection as $item) {
                call_user_func_array(array($this, $callback), array_merge(array($item), $args));
            }
        }
    }
}
