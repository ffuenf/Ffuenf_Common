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

class Ffuenf_Common_Block_Adminhtml_System_Extensioninfo extends Mage_Adminhtml_Block_System_Config_Form_Field
{
    protected $_dummyElement;
    protected $_fieldRenderer;
    protected $_values;
    protected $_repoUrl  = 'https://github.com';
    protected $_repoUser = 'ffuenf';

    public function render(Varien_Data_Form_Element_Abstract $element)
    {
        $html    = '';
        $modules = array_keys((array)Mage::getConfig()->getNode('modules')->children());
        sort($modules);
        foreach ($modules as $moduleName) {
            if (strstr($moduleName, 'Ffuenf_')) {
                $html .= $this->_getFieldHtml($element, $moduleName);
            }
        }
        return $html;
    }

    /**
     * @return Varien_Object
     */
    protected function _getDummyElement()
    {
        if (empty($this->_dummyElement)) {
            $this->_dummyElement = new Varien_Object(array('show_in_default' => 1, 'show_in_website' => 1));
        }
        
        return $this->_dummyElement;
    }

    /**
     * @return Mage_Adminhtml_Block_System_Config_Form_Field
     */
    protected function _getFieldRenderer()
    {
        if (empty($this->_fieldRenderer)) {
            $this->_fieldRenderer = Mage::getBlockSingleton('adminhtml/system_config_form_field');
        }
        
        return $this->_fieldRenderer;
    }

    /**
     * @return string
     */
    protected function _getFieldHtml($fieldset, $moduleName)
    {
        $moduleKey = substr($moduleName, strpos($moduleName, '_') + 1);
        $ver = (Mage::getConfig()->getModuleConfig($moduleName)->version);
        $id = $moduleName;
        $extStatus = '<img class="ext-status" src="' . $this->getSkinUrl('images/fam_bullet_success.gif') . '" title="' . $this->__("Installed") . '"/>';
        $moduleName = $extStatus . '<a target="_blank" href="' . $this->_repoUrl . '/' . $this->_repoUser . '/' . $moduleName . '" title="' . $moduleName . '">' . $moduleName . '</a>';
        if ($ver) {
            $field = $fieldset->addField(
                $id,
                'label',
                array(
                    'name'  => $moduleKey,
                    'label' => $moduleName,
                    'value' => $ver,
                )
            )->setRenderer($this->_getFieldRenderer());
            return $field->toHtml();
        }
        return '';
    }
}
