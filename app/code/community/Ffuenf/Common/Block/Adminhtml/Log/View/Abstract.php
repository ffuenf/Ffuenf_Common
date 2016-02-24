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

class Ffuenf_Common_Block_Adminhtml_Log_View_Abstract extends Mage_Adminhtml_Block_Widget_Container
{

    /**
     * Instance of the log model
     *
     * @var Varien_Object $_model
     */
    protected $_model = null;

    public function __construct()
    {
        parent::__construct();
        $this->_addButton(
            'back',
            array(
                'label'   => Mage::helper('adminhtml')->__('Back'),
                'onclick' => 'window.location.href=\'' . $this->getUrl('*/*/') . '\'',
                'class'   => 'back',
            )
        );
    }

    /**
     * Returns log model instance
     *
     * @return Varien_Object
     */
    protected function _getLog()
    {
        return $this->_model;
    }

    /**
     * Converts field names for geters
     *
     * @param string $name
     * @return string
     */
    protected function _underscore($name)
    {
        return strtolower(preg_replace('/(.)([A-Z])/', "$1_$2", $name));
    }

    public function __call($method, $args)
    {
        if (null !== $this->_getLog()) {
            if (substr($method, 0, 3) == 'get') {
                $key = $this->_underscore(substr($method, 3));
                return $this->_getLog()->getData($key);
            }
        }
    }

    public function getTimestamp()
    {
        if (null !== $this->_getLog()) {
            return Mage::app()->getLocale()->date($this->_getLog()->getTimestamp());
        }
        return null;
    }

    public function setLog($model)
    {
        $this->_model = $model;
        return $this;
    }

    public function getHeaderCssClass()
    {
        return 'icon-head head-ffuenf-log ' . parent::getHeaderCssClass();
    }
}
