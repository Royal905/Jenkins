<?php
 
declare(strict_types=1);
 
namespace FS\RazorpayGraphQl\Model\Resolver;
 
use FS\RazorpayGraphQl\Model\Resolver\DataProvider\UpdatePaymentResponse as UpdatePaymentResponseService;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;
 
class UpdatePaymentResponse implements ResolverInterface
{
    /**
     * @var GenerateHashService
     */
    private $updatePaymentResponseService;
 
    /**
     * @param GenerateHashService $generateHashService
     */
    public function __construct(UpdatePaymentResponseService $updatePaymentResponseService)
    {
        $this->updatePaymentResponseService = $updatePaymentResponseService;
    }
 
    /**
     * @inheritDoc
     */
    public function resolve(Field $field, $context, ResolveInfo $info, array $value = null, array $args = null)
    {
        if (empty($args['input']) || !is_array($args['input'])) {
            throw new GraphQlInputException(__('"input" value should be specified'));
        }
 
        return ['response'=>$this->updatePaymentResponseService->execute($args['input'])];
    }
}