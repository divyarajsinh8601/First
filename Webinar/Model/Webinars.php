<?php

namespace HelenOfTroy\Webinar\Model;

use Magento\Framework\Model\AbstractModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Webinars as WebinarsResourceModel;

class Webinars extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(WebinarsResourceModel::class);
    }
}