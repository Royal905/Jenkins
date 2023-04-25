<?php


namespace FS\FaqGraphQl\Model;

use GraphQL\Type\Definition\ResolveInfo;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\Resolver\ContextInterface;
use Magento\Framework\GraphQl\Query\Resolver\Value;
use \Mageprince\Faq\Model\ResourceModel\Faq\Collection ;
use FS\FaqGraphQl\Model\Filter;
use FS\FaqGraphQl\Model\Sort;
class FaqResolver  implements \Magento\Framework\GraphQl\Query\ResolverInterface
{
    private $_collection;

    private $_filter;

    private $_sort;

    public function __construct(Collection $collection,Filter $filter,Sort $sort)
    {
        $this->_collection=$collection;
        $this->_filter=$filter;
        $this->_sort=$sort;
    }
    /**
    * @param \Magento\Framework\GraphQl\Config\Element\Field $field
    * @param ContextInterface $context
    * @param ResolveInfo $info
    * @param array|null $value
    * @param array|null $args
    * @throws \Exception
    * @return mixed|Value
    */
    public function resolve(
        Field $field,
              $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ){
//        if(isset($args['type'])){
//            if(!in_array($args['type'],$this->productType)){
//                throw new GraphQlInputException(__('Invalid type. it shoulde be %1',implode(" or ",$this->productType)));
//            }
//        }
       $result=[];
       $faqCollection=$this->_collection;

       if(isset($args['currentPage'])){
           $faqCollection->setCurPage($args['currentPage']);
       }
       if(isset($args['pageSize'])){
           $faqCollection->setPageSize($args['pageSize']);
       }
       if(isset($args['filter'])){
           $faqCollection=$this->_filter->applyFilter($faqCollection,$args['filter']);
       }

       if(isset($args['sort'])){
           $faqCollection=$this->_sort->applySort($faqCollection,$args['sort']);
       }

       foreach ($faqCollection as $item){
            $result[]=$item->getData();
       }

       return $result;
    }
}