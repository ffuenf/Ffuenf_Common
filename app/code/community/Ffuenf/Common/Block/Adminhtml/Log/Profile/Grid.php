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

class Ffuenf_Common_Block_Adminhtml_Log_Profile_Grid extends Ffuenf_Common_Block_Adminhtml_Log_Grid
{
    /**
     * Type of log
     *
     * @var string
     */
    protected $_logType = 'profile';

    protected function _prepareColumns()
    {
        $this->addColumn(
            'timestamp',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Date'),
                'index'  => 'timestamp',
                'type'   => 'datetime',
                'format' => Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM, true),
                'width'  => '150px'
            )
        );
        $this->addColumn(
            'class',
            array(
                'header'   => Mage::helper('ffuenf_common')->__('Class'),
                'index'    => 'class',
                'width'  => '200px'
            )
        );
        $this->addColumn(
            'level',
            array(
                'header'   => Mage::helper('ffuenf_common')->__('Level'),
                'index'    => 'level'
            )
        );
        $this->addColumn(
            'items',
            array(
                'header'   => Mage::helper('ffuenf_common')->__('Items'),
                'index'    => 'items'
            )
        );
        $this->addColumn(
            'page',
            array(
                'header'   => Mage::helper('ffuenf_common')->__('Page'),
                'index'    => 'page'
            )
        );
        $this->addColumn(
            'start',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Start Time'),
                'index'  => 'start',
                'type'   => 'date',
                'format' => Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM)
            )
        );
        $this->addColumn(
            'stop',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Stop Time'),
                'index'  => 'stop',
                'type'   => 'date',
                'format' => Mage::app()->getLocale()->getDateTimeFormat(Mage_Core_Model_Locale::FORMAT_TYPE_MEDIUM)
            )
        );
        $this->addColumn(
            'duration',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Duration'),
                'index'  => 'duration',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'memory',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Memory Consumption'),
                'index'  => 'memory',
                'type'   => 'number'
            )
        );
        $this->addColumn(
            'preview_action',
            array(
                'header'  => Mage::helper('ffuenf_common')->__('Details'),
                'type'    => 'action',
                'align'   => 'center',
                'width'   => '50px',
                'getter'  => 'getId',
                'actions' => array(
                    array(
                        'caption' => Mage::helper('ffuenf_common')->__('Details'),
                        'url'     => array('base' => '*/*/view'),
                        'field'   => 'id'
                    )
                ),
                'filter'    => false,
                'sortable'  => false,
                'is_system' => true
            )
        );
        return parent::_prepareColumns();
    }
}
