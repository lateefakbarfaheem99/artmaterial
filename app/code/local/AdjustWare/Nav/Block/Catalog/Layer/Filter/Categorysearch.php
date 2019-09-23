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
class AdjustWare_Nav_Block_Catalog_Layer_Filter_Categorysearch extends AdjustWare_Nav_Block_Catalog_Layer_Filter_Category
{
    public function __construct()
    {
        parent::__construct();
        $this->setTemplate('adjnav/filter/category_search.phtml');
        $this->_filterModelName = 'adjnav/catalog_layer_filter_categorysearch'; 
    }
}