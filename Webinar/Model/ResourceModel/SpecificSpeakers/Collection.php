<?php

namespace HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HelenOfTroy\Webinar\Model\SpecificSpeakers as SpecificSpeakersModel;
use HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers as SpecificSpeakersResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            SpecificSpeakersModel::class,
            SpecificSpeakersResourceModel::class
        );
    }
}
