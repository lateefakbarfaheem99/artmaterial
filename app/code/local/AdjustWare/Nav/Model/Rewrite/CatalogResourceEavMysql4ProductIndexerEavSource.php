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
class AdjustWare_Nav_Model_Rewrite_CatalogResourceEavMysql4ProductIndexerEavSource extends Mage_Catalog_Model_Resource_Eav_Mysql4_Product_Indexer_Eav_Source
{
    /** Reindex updated product children also with configurable update
     * 
     * @author ksenevich@aitoc.com
     */
    public function reindexEntities($processIds)
    {
        if (!is_array($processIds)) 
        {
            $processIds = array($processIds);
        }

        $childIds = $this->getRelationsByParent($processIds);
        if ($childIds) 
        {
            $processIds = array_unique(array_merge($processIds, $childIds));
        }

        return parent::reindexEntities($processIds);
    }
}