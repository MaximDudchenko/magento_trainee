<?php

namespace Dudchenko\RandomProductSidebar\Block;

use Magento\Catalog\Model\Product;

class ProductSidebar extends \Magento\Framework\View\Element\Template
{
    protected $productCollectionFactory;
    protected $productVisibility;
    protected $helperImage;
    protected $helperPrice;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Framework\Pricing\Helper\Data $helperPrice,
        array $data = []
    ) {
        $this->productCollectionFactory = $productCollectionFactory;
        $this->productVisibility = $productVisibility;
        $this->helperPrice = $helperPrice;
        parent::__construct($context, $data);
    }

    public function getProductCollection() {
        $collection = $this->productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'thumbnail']);
        $collection->addAttributeToFilter('type_id', \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
        $collection->setVisibility($this->productVisibility->getVisibleInSiteIds());
        $collection->setPageSize(3);
        $collection->getSelect()->orderRand()->limit(3);

        return $collection;
    }

    public function getProductPrice(Product $product): string
    {
        return $this->helperPrice->currency($product->getFinalPrice(), includeContainer: false);
    }


    public function getProductUrl(Product $product): string
    {
        return $product->getProductUrl(false);
    }
}
