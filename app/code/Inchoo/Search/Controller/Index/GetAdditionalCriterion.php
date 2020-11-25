<?php

namespace Inchoo\Search\Controller\Index;

use Magento\Framework\App\Action\Context;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\View\Element\Template;

class GetAdditionalCriterion extends \Magento\Framework\App\Action\Action {

    protected $_logger;
    protected $_resourceConnection;
    protected $_coreTemplate;

    public function __construct(Context $context, LoggerInterface $logger, ResourceConnection $resourceConnection, Template $coreTemplate) {
        parent::__construct($context);

        $this->_logger = $logger;
        $this->_resourceConnection = $resourceConnection;
        $this->_coreTemplate = $coreTemplate;
    }

    public function execute() {
        try {
            $responseData = array();
            $yearId = $this->getRequest()->getParam('year');
            $makeId = $this->getRequest()->getParam('make');
            $modelId = $this->getRequest()->getParam('model');
            $subModelId = $this->getRequest()->getParam('submodel');
            if (!$yearId || !$makeId || !$modelId || !$subModelId) {
                die;
            }
            $data = \Inchoo\Search\Helper\Vehicle::getAdditionalCriterion($yearId, $makeId, $modelId, $subModelId);

            if (empty($data)) {
                $responseData['criterionHtml'] = "";
            } else {
                $layout = $this->getLayout();
                $criterionBlock = $layout->createBlock($this->_coreTemplate);
                $criterionBlock->setData($data->getData());
                $criterionBlock->setTemplate('Inchoo_Search::search/addtlcriterion.phtml');
                $criterionHtml = $criterionBlock->toHtml(); //also consider $myBlock->renderView();
                $responseData['criteria'] = $data;
                $responseData['criterionHtml'] = $criterionHtml;
                $this->getResponse()->setHeader('Content-type', 'application/json', true);
            }
            $this->getResponse()->setBody(\Magento\Framework\Json\Helper\Data::jsonEncode($responseData));
        } catch (\Exception $e) {
            $this->_logger->critical('Error message', ['exception' => $e]);
        }
    }

    public static function getDbConnection() {
        return $this->_resourceConnection->getConnection('revo');
    }

}
