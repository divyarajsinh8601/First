<?php

namespace HelenOfTroy\Webinar\Controller\Adminhtml\Pages;

use Magento\Framework\Controller\ResultFactory;

/**
 * Class Upload
 * @package HelenOfTroy\Webinar\Controller\Adminhtml\Pages
 */
class Upload extends \Magento\Backend\App\Action {

    /**
     * @var \HelenOfTroy\Webinar\Model\ImageUploader
     */
    public $imageUploader;

    /**
     * Upload constructor.
     * @param \Magento\Backend\App\Action\Context $context
     * @param \HelenOfTroy\Webinar\Model\ImageUploader $imageUploader
     */
	public function __construct(
		\Magento\Backend\App\Action\Context $context,
		\HelenOfTroy\Webinar\Model\ImageUploader $imageUploader
	) {
		parent::__construct($context);
		$this->imageUploader = $imageUploader;
	}

    /**
     * @return mixed
     */
	public function _isAllowed() {
		return $this->_authorization->isAllowed('HelenOfTroy_Webinar::image');
	}

    /**
     * @return mixed
     */
    public function execute() {
		try {
			$result = $this->imageUploader->saveFileToTmpDir('image');
			$result['cookie'] = [
				'name' => $this->_getSession()->getName(),
				'value' => $this->_getSession()->getSessionId(),
				'lifetime' => $this->_getSession()->getCookieLifetime(),
				'path' => $this->_getSession()->getCookiePath(),
				'domain' => $this->_getSession()->getCookieDomain(),
			];
		} catch (\Exception $e) {
			$result = ['error' => $e->getMessage(), 'errorcode' => $e->getCode()];
		}
		return $this->resultFactory->create(ResultFactory::TYPE_JSON)->setData($result);
	}
}
