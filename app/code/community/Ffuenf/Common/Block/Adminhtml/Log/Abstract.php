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

abstract class Ffuenf_Common_Block_Adminhtml_Log_Abstract extends Mage_Adminhtml_Block_Widget_Grid_Container
{
    protected $_logType = null;

    public function __construct()
    {
        parent::__construct();
        $this->_blockGroup = 'ffuenf_common';
        $this->_controller = 'adminhtml_log_'.$this->_logType;
        $this->_removeButton('add');
    }

    public function getHeaderCssClass()
    {
        return 'head-ffuenf-log '.parent::getHeaderCssClass();
    }
}
