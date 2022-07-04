<?php

namespace HelenOfTroy\Webinar\Model\ResourceModel\Speakers;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HelenOfTroy\Webinar\Model\Speakers as SpeakersModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Speakers as SpeakersResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            SpeakersModel::class,
            SpeakersResourceModel::class
        );
    }
}
