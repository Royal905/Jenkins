<?php
namespace FS\TestimonialGraphQl\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class Type implements ArrayInterface
{
    public function toOptionArray()
    {
        $result = [];
        foreach ($this->getOptions() as $value => $label) {
            $result[] = [
                 'value' => $value,
                 'label' => $label,
             ];
        }

        return $result;
    }

    public function getOptions()
    {
        return [
            'video' => __('Video'),
            'text' => __('Text'),
        ];
    }
}