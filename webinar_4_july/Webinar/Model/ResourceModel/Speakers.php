<?php

namespace HelenOfTroy\Webinar\Model\ResourceModel;

use Magento\Framework\Model\ResourceModel\Db\AbstractDb;

class Speakers extends AbstractDb
{
    protected function _construct()
    {
        $this->_init('webinar_speakers', 'id');
    }
}
