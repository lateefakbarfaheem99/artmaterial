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
class AdjustWare_Nav_Model_Observer
{
    /** Add filters after all filters have applied for configurable products
     * 
     * @param Varien_Event_Observer $observer
     * @author ksenevich@aitoc.com
     */
    public function onCatalogProductCollectionLoadBefore(Varien_Event_Observer $observer)
    {
        /* @var $versionHelper AdjustWare_Nav_Helper_Version */
        $versionHelper = Mage::helper('adjnav/version');

        /* @var $collection Varien_Data_Collection_Db */
        $collection = $observer->getEvent()->getCollection();
        $adapter    = $collection->getConnection();
		$helper = Mage::helper('adjnav');

        $filterAttributes     = AdjustWare_Nav_Model_Catalog_Layer_Filter_Attribute::getFilterAttributes();
        $filterProducts       = AdjustWare_Nav_Model_Catalog_Layer_Filter_Attribute::getFilterProducts();
        $configurableProducts = array();
        $childByAttribute     = array();
        $child2parent         = array();
        $productModel         = Mage::getModel('catalog/product')->getResource();
        $attributesCount      = count($filterAttributes);

        foreach ($filterAttributes as $attributeId => $attributeValues)
        {
            $configurableQuery = new Varien_Db_Select($adapter);
            $configurableQuery
                ->from(array('e' => $productModel->getTable('catalog/product')), 'entity_id')
                ->join(array('l' => $productModel->getTable($versionHelper->getProductRelationTable())), 'l.parent_id = e.entity_id', array('child_id' => $versionHelper->getProductIdChildColumn()))
                ->join(array('a' => Mage::getResourceModel('adjnav/catalog_product_indexer_configurable')->getMainTable()), 'a.entity_id = l.'.$versionHelper->getProductIdChildColumn(), array())
                ->where('e.type_id = ?', Mage_Catalog_Model_Product_Type::TYPE_CONFIGURABLE)
                ->where('a.store_id = ?', Mage::app()->getStore()->getId())
                ->where('a.attribute_id = ?', $attributeId)
                ->where('a.value IN (?)', $attributeValues)
                ->group(array('e.entity_id', 'l.'.$versionHelper->getProductIdChildColumn(), 'a.store_id'));

            $statement = $adapter->query($configurableQuery);
            while ($row = $statement->fetch(PDO::FETCH_ASSOC))
            {
                $child2parent[$row['child_id']][] = $row['entity_id'];
                $childByAttribute[$row['child_id']][$attributeId] = true;
            }
        }

        foreach ($childByAttribute as $childId => $attributeIds)
        {
            if (count($attributeIds) == $attributesCount)
            {
                $configurableProducts[] = $childId;
                foreach ($child2parent[$childId] as $parentId)
                {
                    $configurableProducts[] = $parentId;
                }
            }
        }

        $configurableProducts = array_unique($configurableProducts);

        $joinTables = $collection->getSelect()->getPart(Zend_Db_Select::FROM);
        foreach ($filterProducts as $attributeId => $filterProducts)
        {
            $alias          = 'attr_index_'.$attributeId;
            $filterProducts = array_merge($filterProducts, $configurableProducts);

            if (empty($filterProducts))
            {
                $filterProducts = array(-1);
            }

            if (isset($joinTables[$alias]))
            {
                $collection->getSelect()->where($alias.'.entity_id IN (?)', $filterProducts);
            }
        }

        AdjustWare_Nav_Model_Catalog_Layer_Filter_Attribute::cleanFilterAttributes();
    }

    public function collectAttributeStats()
    {
        if (!Mage::helper('adjnav/featured')->isAutoRange())
        {
            return false;
        }

        $cron = Mage::getModel('adjnav/cron');

        if (!$cron->canRunJob('collect_attribute_stats'))
        {
            //return false;
        }

        Mage::getModel('adjnav/eav_entity_attribute_option_stat')->recalculateStat();

        $cron->setLastRun(now())->save();
    }

