<?php

namespace Inchoo\Search\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\App\ResourceConnection;
use Psr\Log\LoggerInterface;
use Inchoo\Search\Helper\Data as SearchDataHelper;

use Magento\Framework\App\Request\Http as RequestHttp;
use Magento\Framework\UrlInterface;

class Search extends AbstractHelper {

    protected $_dbConnection, $_logger, $_seachDataHelper, $_request, $_urlInterface;

    public function __construct(ResourceConnection $dbConnection, LoggerInterface $logger, SearchDataHelper $searchDataHelper, RequestHttp $request, UrlInterface $urlInterface) {
        $this->_dbConnection = $dbConnection;
        $this->_logger = $logger;
        
        $this->_seachDataHelper = $searchDataHelper;
        $this->_request = $request;
        $this->_urlInterface = $urlInterface;
    }

    public function getSearchForUrl($filterData, $categoryId) {
        
        if (isset($filterData['criteria'])) {
            $params = [
                'cat' => $categoryId,
                'year' => $filterData['getYear'],
                'make' => $filterData['getMake'],
                'model' => $filterData['getModel'],
                'submodel' => $filterData['getSubmodel'],
                'criteria' => $filterData['criteria']
            ];
        } else {
            $params = [
                'cat' => $categoryId,
                'year' => $filterData['getYear'],
                'make' => $filterData['getMake'],
                'model' => $filterData['getModel'],
                'submodel' => $filterData['getSubmodel']
            ];
        }
        
        return $this->_urlInterface->getUrl('coyote_search/results/for', ['_query' => $params]);
    }

    public function getSkus() {
        $dbConnection = $this->_seachDataHelper->dbConnection();

        $params = $this->_request->getParams();
        $year = (int) $params['year'];
        $make = (int) $params['make'];
        $model = (int) $params['model'];
        $submodel = (int) $params['submodel'];
        $criteria = isset($params['criteria']) ? $params['criteria'] : '';
        if ($criteria) {
            $result = $dbConnection->fetchAll(sprintf('SELECT p1.partnumber, p2.partnumber as LinkedPart'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.AdditionalCriteria_PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.AdditionalCriteria_PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' WHERE v.YearID=%d and v.MakeID=%d and v.ModelID=%d and v.SubModelID=%d and AdditionalCriteria_PartMasterID=%d', $year, $make, $model, $submodel, $criteria
            ));
        } else {
            $result = $dbConnection->fetchAll(sprintf('SELECT p1.partnumber, p2.partnumber as LinkedPart'
                            . ' FROM ' . \Inchoo\Search\Helper\Data::VWVEHICLESTPMS . ' v'
                            . ' LEFT OUTER JOIN partxref x on x.PartMasterID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p1 on p1.ID = v.PartMasterID'
                            . ' LEFT OUTER JOIN partmaster p2 on p2.ID = x.XREF_ID'
                            . ' WHERE v.YearID=%d and v.MakeID=%d and v.ModelID=%d and v.SubModelID=%d', $year, $make, $model, $submodel
            ));
        }

        $skus = array_unique(array_merge(array_column($result, 'partnumber'), array_column($result, 'LinkedPart')));
        $filters = [];
        foreach ($skus as $sku) {
            $filters[] = array('attribute' => 'sku', 'eq' => $sku);
        }
        return $filters;
    }

    public function getQueryParamsByUrl($url) {
        $query_str = parse_url($url, PHP_URL_QUERY);

        parse_str($query_str, $query_params);
        unset($query_params['cat']);
        unset($query_params['year']);
        unset($query_params['make']);
        unset($query_params['model']);
        unset($query_params['submodel']);
        unset($query_params['dir']);
        unset($query_params['limit']);
        unset($query_params['p']);
        unset($query_params['order']);
        unset($query_params['criteria']);
        return $query_params;
    }

    public function getDbConnection() {
        $dbConnection = $this->_seachDataHelper->dbConnection();

        return $dbConnection;
    }

}
