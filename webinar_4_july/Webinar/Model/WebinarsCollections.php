<?php

namespace HelenOfTroy\Webinar\Model;

use HelenOfTroy\Webinar\Model\WebinarInterface;
use HelenOfTroy\Webinar\Model\ResourceModel\Webinars\CollectionFactory as WebinarCollectionFactory;

/**
 * Class WebinarsCollections
 * @package HelenOfTroy\Webinar\Model\Interface
 */
class WebinarsCollections implements WebinarInterface
{

    /**
     * WebinarsCollections constructor.
     * @param WebinarCollectionFactory $webinarCollectionFactory
     */
    public function __construct(
        WebinarCollectionFactory $webinarCollectionFactory
    )
    {
        $this->webinarCollectionFactory = $webinarCollectionFactory;
    }

    /**
     * @inheritDoc
     */
    public function getPreWebinarsCollection():object
    {
        return $this->webinarCollectionFactory->create();
    }

    /**
     * @inheritDoc
     */
    public function getPostWebinarsCollection():object
    {
        return $this->webinarCollectionFactory->create();
    }

}
