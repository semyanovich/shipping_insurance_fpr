<?php
/**
 * Created by Magentix
 * Based on Module from "Excellence Technologies" (excellencetechnologies.in)
 *
 * @category   Magentix
 * @package    Magentix_Fee
 * @author     Matthieu Vion (http://www.magentix.fr)
 * @license    This work is free software, you can redistribute it and/or modify it
 */

$installer = $this;

$installer->startSetup();

$installer->run("
    ALTER TABLE  `".$this->getTable('sales/quote')."` ADD  `shipping_insurance_amount` DECIMAL( 10, 2 ) NOT NULL;
    ALTER TABLE  `".$this->getTable('sales/order')."` ADD  `shipping_insurance_amount` DECIMAL( 10, 2 ) NOT NULL;
");

$installer->endSetup();