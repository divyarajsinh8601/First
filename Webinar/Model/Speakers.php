<?php

namespace HelenOfTroy\Webinar\Model;

use Magento\Framework\Model\AbstractModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Speakers as SpeakersResourceModel;

class Speakers extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(SpeakersResourceModel::class);
    }
}
