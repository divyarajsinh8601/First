<?php

namespace HelenOfTroy\Webinar\Model\Config;

use Magento\Framework\Option\ArrayInterface;
use Magento\Email\Model\ResourceModel\Template\CollectionFactory;

class EmailTemplates implements ArrayInterface
{
    protected $collectionFactory;

    public function __construct(
        CollectionFactory $collectionFactory
    ) {
        $this->collectionFactory = $collectionFactory;
    }

    public function toOptionArray()
    {
        $collections = $this->collectionFactory->create();

        $options = [];
        foreach ($collections as $collection) {
            $options[] = [
                'value' => $collection->getId(),
                'label' => $collection->getTemplateCode(),
            ];
        }

        return $options;
    }
}
