<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExport
 */


declare(strict_types=1);

namespace Amasty\ProductExport\Observer;

use Amasty\ExportCore\Api\Config\ProfileConfigInterface;
use Amasty\ExportCore\Api\ExportProcessInterface;
use Amasty\ExportCore\Api\ExportResultInterface;
use Amasty\ProductExport\Model\ConfigProvider;
use Amasty\ProductExport\Model\ModuleType;
use Amasty\ProductExport\Utils\Email;
use Magento\Framework\App\Area;
use Magento\Framework\Event\ObserverInterface;
use Magento\Framework\Stdlib\DateTime\TimezoneInterface;
use Magento\Store\Model\Store;

class ExportRunAfter implements ObserverInterface
{
    /**
     * @var ConfigProvider
     */
    private $configProvider;

    /**
     * @var Email
     */
    private $email;

    /**
     * @var TimezoneInterface
     */
    private $timezone;

    public function __construct(
        ConfigProvider $configProvider,
        TimezoneInterface $timezone,
        Email $email
    ) {
        $this->configProvider = $configProvider;
        $this->timezone = $timezone;
        $this->email = $email;
    }

    public function execute(\Magento\Framework\Event\Observer $observer)
    {
        try {
            /** @var ExportProcessInterface $exportProcess */
            $exportProcess = $observer->getData('exportProcess');
            $profileConfig = $exportProcess->getProfileConfig();
            $exportResult = $exportProcess->getExportResult();

            if ($profileConfig->getModuleType() === ModuleType::TYPE
                && !$profileConfig->getExtensionAttributes()->getManualRun()
                && $exportResult->isFailed()
            ) {
                $this->sendAdminNotification($profileConfig, $exportResult);
            }
        } catch (\Exception $e) {
            null;
        }
    }

    private function getTypeMessage($typeId)
    {
        switch ($typeId) {
            case ExportResultInterface::MESSAGE_CRITICAL:
                $typeText = __('Critical');
                break;
            case ExportResultInterface::MESSAGE_ERROR:
                $typeText = __('Error');
                break;
            case ExportResultInterface::MESSAGE_WARNING:
                $typeText = __('Warning');
                break;
            case ExportResultInterface::MESSAGE_INFO:
                $typeText = __('Info');
                break;
            case ExportResultInterface::MESSAGE_DEBUG:
                $typeText = __('Debug');
                break;
            default:
                $typeText = '';
        }

        return $typeText;
    }

    private function getTextMessages(ExportResultInterface $exportResult)
    {
        $messages = [];

        foreach ($exportResult->getMessages() as $messageItem) {
            $type = $this->getTypeMessage($messageItem['type']);
            $messages[] = (!empty($type) ? $type . ': ' : '') . $messageItem['message'];
        }

        return implode(' ', $messages);
    }

    private function sendAdminNotification(ProfileConfigInterface $profileConfig, ExportResultInterface $exportResult)
    {
        if (!$this->configProvider->isNotifyAdmin()) {
            return;
        }

        $emailData = [
            'date' => $this->timezone->formatDate(),
            'profile_name' => $profileConfig->getExtensionAttributes()->getName(),
            'error_text' => $this->getTextMessages($exportResult)
        ];

        $this->email->sendEmail(
            $this->configProvider->notifyAdminEmailRecipients(),
            Store::DEFAULT_STORE_ID,
            $this->configProvider->notifyAdminEmailTemplate(),
            $emailData,
            Area::AREA_ADMINHTML,
            $this->configProvider->notifyAdminEmail()
        );
    }
}
