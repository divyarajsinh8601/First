<?php

namespace HelenOfTroy\Webinar\Model;

use Magento\Framework\Model\AbstractModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Attendees as AttendeesResourceModel;

class Attendees extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(AttendeesResourceModel::class);
    }
}