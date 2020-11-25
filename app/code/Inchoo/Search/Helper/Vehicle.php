<?php

namespace Inchoo\Search\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;
use AJH\ProductVehicle\Model\VehiclestpmsFactory;
use Magento\Framework\App\Config\ScopeConfigInterface;
use Magento\Framework\Registry;
use Magento\Store\Model\StoreManagerInterface;
use Magento\Framework\UrlInterface;

use Magento\Framework\App\RequestInterface;

class Vehicle extends AbstractHelper {

    protected $eoSensorsMagentoId = ['1489'];

    const XPATH_ENABLE_LINKED_PART = 'custom_search/general/linked_part';
    const XPATH_OE_NO_RESULT_MSG = 'custom_search/general/no_result_text';
    const XPATH_IMAGE_PATH = 'custom_search/general/image_path';

    protected $collection;
    protected $linkCollection;
    protected static $_read = null;
    protected $_dbConnection, $_logger, $_vehiclestpmsFactory, $_registry, $_scopeConfig, $_storeManager, $_urlInterface, $_resourceConnection;
    
    private $protocolResult = NULL;
    
    protected $_request;

    public function __construct(ResourceConnection $dbConnection, LoggerInterface $logger, VehiclestpmsFactory $vehiclestpmsFactory, Registry $registry, ScopeConfigInterface $scopeConfig, ResourceConnection $_resourceConnection, StoreManagerInterface $storeManager, UrlInterface $urlInterface, RequestInterface $request) {
        $this->_dbConnection = $dbConnection;
        $this->_logger = $logger;

        $this->_vehiclestpmsFactory = $vehiclestpmsFactory;
        $this->_registry = $registry;
        $this->_scopeConfig = $scopeConfig;

        $this->_resourceConnection = $_resourceConnection;
        $this->_storeManager = $storeManager;

        $this->_urlInterface = $urlInterface;
        $this->_request = $request;
    }

    public function getDbConnection() {
        $dbConnection = $this->_resourceConnection->getConnection('revo');

        return $dbConnection;
    }

    public function getYears() {
        return $this->getDbConnection()->fetchCol('SELECT DISTINCT YearID FROM aaia_basevehicle ORDER BY YearID DESC');
    }

    public function getMakes($yearId) {

        $makes = self::getDbConnection()->fetchPairs(sprintf(
                        'SELECT m.MakeId, m.MakeName'
                        . ' FROM aaia_make AS m'
                        . ' INNER JOIN aaia_basevehicle AS b ON b.MakeID = m.MakeID'
                        . ' WHERE m.Active = 1 AND b.YearId = %d '
                        . ' GROUP BY b.MakeId'
                        . ' ORDER BY m.MakeName', $yearId
        ));

        return $makes;
    }

    public function getModels($yearId, $makeId) {
        $makes = self::getDbConnection()->fetchPairs(sprintf(
                        'SELECT m.ModelID, m.ModelName '
                        . 'FROM aaia_basevehicle AS b '
                        . 'INNER JOIN aaia_model AS m ON m.ModelID = b.ModelID '
                        . 'WHERE m.Active = 1 AND b.YearId = %d AND b.MakeID = %d '
                        . 'AND m.ModelID IN (' . sprintf('SELECT ModelID FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' WHERE YearID=%d AND MakeID = %d GROUP BY ModelID', $yearId, $makeId) . ')'
                        . 'GROUP BY m.ModelID '
                        . 'ORDER BY m.ModelName', $yearId, $makeId
        ));

        return $makes;
    }

    public function getSubmodels($yearId, $makeId, $modelId) {
        $makes = self::getDbConnection()->fetchPairs(sprintf(
                        'SELECT s.SubModelID, s.SubModelName'
                        . ' FROM aaia_basevehicle AS b'
                        . ' INNER JOIN aaia_vehicle AS v ON v.BaseVehicleID = b.BaseVehicleID'
                        . ' INNER JOIN aaia_submodel AS s ON s.SubModelID = v.SubModelID'
                        . ' WHERE s.Active = 1 AND b.YearId = %d AND b.MakeID = %d AND b.ModelID = %d'
                        . ' AND s.SubModelID IN (' . sprintf('SELECT SubModelID FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' WHERE YearID=%d AND MakeID = %d AND ModelID = %d GROUP BY SubModelID', $yearId, $makeId, $modelId) . ')'
                        . ' GROUP BY s.SubModelID'
                        . ' ORDER BY s.SubModelName', $yearId, $makeId, $modelId
        ));
        return $makes;
    }

