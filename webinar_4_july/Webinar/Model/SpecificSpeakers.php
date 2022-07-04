<?php

namespace HelenOfTroy\Webinar\Model;

use Magento\Framework\Model\AbstractModel;
use HelenOfTroy\Webinar\Model\ResourceModel\SpecificSpeakers as SpecificSpeakersResourceModel;

class SpecificSpeakers extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(SpecificSpeakersResourceModel::class);
    }
}
