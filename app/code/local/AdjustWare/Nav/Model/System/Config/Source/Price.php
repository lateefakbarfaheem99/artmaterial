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
class AdjustWare_Nav_Model_System_Config_Source_Price extends Varien_Object
{
    public function toOptionArray()
    {
        $options = array();
        
        $options[] = array(
                'value'=> 'default',
                'label' => Mage::helper('adjnav')->__('Default')
        );
        $options[] = array(
                'value'=> 'slider',
                'label' => Mage::helper('adjnav')->__('Slider')
        );
        $options[] = array(
                'value'=> 'input',
                'label' => Mage::helper('adjnav')->__('Input')
        );
        
        return $options;
    }
}