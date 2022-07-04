<?php

namespace HelenOfTroy\Webinar\Model\ResourceModel\Questions;

use Magento\Framework\Model\ResourceModel\Db\Collection\AbstractCollection;
use HelenOfTroy\Webinar\Model\Questions as QuestionsModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Questions as QuestionsResourceModel;

class Collection extends AbstractCollection
{
    protected function _construct()
    {
        $this->_init(
            QuestionsModel::class,
            QuestionsResourceModel::class
        );
    }
}