<?php
namespace Mageplaza\BlogGraphQl\Model\ResourceModel\ViewCount;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'traffic_id';
    protected function _construct()
    {
        $this->_init('Mageplaza\BlogGraphQl\Model\ViewCount', 'Mageplaza\BlogGraphQl\Model\ResourceModel\ViewCount');
    }
}