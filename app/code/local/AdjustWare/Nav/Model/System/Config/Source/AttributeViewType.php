<?php
/**
 * Layered Navigation Pro
 *
 * @category:    AdjustWare
 * @package:     AdjustWare_Nav
 * @version      2.5.3
 * @license:     xA2TerxHdbbs8SP4CbJ7byvmlmH5ruA3oJQCc2LRFp
 * @copyright:   Copyright (c) 2014 AITOC, Inc. (http://www.aitoc.com)
 */
class AdjustWare_Nav_Model_System_Config_Source_AttributeViewType extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();

        $options[] = array(
            'value'=> 'default',
            'label' => Mage::helper('adjnav')->__('Checkbox')
        );
        $options[] = array(
            'value'=> 'dropdown',
            'label' => Mage::helper('adjnav')->__('Dropdown')
        );
        $options[] = array(
            'value'=> 'container',
            'label' => Mage::helper('adjnav')->__('Checkable Multiselect')
        );

        return $options;
    }
}