    /** Add Layered navigation pro scripts to brand page
     * 
     * @param Varien_Event_Observer $observer
     * @author ksenevich
     */
    public function onAitmanufacturersRenderAdjnav($observer)
    {
        $action = $observer->getEvent()->getAction();

        //$action->getLayout()->getUpdate()->addHandle('adjnav_head');
        $action->getLayout()->getBlock('head')->addCss('css/adjnav.css');
		$action->getLayout()->getBlock('head')->addCss('css/adjnavtop.css');
		if(Mage::helper('adjnav')->ifBrowserNotFF()) {
            $action->getLayout()->getBlock('head')->addCss('css/adjnavtopIE.css');
        }
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/main.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/control/navigation.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/listener.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/control/block.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/control/pageAutoload.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/control/attribute.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/control/attributeValues.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/control/price.js');
        $action->getLayout()->getBlock('head')->addItem('skin_js', 'js/adjnav/control/toolbar.js');
        $action->getLayout()->getBlock('head')->addJs('jquery/jquery.min.js');
        $action->getLayout()->getBlock('head')->addJs('jquery/aitoc.js');
        $action->getLayout()->getBlock('head')->addJs('jquery/jquery.ba-bbq.min.js');
    }
    
    public function onCoreBlockAbstractToHtmlAfter($observer)
    {
        $block = $observer->getBlock();
        $transport = $observer->getTransport();
        $html = $transport->getHtml();
        
        if ($block instanceof Mage_Catalog_Block_Product_List) 
        {
            $additionalHtml = '';
            if (Mage::helper('adjnav')->isPageAutoload() && 
                ($block->getToolbarBlock()->getLastPageNum() > 1))
            {
                if ($block->getToolbarBlock()->getCurrentPage() < $block->getToolbarBlock()->getLastPageNum())
                { 
                    $additionalHtml .= '<div class="adjnav-page-autoload-pholder"></div>';
                }
                $additionalHtml .= '<div class="adjnav-page-autoload-progress" style="display:none">' .
                    '<img src="' . Mage::getDesign()->getSkinUrl('images/opc-ajax-loader.gif') . '" />' . 
                    Mage::helper('adjnav')->__('Loading...') . 
                '</div>';
                
                $columnCount = false;
                
                $category = Mage::registry('current_category');
                if ($category)
                {
                    if (Aitoc_Aitsys_Abstract_Service::get()->isMagentoVersion('>=1.4'))
                    {
                        $pageLayout = Mage::helper('adjnav')->getPageLayout($category);
                        $columnCount = $block->getColumnCountLayoutDepend($pageLayout);
                    }
                }
                
                if (!$columnCount)
                {
                    $columnCount = $block->getColumnCount('column_count');
                }
                
                $additionalHtml .= '<input id="adjnav-page-column-count" type="hidden" value="' . $columnCount . '"/>';
                $additionalHtml .= '<input id="adjnav-page-product-limit" type="hidden" value="' . $block->getToolbarBlock()->getLimit() . '"/>';
                
            }
            $transport->setHtml($html . $additionalHtml);
        }
    }

    public function onAdminhtmlEttributeEditPrepareForm($observer)
    {
        /** @var $form Varien_Data_Form_Element_Fieldset */
        $form = $observer->getEvent()->getForm()->getElements()->searchById('front_fieldset');

        $form->addField('adjnav_block_type', 'select', array(
            'name'      => 'adjnav_block_type',
            'label'     => Mage::helper('adminhtml')->__('Navigation Block'),
            'title'     => Mage::helper('adminhtml')->__('Navigation Block'),
            'values'    => Mage::getModel('adjnav/system_config_source_blockView')->toOptionArray(),
        ), 'is_filterable');

        $form->addField('adjnav_display_type', 'select', array(
            'name'      => 'adjnav_display_type',
            'label'     => Mage::helper('adminhtml')->__('Display Type in Navigation Block'),
            'title'     => Mage::helper('adminhtml')->__('Display Type in Navigation Block'),
            'values'    => Mage::getModel('adjnav/system_config_source_attributeViewType')->toOptionArray(),
        ), 'adjnav_block_type');
    }
}