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

class Ffuenf_Common_Block_Adminhtml_System_Config_Fieldset_Teaser extends Mage_Adminhtml_Block_Abstract implements Varien_Data_Form_Element_Renderer_Interface
{
    protected $_template   = 'ffuenf/common/teaser.phtml';
    protected $_moduleName = 'Ffuenf_Common';
    protected $_repoUser   = 'ffuenf';

    /**
     * Render element html
     *
     * @param Varien_Data_Form_Element_Abstract $element
     * @return string
     */
    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        return $this->toHtml();
    }

    /**
     * Render extension name
     *
     * @return string
     */
    public function getExtensionName()
    {
        return $this->_moduleName;
    }

    /**
     * Render version string
     *
     * @return string
     */
    public function getExtensionVersion()
    {
        return (string)Mage::getConfig()->getModuleConfig($this->_moduleName)->version;
    }

    /**
     * Render url to opensource repository
     *
     * @return string
     */
    public function getExtensionRepositoryUrl($repoType = 'github')
    {
        if ($repoType == 'github') {
            return 'https://github.com/'.$this->_repoUser.'/'.$this->_moduleName;
        }
        if ($repoType == 'bitbucket') {
            return 'https://bitbucket.org/'.$this->_repoUser.'/'.$this->_moduleName;
        }
        return '';
    }
}
