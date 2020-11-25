<?php
/*© Copyright 2020 Webgility Inc
    ----------------------------------------
 All materials contained in these files are protected by United States copyright
 law and may not be reproduced, distributed, transmitted, displayed, published or
 broadcast without the prior written permission of Webgility LLC. You may not
 alter or remove any trademark, copyright or other notice from copies of the
 content.
*/

namespace Webgility\EccM2\Model;

use Magento\Sales\Model\Order\Email\Sender\CreditmemoSender;

class WgSender extends CreditmemoSender
{
    public function __construct($_objectManager)
    {
        global $objectManager;
        $objectManager = $_objectManager;
        $templateContainer = $objectManager->create('Magento\Sales\Model\Order\Email\Container\Template');
        $identityContainer = $objectManager->create('Magento\Sales\Model\Order\Email\Container\CreditmemoIdentity');
        $senderBuilderFactory = $objectManager->create('Magento\Sales\Model\Order\Email\SenderBuilderFactory');
        $logger = $objectManager->create('Psr\Log\LoggerInterface');
        $addressRenderer = $objectManager->create('Magento\Sales\Model\Order\Address\Renderer');
        $paymentHelper = $objectManager->create('Magento\Payment\Helper\Data');
        $creditmemoResource = $objectManager->create('Magento\Sales\Model\ResourceModel\Order\Creditmemo');
        $globalConfig = $objectManager->create('Magento\Framework\App\Config\ScopeConfigInterface');
        $eventManager = $objectManager->create('Magento\Framework\Event\ManagerInterface');
       
        parent::__construct(
            $templateContainer,
            $identityContainer,
            $senderBuilderFactory,
            $logger,
            $addressRenderer,
            $paymentHelper,
            $creditmemoResource,
            $globalConfig,
            $eventManager
        );
    }
                                       
    public function wgSend($creditmemo, $forceSyncMode = false)
    {

        $creditmemo->setSendEmail(true);

        if (!$this->globalConfig->getValue('sales_email/general/async_sending') || $forceSyncMode) {
            $order = $creditmemo->getOrder();
            
            $transport = [
                'order' => $order,
                'creditmemo' => $creditmemo,
                'comment' => $creditmemo->getCustomerNoteNotify() ? $creditmemo->getCustomerNote() : '',
                'billing' => $order->getBillingAddress(),
                'payment_html' => $this->wggetPaymentHtml($order),
                'store' => $order->getStore(),
                'formattedShippingAddress' => $this->getFormattedShippingAddress($order),
                'formattedBillingAddress' => $this->getFormattedBillingAddress($order),
            ];

            $this->eventManager->dispatch(
                'email_creditmemo_set_template_vars_before',
                ['sender' => $this, 'transport' => $transport]
            );

            $this->templateContainer->setTemplateVars($transport);

            if ($this->checkAndSend($order)) {
                $creditmemo->setEmailSent(true);
                $this->creditmemoResource->saveAttribute($creditmemo, ['send_email', 'email_sent']);
                return true;
            }
        }

        $this->creditmemoResource->saveAttribute($creditmemo, 'send_email');

        return false;
    }

    public function wggetPaymentHtml($order)
    {
        $templateFileName = "/home/giliwebstore/domains/magento.giliwebstore.com/public_html/
        magento202/vendor/magento/module-offline-payments/view/frontend/templates/info/checkmo.phtml";
        $paymentHelper = $this->_objectManager->get('Magento\Payment\Helper\Data');
        $htmlData = $paymentHelper->getInfoBlock($order->getPayment());
        $templateObj = $this->_objectManager->get('Magento\Framework\View\Element\Template');
        $templateEngine = $this->_objectManager->get('Magento\Framework\View\TemplateEngine\Php');
        $html = $templateEngine->render($htmlData, $templateFileName);
        return($html);
    }
}
