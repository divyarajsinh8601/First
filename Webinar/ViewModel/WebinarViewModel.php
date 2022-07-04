<?php
namespace HelenOfTroy\Webinar\ViewModel;

use HelenOfTroy\Webinar\Model\WebinarInterface;
use Magento\Framework\Stdlib\DateTime\DateTime;

/**
 * Class WebinarViewModel
 * @package HelenOfTroy\Webinar\ViewModel
 */
class WebinarViewModel implements \Magento\Framework\View\Element\Block\ArgumentInterface
{
    /**
     * @var WebinarInterface
     */
    protected $webinarInterface;

    /**
     * WebinarViewModel constructor.
     * @param WebinarInterface $webinarInterface
     */
    public function __construct(WebinarInterface $webinarInterface,DateTime $dateTime)
    {
        $this->webinarInterface = $webinarInterface;
        $this->dateTime = $dateTime;
    }

    /**
     * @return mixed
     */
    public function getPreWebinarsModel() {
        $webinarCollection = $this->webinarInterface->getPreWebinarsCollection();
        $currentDate = $this->dateTime->date();
        $webinarCollection->addFieldToFilter('date',['lt' => $currentDate])->getData();
        return $webinarCollection;
    }

    /**
     * @return mixed
     */
    public function getPostWebinarsModel() {
        $webinarCollection = $this->webinarInterface->getPostWebinarsCollection();
        $currentDate = $this->dateTime->date();
        $webinarCollection->addFieldToFilter('date',['gt' => $currentDate]);
        return $webinarCollection;
    }
}
