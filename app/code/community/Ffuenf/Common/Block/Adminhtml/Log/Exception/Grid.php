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

class Ffuenf_Common_Block_Adminhtml_Log_Exception_Grid extends Mage_Adminhtml_Block_Widget_Grid
{
    public function __construct()
    {
        parent::__construct();
        $this->setId('ffuenf_common_log_exception_grid');
        $this->setFilterVisibility(false);
        $this->setSaveParametersInSession(true);
    }

    protected function _prepareCollection()
    {
        $collection = Mage::getModel('ffuenf_common/log_collection')->setLogType('exception');
        $this->setCollection($collection);
        return parent::_prepareCollection();
    }

    protected function _prepareColumns()
    {
        $this->addColumn('timestamp', array(
            'header'        => Mage::helper('ffuenf_common')->__('Date'),
            'index'         => 'timestamp',
            'type'          => 'datetime',
            'width'         => '150px',
            'renderer'      => 'Ffuenf_Common_Block_Adminhtml_Renderer_Timestamp',
            'filter'        => false,
            'sortable'      => false
        ));
        $this->addColumn('exception_message', array(
            'header'        => Mage::helper('ffuenf_common')->__('Exception message'),
            'index'         => 'exception_message',
            'filter'        => false,
            'sortable'      => false
        ));
        $this->addColumn('exception_code', array(
            'header'        => Mage::helper('ffuenf_common')->__('Exception code'),
            'index'         => 'exception_code',
            'align'         => 'center',
            'width'         => '50px',
            'filter'        => false,
            'sortable'      => false
        ));
        $this->addColumn('preview_action', array(
            'header'    => Mage::helper('ffuenf_common')->__('Details'),
            'type'      => 'action',
            'align'     => 'center',
            'width'     => '50px',
            'getter'    => 'getId',
            'actions'   => array(
                array(
                    'caption' => Mage::helper('ffuenf_common')->__('Details'),
                    'url'     => array('base' => '*/*/view'),
                    'field'   => 'id'
                )
            ),
            'filter'    => false,
            'sortable'  => false,
            'is_system' => true
        ));
        return parent::_prepareColumns();
    }

    public function getRowUrl($row)
    {
        return $this->getUrl('*/*/view', array('id' => $row->getId()));
    }

    public function getHeaderCssClass()
    {
        return 'head-ffuenf-icon';
    }
}