    public function getVehicleInfo($yearId, $makeId, $modelId, $submodelId) {
        $res = $this->getDbConnection()->fetchAll(sprintf(
                        'SELECT *'
                        . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS
                        . ' WHERE YearId=%d AND MakeID=%d AND ModelID=%d AND SubModelID=%d'
                        . ' LIMIT 1', $yearId, $makeId, $modelId, $submodelId
        ));
        return isset($res[0]) ? $res[0] : false;
    }

    public function getRelearnUrl($yearId = null, $makeId = null, $modelId = null, $submodelId = null) {

        if (!$yearId || !$makeId || !$modelId || !$submodelId) {
            return false;
        }

        $info = $this->getVehicleInfo($yearId, $makeId, $modelId, $submodelId);
        if (empty($info['TPMSResetProcedureID'])) {
            return false;
        }

        $res = $this->_urlInterface->getUrl('', [
            '_direct' => 'tpms-reset-procedure',
            '_query' => [
                'Id' => $info['TPMSResetProcedureID'],
                'Make' => $info['MakeName'],
                'Model' => $info['ModelName'],
                'SubModel' => $info['SubModelName'],
                'Year' => $info['YearID']
            ],
        ]);
        return $res;
    }

    public function getRelearnHtml($procId = null) {
        $procId = $procId ?: (int) $this->_request->getParam('id');
        if (!$procId) {
            return false;
        }

        $res = $this->getDbConnection()->fetchOne(sprintf('SELECT `Procedure` FROM tpmsresetprocedures WHERE ID=%d', $procId));

        return $res;
    }

    public static function getAdditionalCriterion($yearId, $makeId, $modelId, $subModelId) {
        try {
            $collection = $this->_vehiclestpmsFactory->create()->getCollection()
                    ->addFieldToFilter('YearId', array('eq' => $yearId))
                    ->addFieldToFilter('MakeID', array('eq' => $makeId))
                    ->addFieldToFilter('ModelID', array('eq' => $modelId))
                    ->addFieldToFilter('SubModelID', array('eq' => $subModelId))
                    ->addFieldToFilter('AdditionalCriteria_Question', array('notnull' => true))
                    ->addFieldToFilter('AdditionalCriteria_PartMasterID', array('neq' => 0))
                    ->addFieldToSelect('AdditionalCriteria_Question')
                    ->addFieldToSelect('AdditionalCriteria_PartMasterID');
            $res = $collection->getFirstItem();
        } catch (\Exception $e) {
            $this->_logger->log("ERROR");
            $this->_logger->log($e->getMessage());

            $res = '';
        }
        return $res;
    }

