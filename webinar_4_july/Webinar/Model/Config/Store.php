<?php

namespace HelenOfTroy\Webinar\Model\Config;

use Magento\Framework\Option\ArrayInterface;
use Magento\Store\Api\StoreRepositoryInterface;

class Store implements ArrayInterface
{
    protected $storeRepository;

    public function __construct(
        StoreRepositoryInterface $storeRepository
    ) {
        $this->storeRepository = $storeRepository;
    }

    public function toOptionArray()
    {
        $collections = $this->storeRepository->getList();

        $options = [];
        foreach ($collections as $collection) {
            if ($collection->getId()) {
                $options[] = [
                    'value' => $collection->getId(),
                    'label' => $collection->getName(),
                ];
            }
        }

        return $options;
    }
}
