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
$installer = $this;
/* @var $installer Mage_Core_Model_Resource_Setup */

$installer->startSetup();

$installer->run('

ALTER TABLE `'.$this->getTable('catalog_eav_attribute').'`
    ADD (
        `adjnav_block_type` varchar(40) DEFAULT \'sidebar\',
        `adjnav_display_type` varchar(40) DEFAULT \'default\'
    );
');

$installer->endSetup();