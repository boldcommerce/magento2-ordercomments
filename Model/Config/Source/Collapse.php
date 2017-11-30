<?php

namespace Bold\OrderComment\Model\Config\Source;

class Collapse implements \Magento\Framework\Option\ArrayInterface
{
    /**
     * Options getter
     *
     * @return array
     */
    public function toOptionArray()
    {
        $options = $this->toArray();
        $result = [];

        foreach($options as $value => $label){
            $result[] = [
                'value' => $value, 'label' => $label
            ];
        }

        return $result;
    }

    /**
     * Get options in "key-value" format
     *
     * @return array
     */
    public function toArray()
    {
        return [
            0 => __('Starts with field closed'),
            1 => __('Starts with field opened'),
            2 => __('Render field without collapse')
        ];
    }
}
