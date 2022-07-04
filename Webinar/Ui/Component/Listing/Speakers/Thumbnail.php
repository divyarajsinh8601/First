<?php

namespace HelenOfTroy\Webinar\Ui\Component\Listing\Speakers;

use Magento\Framework\View\Element\UiComponentFactory;
use Magento\Framework\View\Element\UiComponent\ContextInterface;
use HelenOfTroy\Webinar\Helper\General as GeneralHelper;
use HelenOfTroy\Webinar\Model\SpeakersFactory;

/**
 * Class Thumbnail
 * @package HelenOfTroy\Webinar\Ui\Component\Listing\Speakers
 */
class Thumbnail extends \Magento\Ui\Component\Listing\Columns\Column
{
    /**
     *
     */
    const NAME = 'thumbnail';

    /**
     *
     */
    const ALT_FIELD = 'name';

    /**
     * @var string
     */
    private $editUrl;

    /**
     * @var
     */
    protected $generalHelper;
    /**
     * @var \Magento\Framework\ObjectManagerInterface
     */
    private $_objectManager = null;

    /**
     * @param ContextInterface $context
     * @param UiComponentFactory $uiComponentFactory
     * @param \Magento\Framework\UrlInterface $urlBuilder
     * @param array $components
     * @param array $data
     */
    public function __construct(
        ContextInterface $context,
        UiComponentFactory $uiComponentFactory,
        \Magento\Framework\UrlInterface $urlBuilder,
        \Magento\Framework\ObjectManagerInterface $objectManager,
        GeneralHelper $generalHelper,
        SpeakersFactory $speakersFactory,
        array $components = [],
        array $data = []
    )
    {
        parent::__construct($context, $uiComponentFactory, $components, $data);
        $this->urlBuilder = $urlBuilder;
        $this->generalHelper = $generalHelper;
        $this->speakersFactory = $speakersFactory;
        $this->_objectManager = $objectManager;
    }

    /**
     * Prepare Data Source
     *
     * @param array $dataSource
     * @return array
     */
    public function prepareDataSource(array $dataSource)
    {
        if (isset($dataSource['data']['items'])) {
            $fieldName = $this->getData('name');

            foreach ($dataSource['data']['items'] as &$item) {
                if (isset($item['speaker_id'])) {
                    $image = $this->speakersFactory->create()->load($item['speaker_id'])->getImage();
                    if ($image) {
                        $item[$fieldName . '_src'] = $this->generalHelper->getMediaUrl($image);
                        $item[$fieldName . '_alt'] = $this->getAlt($item) ?: $image;
                        $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                            'webinars/speakers/form',
                            ['id' => $item['id']]);
                        $item[$fieldName . '_orig_src'] = $this->generalHelper->getMediaUrl($image);
                    }
                }
                if (isset($item['image'])) {
                    $item[$fieldName . '_src'] = $this->generalHelper->getMediaUrl($item['image']);
                    $item[$fieldName . '_alt'] = $this->getAlt($item) ?: $item['image'];
                    $item[$fieldName . '_link'] = $this->urlBuilder->getUrl(
                        'webinars/speakers/form',
                        ['id' => $item['id']]);
                    $item[$fieldName . '_orig_src'] = $this->generalHelper->getMediaUrl($item['image']);
                }

            }
        }

        return $dataSource;
    }

    /**
     * @param array $row
     *
     * @return null|string
     */
    protected function getAlt($row)
    {
        $altField = $this->getData('config/altField') ?: self::ALT_FIELD;
        return isset($row[$altField]) ? $row[$altField] : null;
    }
}
