<?php

namespace HelenOfTroy\Webinar\Model\ResourceModel\Webinars;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HelenOfTroy\Webinar\Model\Webinars as WebinarsModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Webinars as WebinarsResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            WebinarsModel::class,
            WebinarsResourceModel::class
        );
    }
}