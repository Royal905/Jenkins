<?php
namespace FS\PinCode\Model\ResourceModel\PinCode;

class Collection extends \Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection
{
	protected $_idFieldName = 'id';
	protected $_eventPrefix = 'fs_pincode_crud_collection';
	protected $_eventObject = 'fs_pincode_crud_event';

	/**
	 * Define resource model
	 *
	 * @return void
	 */
	protected function _construct()
	{
		$this->_init('FS\PinCode\Model\PinCode', 'FS\PinCode\Model\ResourceModel\PinCode');
	}

}
