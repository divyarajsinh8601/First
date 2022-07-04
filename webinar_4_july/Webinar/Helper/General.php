<?php
namespace HelenOfTroy\Webinar\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Customer\Model\Session;
use Magento\Customer\Model\SessionFactory;

/**
 * Class General
 * @package HelenOfTroy\Webinar\Helper
 */
class General extends AbstractHelper
{
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;

    /**
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        StoreManagerInterface $storeManager,
        Session $session,
        SessionFactory $sessionFactory
    ) {
        $this->storeManager = $storeManager;
        $this->session = $session;
        $this->sessionFactory = $sessionFactory;
    }

    public function getMediaUrl($path = '')
    {
        $mediaUrl = $this->storeManager->getStore()->getBaseUrl(\Magento\Framework\UrlInterface::URL_TYPE_MEDIA) . 'HelenOfTroy/Webinar/Files/' . $path;
        return $mediaUrl;
    }

    public function getIsLogin()
    {
        return $this->session->isLoggedIn();
    }

    public function getCurrentCustomerId()
    {
        $customer = $this->sessionFactory->create()->getCustomer();
        return $customer ? $customer->getId() : '';
    }
}
