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

class Ffuenf_Common_Block_Adminhtml_Log_System_View extends Ffuenf_Common_Block_Adminhtml_Log_View_Abstract
{
    public function __construct()
    {
        parent::__construct();
        $this->_controller = 'adminhtml_log_system';
        $this->_headerText = $this->__('System Log');
        $this->setTemplate('ffuenf/common/log/system/view.phtml');
    }

    public function setLog($model)
    {
        parent::setLog($model);
        if (is_object($model) && $model->getId()) {
            $this->_headerText = $this->__(
                'Message',
                $this->getTimestamp(),
                $this->getExtension(),
                $this->getType(),
                $this->getMessage()
            );
        }
        return $this;
    }

    /**
     * @return string
     */
    public function getLogTypeHtml($typeId)
    {
        $html = Mage::getModel('ffuenf_common/logger')->getLogTypeHtml($typeId);
        return $html;
    }
}
