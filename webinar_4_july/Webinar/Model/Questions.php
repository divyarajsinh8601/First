<?php

namespace HelenOfTroy\Webinar\Model;

use Magento\Framework\Model\AbstractModel;
use HelenOfTroy\Webinar\Model\ResourceModel\Questions as QuestionsResourceModel;

class Questions extends AbstractModel
{
    protected function _construct()
    {
        $this->_init(QuestionsResourceModel::class);
    }
}