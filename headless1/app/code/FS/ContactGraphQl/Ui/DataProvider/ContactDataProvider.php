<?php
namespace FS\ContactGraphQl\Ui\DataProvider;
use Magento\Ui\DataProvider\AbstractDataProvider;
use FS\ContactGraphQl\Model\ResourceModel\Contact\CollectionFactory;
class ContactDataProvider extends AbstractDataProvider
{
    protected $collection;
    protected $addFieldStrategies;
    protected $addFilterStrategies;
    public function __construct(
        $name,
        $primaryFieldName,
        $requestFieldName,
        CollectionFactory $collectionFactory,
        array $meta = [],
        array $data = []
    ) {
        parent::__construct($name, $primaryFieldName, $requestFieldName, $meta, $data);
        $this->collection = $collectionFactory->create();
    }
 
    public function getCollection()
    {
        return $this->collection;
 
    }
 
    public function getData()
    {
        if (!$this->getCollection()->isLoaded()) {
            $this->getCollection()->load();
        }
        return $this->getCollection()->toArray();
    }
}