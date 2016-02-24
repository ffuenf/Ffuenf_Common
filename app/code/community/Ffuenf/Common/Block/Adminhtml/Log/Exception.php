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

class Ffuenf_Common_Block_Adminhtml_Log_Exception extends Ffuenf_Common_Block_Adminhtml_Log_Abstract
{
    protected $_logType = 'exception';

    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_log_exception';
        $this->_headerText = Mage::helper('ffuenf_common')->__('Ffuenf Exceptions');
    }
}
