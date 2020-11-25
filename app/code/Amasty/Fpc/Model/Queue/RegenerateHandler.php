<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Fpc
 */


declare(strict_types=1);

namespace Amasty\Fpc\Model\Queue;

use Amasty\Fpc\Model\Config;
use Amasty\Fpc\Model\Queue;
use Magento\Framework\App\Area;
use Magento\Framework\App\State;
use Magento\Framework\Exception\LocalizedException;

class RegenerateHandler
{
    /**
     * @var Config
     */
    private $config;

    /**
     * @var Queue
     */
    private $queue;

    /**
     * @var State
     */
    private $appState;

    /**
     * @var bool
     */
    private $isQueueGenerated = false;

    public function __construct(
        Config $config,
        Queue $queue,
        State $appState
    ) {
        $this->config = $config;
        $this->queue = $queue;
        $this->appState = $appState;
    }

    /**
     * Regenerate pages queue if it possible
     *
     * @throws \Amasty\Fpc\Exception\LockException
     */
    public function execute()
    {
        if (!$this->isAllowedToRegenerate()) {
            return;
        }

        try {
            $this->appState->setAreaCode(Area::AREA_GLOBAL);
        } catch (\Exception $e) {
            null;
            //launched from admin
            //(emulateArea not working due the area emulation in \Amasty\Fpc\Model\Source\PageType\Emulated)
        }

        $this->queue->forceUnlock();

        if (!$this->isQueueGenerated) {
            list($result, $processedItems) = $this->queue->generate();
            $this->isQueueGenerated = $result;
        }
    }

    /**
     * Queue regeneration is not allowed in frontend area
     * to prevent performance issues on frontend
     */
    private function isAllowedToRegenerate(): bool
    {
        return $this->config->isModuleEnabled()
            && $this->config->getQueueAfterGenerate()
            && !$this->isFrontendArea();
    }

    private function isFrontendArea(): bool
    {
        $isFrontend = false;

        try {
            $isFrontend = $this->appState->getAreaCode() == Area::AREA_FRONTEND;
        } catch (LocalizedException $e) {
            null;
        }

        return $isFrontend;
    }
}
