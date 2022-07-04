<?php
namespace HelenOfTroy\Webinar\Model\Config;

class State implements \Magento\Framework\Option\ArrayInterface {
	public function toOptionArray() {
		return [
				['value' => 'Pre-Webinar', 'label' => __('Pre-Webinar')], 
				['value' => 'Live-Webinar', 'label' => __('Live-Webinar')], 
				['value' => 'Post-Webinar', 'label' => __('Post-Webinar')]
			];
	}

}
