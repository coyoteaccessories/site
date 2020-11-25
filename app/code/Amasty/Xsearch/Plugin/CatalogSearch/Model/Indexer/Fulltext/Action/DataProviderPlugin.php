<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_Xsearch
 */


namespace Amasty\Xsearch\Plugin\CatalogSearch\Model\Indexer\Fulltext\Action;

use Magento\CatalogSearch\Model\Indexer\Fulltext\Action\DataProvider as MagentoDataProvider;
use Magento\Framework\App\ResourceConnection;
use Magento\Framework\DB\Select;
use Magento\Store\Model\StoreManager;
use Magento\Framework\App\Config\ScopeConfigInterface;

class DataProviderPlugin
{
    const TYPE_WEBSITE = 'website';

    /**
     * @var ResourceConnection
     */
    private $resource;

    /**
     * @var StoreManager
     */
    private $storeManager;

    /**
     * @var \Amasty\Xsearch\Model\Di\Wrapper
     */
    private $stockResolver;

    /**
     * @var \Amasty\Xsearch\Model\Di\Wrapper
     */
    private $defaultStockProvider;

    /**
     * @var \Amasty\Xsearch\Model\Di\Wrapper
     */
    private $stockIndexTableNameResolver;

    /**
     * @var ScopeConfigInterface
     */
    private $config;

    public function __construct(
        ResourceConnection $resourceConnection,
        StoreManager $storeManager,
        \Amasty\Xsearch\Model\Di\Wrapper $stockResolver,
        \Amasty\Xsearch\Model\Di\Wrapper $defaultStockProvider,
        \Amasty\Xsearch\Model\Di\Wrapper $stockIndexTableNameResolver,
        ScopeConfigInterface $config
    ) {
        $this->resource = $resourceConnection;
        $this->storeManager = $storeManager;
        $this->stockResolver = $stockResolver;
        $this->defaultStockProvider = $defaultStockProvider;
        $this->stockIndexTableNameResolver = $stockIndexTableNameResolver;
        $this->config = $config;
    }

    /**
     * Plugin cuts off products which, don't have stock data for current website. This action is necessary for
     * search request proper work.
     *
     * @param MagentoDataProvider $subject
     * @param array $result
     * @param $storeId
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function afterGetSearchableProducts(
        MagentoDataProvider $subject,
        array $result,
        string $storeId
    ): array {
        $manageStock = $this->config->getValue('cataloginventory/item_options/manage_stock');

        if ($manageStock) {
            $displayType = $this->config->getValue('cataloginventory/options/show_out_of_stock');
            $stockData = $this->getStockStatusData((int)$storeId, $this->getProductIds($result), !$displayType);
            if (count($stockData) > count($result)) {
                foreach ($result as $key => $data) {
                    if (!isset($stockData[$data['entity_id']])) {
                        unset($result[$key]);
                    }
                }
            }
        }

        return $result;
    }

    /**
     * @param array $products
     * @return array
     */
    private function getProductIds(array $products): array
    {
        return array_column($products, 'entity_id');
    }

    /**
     * @param int $storeId
     * @param array $productIds
     * @param bool $inStockFilter
     * @return array
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    private function getStockStatusData(int $storeId, array $productIds = [], bool $inStockFilter = false): array
    {
        $result = [];
        if (!empty($productIds)) {
            $stockId = $this->getStockId($storeId);

            if ($stockId === null || $stockId === $this->defaultStockProvider->getId()) {
                $select = $this->getDefaultStockSelect($productIds, $inStockFilter);
            } else {
                $select = $this->getMsiStockSelect($productIds, $stockId, $inStockFilter);
            }

            return $this->resource->getConnection()->fetchPairs($select);
        }

        return $result;
    }

    /**
     * @param int $storeId
     * @return int|null
     * @throws \Magento\Framework\Exception\LocalizedException
     * @throws \Magento\Framework\Exception\NoSuchEntityException
     */
    public function getStockId(int $storeId): ?int
    {
        $websiteId = $this->storeManager->getStore($storeId)->getWebsiteId();
        $websiteCode = $this->storeManager->getWebsite($websiteId)->getCode();
        $stock = $this->stockResolver->execute(self::TYPE_WEBSITE, $websiteCode);
        return $stock ? $stock->getStockId() : null;
    }

    /**
     * @param array $productIds
     * @param bool $inStockFilter
     * @return Select
     */
    public function getDefaultStockSelect(array $productIds, bool $inStockFilter = false): Select
    {
        $stockStatusTable = $this->resource->getTableName('cataloginventory_stock_status');
        $select = $this->resource->getConnection()->select()
            ->from($stockStatusTable, ['product_id', 'stock_status'])
            ->where('product_id in (?)', $productIds);
        if ($inStockFilter) {
            $select->where('stock_status = 1');
        }

        return $select;
    }

    /**
     * @param array $productIds
     * @param int $stockId
     * @param bool $inStockFilter
     * @return Select
     */
    public function getMsiStockSelect(array $productIds, int $stockId, bool $inStockFilter = false): Select
    {
        $stockIndexTableName = $this->stockIndexTableNameResolver->execute((int)$stockId);
        if (!$stockIndexTableName) {
            return $this->getDefaultStockSelect($productIds, $inStockFilter);
        }

        $productTable = $this->resource->getTableName('catalog_product_entity');
        $select = $this->resource->getConnection()->select()
            ->from(
                ['stock_index' => $stockIndexTableName],
                ['product_entity.entity_id', 'stock_index.is_salable']
            )
            ->joinInner(['product_entity' => $productTable], 'product_entity.sku = stock_index.sku', [])
            ->where('product_entity.entity_id in (?)', $productIds);
        if ($inStockFilter) {
            $select->where('stock_index.is_salable = 1');
        }

        return $select;
    }
}
