<?php
namespace Mageplaza\BlogGraphQl\Model\ResourceModel;
class ViewCount extends \Magento\Framework\Model\ResourceModel\Db\AbstractDb
{
    protected function _construct()
    {
        $this->_init('mageplaza_blog_post_traffic', 'traffic_id');
    }
}