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
 * @copyright  Copyright (c) 2018 ffuenf (http://www.ffuenf.de)
 * @license    http://opensource.org/licenses/mit-license.php MIT License
 */

class Ffuenf_Common_Block_Adminhtml_Log_System_Grid extends Ffuenf_Common_Block_Adminhtml_Log_Grid
{
    /**
     * Type of log
     *
     * @var string
     */
    protected $_logType = 'system';

    protected function _prepareColumns()
    {
        $this->addColumn(
            'timestamp',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Date'),
                'index'  => 'timestamp',
                'width'  => '150px',
                'renderer' => 'Ffuenf_Common_Block_Adminhtml_Renderer_Timestamp'
            )
        );
        $this->addColumn(
            'class',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Class'),
                'index'  => 'class',
                'width'  => '200px'
            )
        );
        $this->addColumn(
            'origin',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Origin'),
                'index'  => 'origin',
                'width'  => '200px'
            )
        );
        $this->addColumn(
            'level',
            array(
                'header'   => Mage::helper('ffuenf_common')->__('Level'),
                'index'    => 'level',
                'width'    => '120px',
                'renderer' => 'Ffuenf_Common_Block_Adminhtml_Renderer_Level'
            )
        );
        $this->addColumn(
            'message',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Message'),
                'index'  => 'message'
            )
        );
        $this->addColumn(
            'details',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Details'),
                'index'  => 'details',
                'column_css_class'=>'no-display',
                'header_css_class'=>'no-display'
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
