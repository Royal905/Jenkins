<?php
namespace FS\ContactGraphQl\Model\ResourceModel\Contact;
class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
    protected $_idFieldName = 'entry_id';
    protected function _construct()
    {
        $this->_init('FS\ContactGraphQl\Model\Contact', 'FS\ContactGraphQl\Model\ResourceModel\Contact');
    }
}