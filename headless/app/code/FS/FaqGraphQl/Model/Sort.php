<?php

namespace FS\FaqGraphQl\Model;

use Mageprince\Faq\Model\ResourceModel\Faq\Collection;

class Sort
{



    /**
     * @param Collection $collection
     * @param $filter
     * @return Collection
     */
    public function applySort(Collection $collection, $sort){
           foreach ($sort as $key=>$value){
              $collection->setOrder($key,$value);
            }
         return  $collection;
    }

}