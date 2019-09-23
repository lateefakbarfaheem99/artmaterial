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
class AdjustWare_Nav_Block_Catalogsearch_Layer extends AdjustWare_Nav_Block_Catalog_Layer_View
{
    public function getLayer()
    {
        return Mage::getSingleton('catalogsearch/layer');
    }

    /**
     * Check availability display layer block
     *
     * @return bool
     */
    public function canShowBlock()
    {
        $availableResCount = (int) Mage::app()->getStore()
            ->getConfig(Mage_CatalogSearch_Model_Layer::XML_PATH_DISPLAY_LAYER_COUNT );

        if (!$availableResCount
            || ($availableResCount>=$this->getLayer()->getProductCollection()->getSize())) {
            return parent::canShowBlock();
        }
        return false;
    }   
}