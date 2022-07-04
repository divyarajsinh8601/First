<?php

namespace HelenOfTroy\Webinar\Block\Adminhtml\Question;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Reset
 * @package HelenOfTroy\Webinar\Block\Adminhtml\Question
 */
class Reset implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        return [
            'label' => __('Reset'),
            'class' => 'reset',
            'on_click' => 'location.reload();',
            'sort_order' => 10,
        ];
    }
}
