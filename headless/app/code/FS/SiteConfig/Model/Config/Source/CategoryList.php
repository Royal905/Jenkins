<?php

namespace FS\SiteConfig\Model\Config\Source;

use Magento\Framework\Option\ArrayInterface;

class CategoryList implements ArrayInterface
{
    protected $_categoryHelper;

    public function __construct(\Magento\Catalog\Helper\Category $catalogCategory)
    {
        $this->_categoryHelper = $catalogCategory;
    }

    /*
     * Return categories helper
     */

    public function getStoreCategories($sorted = false, $asCollection = false, $toLoad = true)
    {
        return $this->_categoryHelper->getStoreCategories($sorted , $asCollection, $toLoad);
    }

    /*  
     * Option getter
     * @return array
     */
    public function toOptionArray()
    {


        $arr = $this->toArray();
        $ret = [];

        foreach ($arr as $key => $value)
        {

            $ret[] = [
                'value' => $key,
                'label' => $value
            ];
        }

        return $ret;
    }

    /*
     * Get options in "key-value" format
     * @return array
     */
    public function toArray()
    {

        $categories = $this->getStoreCategories(false,true,true);

        $catagoryList = array();
        foreach ($categories as $category){
		$level = $category->getLevel();
		$spaces = "";
		if($level > 2){
			$spaces = "&nbsp;&nbsp;&nbsp;&nbsp;";
		}
            $catagoryList[$category->getEntityId()] = __($spaces.$category->getName());
        }

        return $catagoryList;
    }

}