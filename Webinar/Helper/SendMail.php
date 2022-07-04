<?php
namespace HelenOfTroy\Webinar\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\Helper\Context;
use Magento\Store\Model\ScopeInterface;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Email\Model\Template as coreTemplate;

/**
 * Class SendMail
 * @package HelenOfTroy\Webinar\Helper
 */
class SendMail extends \Magento\Framework\App\Helper\AbstractHelper
{
    /**
     *
     */
    const WEBINAR_EMAIL_SENDER_NAME = 'helenoftroy_webinar/module/email_sender';
    /**
     * @var \Magento\Store\Model\StoreManagerInterface
     */
    protected $storeManager;
    /**
     * @var \Magento\Framework\Mail\Template\TransportBuilder
     */
    protected $_transportBuilder;

    /**
     * @var
     */
    protected $emailTemplate;

    /**
     * @param \Magento\Framework\App\Helper\Context $context
     * @param StoreManagerInterface $storeManager
     * @param \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder
     * @param ScopeConfigInterface $scopeConfig
     * @param StoreManagerInterface $storeManager
     */
    public function __construct(
        \Magento\Framework\App\Helper\Context $context,
        \Magento\Store\Model\StoreManagerInterface $storeManager,
        \Magento\Framework\Mail\Template\TransportBuilder $transportBuilder,
        ScopeConfigInterface $scopeConfig,
        coreTemplate $template
    ) {
        parent::__construct($context);
        $this->storeManager = $storeManager;
        $this->_transportBuilder = $transportBuilder;
        $this->scopeConfig = $scopeConfig;
        $this->template = $template;
    }

    /**
     * @param $emailTemplateName
     * @param $customerData
     * @param $customerEmail
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\MailException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function sendMail($emailTemplateName, $customerData, $customerEmail)
    {
        $emailSender = $this->scopConfig(self::WEBINAR_EMAIL_SENDER_NAME);
        $getStoreName = $this->scopConfig('trans_email/ident_'.$emailSender.'/name');
        $getStoreEmail = $this->scopConfig('trans_email/ident_'.$emailSender.'/email');
        $store = $this->storeManager->getStore()->getId();
        $sender = [
					'name' => $getStoreName,
					'email' => $getStoreEmail,
				];

          $transport = $this->_transportBuilder->setTemplateIdentifier($emailTemplateName)
            ->setTemplateOptions(['area' => \Magento\Framework\App\Area::AREA_FRONTEND,
                                  'store' => $store])
            ->setTemplateVars($customerData)
            ->setFrom($sender)
            ->addTo($customerEmail);
          $transport = $this->_transportBuilder->getTransport();
          $transport->sendMessage();
    }

    /**
     * @param $const
     * @return mixed
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function scopConfig($const) {
       return $this->scopeConfig->getValue($const, ScopeInterface::SCOPE_STORE, $this->storeManager->getStore()->getId());
    }

    /**
     * @return mixed
     */
    public function adminEmailSelector() {
        return $this->scopeConfig->getValue('helenoftroy_webinar/module/admin_auth_send_email',ScopeInterface::SCOPE_STORE);
    }

    /**
     * @param $templateId
     */
    public function getTemplateIdentifier($templateId) {
        return  $this->template->load($templateId);

    }

}
