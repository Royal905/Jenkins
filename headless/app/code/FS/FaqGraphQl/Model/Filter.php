<?php

namespace FS\FaqGraphQl\Model;

use Mageprince\Faq\Model\ResourceModel\Faq\Collection;

class Filter
{
    /**
     * @param Collection $collection
     * @param $filter
     * @return Collection
     */
    public function applyFilter(Collection $collection, $filter){

        foreach ($filter as $key=>$values){
            $attribute_name=$key;
           foreach ($values as $k=>$v){
               $filter_type=$k;
               $filter_value=$v;
               $collection->addFieldToFilter($attribute_name,[$filter_type=>$filter_value]);
           }
        }
         return  $collection;
    }

}