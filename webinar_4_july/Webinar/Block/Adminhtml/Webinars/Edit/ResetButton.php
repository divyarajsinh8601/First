<?php

namespace HelenOfTroy\Webinar\Block\Adminhtml\Webinars\Edit;

use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class ResetButton
 * @package HelenOfTroy\Webinar\Block\Adminhtml\Webinars\Edit
 */
class ResetButton implements ButtonProviderInterface {

    /**
     * @return array
     */
    public function getButtonData() {
		return [
			'label' => __('Reset'),
			'class' => 'reset',
			'on_click' => 'location.reload();',
			'sort_order' => 30,
		];
	}
}
