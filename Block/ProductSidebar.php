<?php

namespace Dudchenko\RandomProductSidebar\Block;

use Magento\Catalog\Model\Product;

class ProductSidebar extends \Magento\Framework\View\Element\Template
{
    protected $_productCollectionFactory;
    protected $_productVisibility;
    protected $_helperImage;
    protected $_helperPrice;

    public function __construct(
        \Magento\Framework\View\Element\Template\Context $context,
        \Magento\Catalog\Model\ResourceModel\Product\CollectionFactory $productCollectionFactory,
        \Magento\Catalog\Model\Product\Visibility $productVisibility,
        \Magento\Catalog\Helper\Image $helperImage,
        \Magento\Framework\Pricing\Helper\Data $helperPrice,
        array $data = []
    ) {
        $this->_productCollectionFactory = $productCollectionFactory;
        $this->_productVisibility = $productVisibility;
        $this->_helperImage = $helperImage;
        $this->_helperPrice = $helperPrice;
        parent::__construct($context, $data);
    }

    public function getProductCollection() {
        $collection = $this->_productCollectionFactory->create();
        $collection->addAttributeToSelect(['name', 'price', 'thumbnail']);
        $collection->addAttributeToFilter('type_id', \Magento\Catalog\Model\Product\Type::TYPE_SIMPLE);
        $collection->setVisibility($this->_productVisibility->getVisibleInSiteIds());
        $collection->setPageSize(3); // делаем выборку только 10 товаров
        $collection->getSelect()->orderRand()->limit(3);

        return $collection;
    }

    public function getProductPrice(Product $product): string
    {
        return $this->_helperPrice->currency($product->getFinalPrice(), includeContainer: false);
    }

    public function getProductImageUrl(Product $product): string
    {
        $imageUrl = $this->_helperImage->init($product, 'product_page_image_thumbnail')
            ->setImageFile($product->getThumbnail())
            ->resize(60, 80)
            ->getUrl();

        return $imageUrl;
    }

    public function getProductUrl(Product $product): string
    {
        return $product->getProductUrl(false);
    }
}
