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
class AdjustWare_Nav_Model_Mysql4_Catalog_Layer_Filter_Decimal extends Mage_Catalog_Model_Resource_Eav_Mysql4_Layer_Filter_Decimal
{
    public function applyPriceRange($filter)
    {
        return $this;
    }
}