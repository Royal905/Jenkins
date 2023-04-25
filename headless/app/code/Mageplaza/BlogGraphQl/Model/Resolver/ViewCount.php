<?php
namespace Mageplaza\BlogGraphQl\Model\Resolver;
//resolver section
use Magento\Framework\Exception\NoSuchEntityException;
use Magento\Framework\GraphQl\Config\Element\Field;
use Magento\Framework\GraphQl\Exception\GraphQlInputException;
use Magento\Framework\GraphQl\Exception\GraphQlNoSuchEntityException;
use Magento\Framework\GraphQl\Query\ResolverInterface;
use Magento\Framework\GraphQl\Schema\Type\ResolveInfo;


class ViewCount implements ResolverInterface
{
    private $contactusDataProvider;

    /**
     * @param
     */
    public function __construct(
        \Mageplaza\BlogGraphQl\Model\Resolver\DataProvider\ViewCount $contactusDataProvider
    ) {
		
        $this->contactusDataProvider = $contactusDataProvider;
    }

    public function resolve(
        Field $field,
        $context,
        ResolveInfo $info,
        array $value = null,
        array $args = null
    ) {

        $post_id = $args['input']['post_id'];

        $success_message = $this->contactusDataProvider->contactUs(
            $post_id
        );
        return $success_message;
    }

}