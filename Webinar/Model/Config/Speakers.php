<?php

namespace HelenOfTroy\Webinar\Model\Config;

use Magento\Framework\Option\ArrayInterface;
use HelenOfTroy\Webinar\Model\ResourceModel\Speakers\CollectionFactory;

class Speakers implements ArrayInterface
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
        $admin = [];
        foreach ($collections as $collection) {
            $admin[] = [
                'value' => $collection->getId(),
                'label' => $collection->getFirstname().' '.$collection->getLastname(),
            ];
        }
        return $admin;
    }
}
