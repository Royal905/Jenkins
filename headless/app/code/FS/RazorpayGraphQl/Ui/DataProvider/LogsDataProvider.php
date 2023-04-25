<?php
namespace FS\RazorpayGraphQl\Ui\DataProvider;
use Magento\Ui\DataProvider\AbstractDataProvider;
use FS\RazorpayGraphQl\Model\ResourceModel\PaymentResponse\CollectionFactory;
class LogsDataProvider extends AbstractDataProvider
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
            $this->getCollection()->setOrder('id','DESC')->load();
        }
        return $this->getCollection()->toArray();
    }
}