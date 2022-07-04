<?php

namespace HelenOfTroy\Webinar\Model\ResourceModel\Attendees;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HelenOfTroy\Webinar\Model\Attendees as AttendeesModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Attendees as AttendeesResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            AttendeesModel::class,
            AttendeesResourceModel::class
        );
    }
}