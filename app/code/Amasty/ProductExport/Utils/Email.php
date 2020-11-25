<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExport
 */


namespace Amasty\ProductExport\Utils;

use Magento\Framework\Mail\Template\TransportBuilder;
use Magento\Store\Model\StoreManagerInterface;
use Psr\Log\LoggerInterface;

class Email
{
    /**
     * @var StoreManagerInterface
     */
    private $storeManager;

    /**
     * @var TransportBuilder
     */
    private $transportBuilder;

    /**
     * @var LoggerInterface
     */
    private $logger;

    public function __construct(
        StoreManagerInterface $storeManager,
        TransportBuilder $transportBuilder,
        LoggerInterface $logger
    ) {
        $this->storeManager = $storeManager;
        $this->transportBuilder = $transportBuilder;
        $this->logger = $logger;
    }

    /**
     * Send email helper
     * emailTo and sendFrom can be array with keys email and name.
     * Otherwise string with key to Store Email address.
     *
     * @param string|array $emailTo
     * @param string $templateConfigPath
     * @param array  $vars
     * @param string $area
     * @param string|array $sendFrom
     */
    public function sendEmail(
        $emailTo = '',
        $storeId = 0,
        $templateIdentifier = '',
        $vars = [],
        $area = \Magento\Framework\App\Area::AREA_FRONTEND,
        $sendFrom = ''
    ) {
        try {
            /** @var \Magento\Store\Model\Store $store */
            $store = $this->storeManager->getStore($storeId);

            if (empty($sendFrom)) {
                $sendFrom = 'general';
            }

            foreach ($emailTo as $reciever) {
                $transport = $this->transportBuilder->setTemplateIdentifier(
                    $templateIdentifier
                )->setTemplateOptions(
                    ['area' => $area, 'store' => $store->getId()]
                )->setTemplateVars(
                    $vars
                )->setFrom(
                    $sendFrom
                )->addTo(
                    $reciever
                )->getTransport();

                $transport->sendMessage();
            }
        } catch (\Exception $e) {
            $this->logger->critical($e);
        }
    }
}
