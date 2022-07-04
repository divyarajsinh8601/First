<?php
declare (strict_types = 1);

namespace HelenOfTroy\Webinar\Block\Adminhtml\Attendee;

use Magento\Customer\Block\Adminhtml\Edit\GenericButton;
use Magento\Framework\View\Element\UiComponent\Control\ButtonProviderInterface;

/**
 * Class Cancel
 * @package HelenOfTroy\Webinar\Block\Adminhtml\Attendee
 */
class Cancel extends GenericButton implements ButtonProviderInterface
{
    /**
     * @return array
     */
    public function getButtonData()
    {
        $form = "webinar_pages_form.webinar_pages_form.webinar_attendees.attendee_modal";
        return [
            'label' => __('Cancel'),
            'on_click' => '',
            'data_attribute' => [
                'mage-init' => [
                    'Magento_Ui/js/form/button-adapter' => [
                        'actions' => [
                            [
                                'targetName' => $form,
                                'actionName' => 'closeModal',
                            ],
                        ],
                    ],
                ],
            ],
            'sort_order' => 10,
        ];
    }
}
