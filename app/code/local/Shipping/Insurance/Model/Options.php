<?php
class Shipping_Insurance_Model_Options
{
    /**
     * Provide available options as a value/label array
     *
     * @return array
     */
    public function toOptionArray()
    {
        return array(
            array('value'=>1, 'label'=>'Absolute value'),
            array('value'=>2, 'label'=>'Percentage of the value'),
        );
    }
}