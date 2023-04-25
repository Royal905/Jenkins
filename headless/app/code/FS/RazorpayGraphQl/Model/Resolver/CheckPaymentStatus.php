<?php
 
declare(strict_types=1);
 
namespace FS\RazorpayGraphQl\Model\Resolver;


use FS\RazorpayGraphQl\Model\Resolver\DataProvider\PaymentStatus as PaymentStatusService;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;

class CheckPaymentStatus implements ResolverInterface
{
    

     /**
     * @var GenerateHashService
     */
    private $paymentStatusService;
 
    /**
     * @param GenerateHashService $generateHashService
     */
    public function __construct(PaymentStatusService $paymentStatusService)
    {
        $this->paymentStatusService = $paymentStatusService;
    }
 


    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {
        
        return $this->paymentStatusService->execute($args);
        
    }
}
