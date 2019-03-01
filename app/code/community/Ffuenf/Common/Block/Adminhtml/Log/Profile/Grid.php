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
                'width'  => '150px',
                'renderer' => 'Ffuenf_Common_Block_Adminhtml_Renderer_Timestamp'
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
                'header'   => Mage::helper('ffuenf_common')->__('Type'),
                'index'    => 'type'
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
            'duration',
            array(
                'header' => Mage::helper('ffuenf_common')->__('Duration'),
                'index'  => 'duration'
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
        return parent::_prepareColumns();
    }
}