    protected function isLinkedPartEnabled() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue(self::XPATH_ENABLE_LINKED_PART, $storeScope);
    }

    public function getOeNoResultMsg() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue(self::XPATH_OE_NO_RESULT_MSG, $storeScope);
    }

    public function getImagePath() {      
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
                
        return $this->_scopeConfig->getValue(self::XPATH_IMAGE_PATH, $storeScope);
    }

    public function getVtpmsCollection($additionalCriteriaPartmasterId) {
        $vtpmsCollection = $this->_vehiclestpmsFactory->create()->getCollection()
                ->addFieldToSelect('YearId')
                ->addFieldToSelect('MakeID')
                ->addFieldToSelect('ModelID')
                ->addFieldToSelect('SubModelID');
        if (boolval($additionalCriteriaPartmasterId)) {
            $vtpmsCollection->addFieldToFilter('AdditionalCriteria_Question', array('notnull' => true))
                    ->addFieldToFilter('AdditionalCriteria_PartMasterID', array('neq' => 0))
                    ->addFieldToFilter('AdditionalCriteria_PartMasterID', array('eq' => $additionalCriteriaPartmasterId));
        }

        return $vtpmsCollection;
    }

    public function getOeSensors($yearId, $makeId, $modelId, $subModelId, $additionalCriteriaPartmasterId = null) {            
        
//        if (!$this->collection) {
            try {
                $isLinkedPartEnabled = $this->isLinkedPartEnabled();
                $coreResource = $this->getDbConnection();
                $partsubclassTable = $coreResource->getTableName('Partsubclass');
                $this->collection = $this->getVtpmsCollection($additionalCriteriaPartmasterId);
                $partmasterTable = $coreResource->getTableName('Partmaster');

                if (boolval($additionalCriteriaPartmasterId)) {
                    $this->collection->getSelect()->join(
                            array('p1' => $partmasterTable), 'p1.ID=`main_table`.AdditionalCriteria_PartMasterID', array(
                        'partnumber', 'p1.partnumber',
                        'PartSubClassID' => 'p1.PartSubClassID',
                        'PartClassID' => 'p1.PartClassID',
                        'ImageThumb'
                            )
                    );
                } else {
                    $this->collection->getSelect()->join(
                            array('p1' => $partmasterTable), 'p1.ID=`main_table`.PartMasterID', array(
                        'partnumber', 'p1.partnumber',
                        'PartSubClassID' => 'p1.PartSubClassID',
                        'PartClassID' => 'p1.PartClassID',
                        'ImageThumb'
                            )
                    );
                }

                $this->collection->getSelect()->join(
                        array(
                    'psc' => $partsubclassTable
                        ), 'p1.PartClassID=`psc`.PartClassID AND p1.PartSubClassID=`psc`.ID', array('description', 'magento_id', 'pscID' => 'psc.ID')
                );
                $this->collection
                        ->addFieldToFilter('main_table.YearId', array('eq' => $yearId))
                        ->addFieldToFilter('main_table.MakeID', array('eq' => $makeId))
                        ->addFieldToFilter('main_table.ModelID', array('eq' => $modelId))
                        ->addFieldToFilter('main_table.SubModelID', array('eq' => $subModelId));
                if ($isLinkedPartEnabled) {
                    $linkedCollection = $this
                            ->getLinkedPartCollection(
                            $yearId, $makeId, $modelId, $subModelId, $additionalCriteriaPartmasterId
                    );
                    foreach ($linkedCollection as $item) {
                        $this->collection->addItem($item);
                    }
                }
//                $this->_logger->log($this->collection->getSelect()->__toString());
            } catch (Exception $e) {
                $this->_logger->log("ERROR");
                $this->_logger->log($e->getMessage());
            }
//        }
        return $this->collection;
    }

    public function getLinkedPartCollection($yearId, $makeId, $modelId, $subModelId, $additionalCriteriaPartmasterId) {
        $coreResource = $this->getDbConnection();
        $partsubclassTable = $coreResource->getTableName('Partsubclass');
        $this->linkCollection = $this->getVtpmsCollection($additionalCriteriaPartmasterId);
        $partxrefTable = $coreResource->getTableName('Partxref');
        $partmasterTable = $coreResource->getTableName('Partmaster');
        $this->linkCollection->getSelect()->join(
                array(
            'x' => $partxrefTable
                ), 'x.PartMasterID=`main_table`.PartMasterID', array(
            'XREF_ID'
                )
        );
        $this->linkCollection->getSelect()->join(
                array(
            'p1' => $partmasterTable
                ), 'p1.ID=`main_table`.PartMasterID', array(
            'PartSubClassID' => 'p1.PartSubClassID',
            'PartClassID' => 'p1.PartClassID'
                )
        );
        $this->linkCollection->getSelect()->join(
                array(
            'p2' => $partmasterTable
                ), 'p2.ID=`x`.XREF_ID', array('partnumber' => 'p2.partnumber',
            'PartSubClassID' => 'p1.PartSubClassID',
            'PartClassID' => 'p1.PartClassID',
            'ImageThumb'
                )
        );
        $this->linkCollection->getSelect()->join(
                array(
            'psc' => $partsubclassTable
                ), 'p1.PartClassID=`psc`.PartClassID AND p1.PartSubClassID=`psc`.ID', array('description', 'magento_id', 'pscID' => 'psc.ID')
        );
        $this->linkCollection
//                ->addFieldToFilter('magento_id', array('in' => $this->eoSensorsMagentoId))
                ->addFieldToFilter('main_table.YearId', array('eq' => $yearId))
                ->addFieldToFilter('main_table.MakeID', array('eq' => $makeId))
                ->addFieldToFilter('main_table.ModelID', array('eq' => $modelId))
                ->addFieldToFilter('main_table.SubModelID', array('eq' => $subModelId));
        return $this->linkCollection;
    }

    public function getSkus($yearId, $makeId, $modelId, $subModelId, $additionalCriteriaPartmasterId = null) {
        $read = $this->getDbConnection();
        if ($additionalCriteriaPartmasterId !== null) {
            $result = $read->fetchAll(sprintf('SELECT p1.partnumber, p2.partnumber as LinkedPart'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.AdditionalCriteria_PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.AdditionalCriteria_PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' WHERE v.YearID=%d and v.MakeID=%d and v.ModelID=%d and v.SubModelID=%d and AdditionalCriteria_PartMasterID=%d', $yearId, $makeId, $modelId, $subModelId, $additionalCriteriaPartmasterId
            ));
        } else {
            $result = $read->fetchAll(sprintf('SELECT p1.partnumber, p2.partnumber as LinkedPart'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' WHERE v.YearID=%d and v.MakeID=%d and v.ModelID=%d and v.SubModelID=%d', $yearId, $makeId, $modelId, $subModelId, $additionalCriteriaPartmasterId
            ));
        }

        return array_unique(array_merge(array_column($result, 'partnumber'), array_column($result, 'LinkedPart')));
    }

    /**
     * array(
      2397=>'Wheel Sensor PDQ Programmable w/Rubber Valve',
      2398=>'Wheel Sensor PDQ Programmable w/OE Metal Valve',
      2399=>'Wheel Sensor PDQ Programmable w/Metal Valve',
      1489=>'Wheel Sensor [OE]',
      2387=>'PDQ Protocol #'
      )
     * @return mixed
     */
    public function getAllowedClass() {
        $storeScope = \Magento\Store\Model\ScopeInterface::SCOPE_STORE;
        return $this->_scopeConfig->getValue('vehicle/protocol/product_class_user_def', $storeScope);
    }    

    public function getProtocolDates() {
        try {
            if (is_null($this->protocolResult)) {                               
                if ($product = $this->_registry->registry('current_product')) {
                    $productSku = $product->getSku();
                    $allowedClass = $this->getAllowedClass();
                    $query = sprintf('SELECT p1.PartNumber, p1.Date_PDQ, p1.Date_ATEQ, p1.Date_Bartec,'
                            . 'p2.PartNumber as linkedPartNumber, p2.Date_PDQ as linkedPartDate_PDQ, p2.Date_ATEQ as linkedPartDate_ATEQ, p2.Date_Bartec as linkedPartDate_Bartec'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' LEFT OUTER JOIN partsubclass psc1 on p1.PartClassID=psc1.PartClassID AND p1.PartSubClassID=psc1.ID'
                            . ' LEFT OUTER JOIN partsubclass psc2 on p2.PartClassID=psc2.PartClassID AND p2.PartSubClassID=psc2.ID'
                            . ' WHERE (p1.PartNumber=\'%s\' OR p2.PartNumber=\'%s\') AND (psc1.Description IN (%s) OR psc2.Description IN (%s))'
                            . ' AND (p1.Date_PDQ IS NOT NULL OR p1.Date_ATEQ IS NOT NULL OR p1.Date_Bartec IS NOT NULL'
                            . 'OR p2.Date_PDQ IS NOT NULL OR p2.Date_ATEQ IS NOT NULL OR p2.Date_Bartec IS NOT NULL) AND (p1.CountryOfOrigin_ID > 0 OR p2.CountryOfOrigin_ID > 0)', $productSku, $productSku, $allowedClass, $allowedClass
                    );
                    $result = $this->getDbConnection()->fetchAll($query);
//                    $this->_logger->log($query, null, 'protocolDates.log');
                } else {
                    $params = $this->_request->getParams();
                    $year = (int) $params['year'];
                    $make = (int) $params['make'];
                    $model = (int) $params['model'];
                    $query = sprintf('SELECT p1.Date_PDQ, p1.Date_ATEQ, p1.Date_Bartec,'
                            . 'p2.Date_PDQ as linkedPartDate_PDQ, p2.Date_ATEQ as linkedPartDate_ATEQ, p2.Date_Bartec as linkedPartDate_Bartec'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' WHERE v.YearID=%d and v.MakeID=%d and v.ModelID=%d and (p1.CountryOfOrigin_ID > 0 OR p2.CountryOfOrigin_ID > 0)', $year, $make, $model
                    );
                    $result = $this->getDbConnection()->fetchAll($query);
//                    $this->_logger->log($query, null, 'protocolDates.log');
                }
                
                
                
                $datePDQ = array_unique(array_merge($this->getValue($result, 'Date_PDQ'), $this->getValue($result, 'linkedPartDate_PDQ')));
                $dateATEQ = array_unique(array_merge($this->getValue($result, 'Date_ATEQ'), $this->getValue($result, 'linkedPartDate_ATEQ')));
                $dateBartec = array_unique(array_merge($this->getValue($result, 'Date_Bartec'), $this->getValue($result, 'linkedPartDate_Bartec')));
                if (!empty($datePDQ) || !empty($dateATEQ) || !empty($dateBartec)) {
                    return $this->protocolResult = array(
                        'PDQ' => $datePDQ,
                        'ATEQ' => $dateATEQ,
                        'Bartec' => $dateBartec
                    );
                }
            } else {
                return $this->protocolResult;
            }
        } catch (\mysqli_sql_exception $e) {
            $this->_logger->log($e->getMessage(), null, 'protocolDates.log');
        } catch (\Exception $e) {
            $this->_logger->log($e->getMessage(), null, 'protocolDates.log');
        }
        return array();
    }

    protected function getValue($result, $column) {
        return array_filter(array_column($result, $column));
    }

}
