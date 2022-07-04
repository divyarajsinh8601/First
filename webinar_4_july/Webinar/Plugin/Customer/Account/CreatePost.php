<?php

namespace HelenOfTroy\Webinar\Plugin\Customer\Account;

use Magento\Customer\Model\Session;

class CreatePost
{

    public function __construct(
        Session $session
    )
    {
        $this->session = $session;
    }

    public function afterExecute(
        \Magento\Customer\Controller\Account\CreatePost $subject,
        $resultRedirect
    ) {
    	if ($this->session->getWebinarRegister()) {
        	$resultRedirect->setUrl($this->session->getWebinarRegister());
        	$this->session->setWebinarRegister('');
    	}
        return $resultRedirect;
    }
}