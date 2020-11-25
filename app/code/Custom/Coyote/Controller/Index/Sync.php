<?php

namespace Custom\Coyote\Controller\Index;

use Magento\Catalog\Model\ResourceModel\Product\CollectionFactory as ProductCollectionFactory;
use Psr\Log\LoggerInterface;
use Magento\Framework\App\ResourceConnection;
use Magento\Directory\Model\ResourceModel\Country\CollectionFactory as CountryCollectionFactory;
use Magento\Eav\Model\Config as EavConfig;

class Sync extends \Magento\Framework\App\Action\Action {

    const DB = 'revo';
    const QBDB = 'quickbook';

    protected $db_prefix = 'rscadmin_revo51';
    protected $qb_db_prefix = 'quickbook';
    protected $_dbs = [];
    protected $_pageFactory, $_products;
    protected $_resourceConnection;
    protected $_logger;
    protected $_countryCollectionFactory;
    protected $_eavConfig;

    public function __construct(
    \Magento\Framework\App\Action\Context $context,
            \Magento\Framework\View\Result\PageFactory $pageFactory,
            ProductCollectionFactory $products,
            ResourceConnection $resourceConnection, LoggerInterface $logger,
            CountryCollectionFactory $countryCollectionFactory,
            EavConfig $eavConfig) {
        $this->_pageFactory = $pageFactory;
        $this->_products = $products;
        $this->_resourceConnection = $resourceConnection;
        $this->_logger = $logger;
        $this->_countryCollectionFactory = $countryCollectionFactory;
        $this->_eavConfig = $eavConfig;

        return parent::__construct($context);
    }

    public function execute() {
        try {

            $readConnection = $this->_resourceConnection->getConnection();
            $writeConnection = $this->_resourceConnection->getConnection();

            $table = 'cron_temp';
            $pageNum = (int) $this->getRequest()->getParam('page');
            if ($pageNum) {
                $counter = [$pageNum];
            } else {
                $counter = $readConnection->fetchCol('SELECT counter FROM ' . $table);
            }

            if (count($counter) > 0) {
                echo 'Counter: ' . (int) $counter[0] . '<br/>';
            }

            $read = $this->getDbConnection(self::DB);
            $readQuickbook = $this->getDbConnection(self::QBDB);

//            $collectionnew = $this->_products->create();
//            $collectionnew->getCollection()->addAttributeToSort('entity_id', 'DESC');
//            $_collectionSizenew = $collectionnew->count();
//            $totalcount = round($_collectionSizenew / 200 + 1);

            $collection = $this->_products->create();
            $collection->addAttributeToSort('entity_id', 'DESC');
            if (count($counter) > 0) {
                $collection->setPage($counter[0], 200);
            }

            $totalcount = round($collection->count() / 200 + 1);

            foreach ($collection as $product) {
                echo 'ID: ' . $product->getId() . '<br />';
//                $product = Mage::getModel('catalog/product')->load($product->getId());
                $sku = addslashes($product->getSku());

                $product_web = $read->fetchAll('SELECT * FROM partmaster WHERE PartNumber = "' . $sku . '"');
                if (!count($product_web)) {
                    $this->insertProduct($product);
                } else {
                    $this->updateProduct($product, $product_web[0]['ID']);
                }

                $product_qb = $readQuickbook->fetchAll('SELECT * FROM ' . $this->qb_db_prefix . '.iteminventory WHERE Name = "' . $sku . '"');
                if (!count($product_qb)) {
                    $product_qb = $readQuickbook->fetchAll('SELECT * FROM ' . $this->qb_db_prefix . '.iteminventoryassembly WHERE Name = "' . $sku . '"');
                    if (!count($product_qb)) {
                        $this->insertQbProduct($product);
                    } else {
                        $this->updateQbProduct($product, $product_qb[0]['Name'], $product_qb[0]['Status'], 'iteminventoryassembly');
                    }
                } else {
                    $this->updateQbProduct($product, $product_qb[0]['Name'], $product_qb[0]['Status'], 'iteminventory');
                }
            }

            if ($counter[0] < $totalcount) {
                $nextCounter = $counter[0] + 1;
                $query = "UPDATE `cron_temp` SET counter = '$nextCounter'";
                $writeConnection->query($query);

                Custom_Coyote_Helper_Data::runItself();

                die;
            } else {
                $query = "UPDATE `cron_temp` SET counter=1";
                $writeConnection->query($query);
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            $this->_logger->critical($e->getMessage());
        }
    }

    protected function _dbName($connectionName = 'quickbook') {
        return $connectionName;
    }

    protected function _db($connectionName = 'quickbook') {
        return $this->_resourceConnection->getConnection($connectionName);
    }

    public function getDbConnection($connectionName = 'revo') {
        $dbConnection = $this->_resourceConnection->getConnection($connectionName);

        return $dbConnection;
    }

    private function updateProduct($product, $PartID) {
        $read = $write = $this->_db(self::DB);

        $PartNumber = $product->getSku();
        $Description = $product->getData('web_description');

        $PartClassID = $this->getClassId($product->getData('web_class'));
        $PartSubClassID = $this->getSubClassId($product->getData('web_subclass'), $PartClassID);
        $MakeID = 0;
        $Brands_ID = $this->getBrandId($product->getData('web_brand'));
        $CountryOfOrigin_ID = $this->getCountryId($product->getData('country_of_manufacture'));

        $Taxable = trim($product->getData('web_taxable')) == "" ? 0 : $product->getData('web_taxable');
        $DisplayInSubClass = trim($product->getData('web_sub_class_display')) == "" ? 0 : $product->getData('web_sub_class_display');
        $AvailForPurchase = trim($product->getData('web_avail_for_purchase')) == "" ? 0 : $product->getData('web_avail_for_purchase');
        $Active = $product->getData('status');

        $ImageDetail = $product->getData('image') == 'no_selection' ? '' : $product->getData('image');
        $ImageDetailInclOnWebpage = $product->getData('image') == 'no_selection' ? 0 : 1;
        $ImageDisplay = $product->getData('small_image') == 'no_selection' ? '' : $product->getData('small_image');
        $ImageDisplayInclOnWebPage = $product->getData('small_image') == 'no_selection' ? 0 : 1;
        $ImageThumb = $product->getData('thumbnail') == 'no_selection' ? '' : $product->getData('thumbnail');
        $ImageThumbInclOnWebPage = $product->getData('thumbnail') == 'no_selection' ? 0 : 1;

        $updateQry = "UPDATE partmaster SET 
				PartNumber = '" . $PartNumber . "',
				Description = '" . addslashes($Description) . "',
				PartClassID = '" . ($PartClassID == "" ? 0 : $PartClassID) . "',
				PartSubClassID = '" . ($PartSubClassID == "" ? 0 : $PartSubClassID) . "',
				MakeID = '" . ($MakeID == "" ? 0 : $MakeID) . "',
				Taxable = '" . $Taxable . "',
				Brands_ID = '" . ($Brands_ID == "" ? 0 : $Brands_ID) . "',
				" . ($CountryOfOrigin_ID != "" ? "CountryOfOrigin_ID = '" . $CountryOfOrigin_ID . "', " : '') . " 
				ImageDetail = '" . $ImageDetail . "',
				ImageDetailInclOnWebpage = '" . $ImageDetailInclOnWebpage . "',
				ImageDisplay = '" . $ImageDisplay . "',
				ImageDisplayInclOnWebPage = '" . $ImageDisplayInclOnWebPage . "',
				ImageThumb = '" . $ImageThumb . "',
				ImageThumbInclOnWebPage = '" . $ImageThumbInclOnWebPage . "',
				DisplayInSubClass = '" . $DisplayInSubClass . "',
				AvailForPurchase = '" . $AvailForPurchase . "',
				Active = '" . $Active . "'
			WHERE ID = " . $PartID;
        $this->log($updateQry);

        $write->query($updateQry);

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_0'), 'web_pack_qty_0');
        $PackageTypes_ID = $PackageTypes_ID == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_0'), 'web_label_format_0');
        $LabelFormat_ID = $LabelFormat_ID == "" ? 0 : $LabelFormat_ID;
        $Qty = $product->getData('web_level_0_qty') == "" ? 0 : $product->getData('web_level_0_qty');
        if ($product->getData('web_level_0_length') == 'Length') {
            $Length = 0.0;
        } else {
            $Length = trim($product->getData('web_level_0_length')) == "" ? 0.0 : $product->getData('web_level_0_length');
        }
        if ($product->getData('web_level_0_width') == 'Width') {
            $Width = 0.0;
        } else {
            $Width = trim($product->getData('web_level_0_width')) == "" ? 0.0 : $product->getData('web_level_0_width');
        }

        if ($product->getData('web_level_0_height') == 'Height') {
            $Height = 0.0;
        } else {
            $Height = trim($product->getData('web_level_0_height')) == "" ? 0.0 : $product->getData('web_level_0_height');
        }

        $Weight = trim($product->getData('weight')) == "" ? 0.0 : $product->getData('weight');
        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_0_cost')) == "" ? 0.000 : $product->getData('web_level_0_cost');
        $Price = trim($product->getData('price')) == "" ? 0.000 : $product->getData('price');
        $level_0 = $read->fetchAll('SELECT * FROM partmaster_packlevels WHERE PartMaster_ID = "' . $PartID . '" AND Level = 0');
        if (count($level_0) == 0) {
            $level_qry = "INSERT INTO partmaster_packlevels 
						(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, Weight, CubicFeet, Cost, Price) VALUES 
						('" . $PartID . "', '0', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . (str_replace(',', '', $Qty) * 1) . "', '" . $Length . "', '" . $Width . "', '" . $Height . "', '" . $Weight . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        } else {
            $level_qry = "UPDATE partmaster_packlevels SET 
				PackageTypes_ID = '" . $PackageTypes_ID . "', 
				LabelFormat_ID = '" . $LabelFormat_ID . "', 
				Qty = '" . (str_replace(',', '', $Qty) * 1) . "', 
				Length = '" . $Length . "', 
				Width = '" . $Width . "', 
				Height = '" . $Height . "', 
				Weight = '" . $Weight . "', 
				CubicFeet = '" . $CubicFeet . "', 
				Cost = '" . $Cost . "',
				Price = '" . $Price . "'
				WHERE PartMaster_ID = '" . $PartID . "' AND Level = 0";
        }
        $write->query($level_qry);

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_1'), 'web_pack_qty_1');
        $PackageTypes_ID = $PackageTypes_ID == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_1'), 'web_label_format_1');
        $LabelFormat_ID = $LabelFormat_ID == "" ? 0 : $LabelFormat_ID;
        $Qty = $product->getData('web_level_1_qty') == "" ? 0 : $product->getData('web_level_1_qty');
        if ($product->getData('web_level_1_length') == 'Length') {
            $Length = 0.0;
        } else {
            $Length = trim($product->getData('web_level_1_length')) == "" ? 0.0 : $product->getData('web_level_1_length');
        }
        if ($product->getData('web_level_1_width') == 'Width') {
            $Width = 0.0;
        } else {
            $Width = trim($product->getData('web_level_1_width')) == "" ? 0.0 : $product->getData('web_level_1_width');
        }

        if ($product->getData('web_level_1_height') == 'Height') {
            $Height = 0.0;
        } else {
            $Height = trim($product->getData('web_level_1_height')) == "" ? 0.0 : $product->getData('web_level_1_height');
        }

        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_1_cost')) == "" ? 0.000 : $product->getData('web_level_1_cost');
        $Price = trim($product->getData('web_level_1_price')) == "" ? 0.000 : $product->getData('web_level_1_price');
        $level_1 = $read->fetchAll('SELECT * FROM partmaster_packlevels WHERE PartMaster_ID = "' . $PartID . '" AND Level = 1');
        if (!count($level_1)) {
            $level_qry = "INSERT INTO partmaster_packlevels 
				(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, CubicFeet, Cost, Price) VALUES 
				('" . $PartID . "', '1', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . (str_replace(',', '', $Qty) * 1)
                    . "', '" . $Length . "', '" . $Width . "', '" . $Height . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        } else {
            $level_qry = "UPDATE partmaster_packlevels SET 
				PackageTypes_ID = '" . $PackageTypes_ID . "', 
				LabelFormat_ID = '" . $LabelFormat_ID . "', 
				Qty = '" . (str_replace(',', '', $Qty) * 1) . "', 
				Length = '" . $Length . "', 
				Width = '" . $Width . "', 
				Height = '" . $Height . "',  
				CubicFeet = '" . $CubicFeet . "', 
				Cost = '" . $Cost . "',
				Price = '" . $Price . "'
				WHERE PartMaster_ID = '" . $PartID . "' AND Level = 1
			";
        }
        $this->log($level_qry);
        $write->query($level_qry);

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_2'), 'web_pack_qty_2');
        $PackageTypes_ID = $PackageTypes_ID == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_2'), 'web_label_format_2');
        $LabelFormat_ID = $LabelFormat_ID == "" ? 0 : $LabelFormat_ID;
        $Qty = $product->getData('web_level_2_qty') == "" ? 0 : $product->getData('web_level_2_qty');
        if ($product->getData('web_level_2_length') == 'Length') {
            $Length = 0.0;
        } else {
            $Length = trim($product->getData('web_level_2_length')) == "" ? 0.0 : $product->getData('web_level_2_length');
        }
        if ($product->getData('web_level_2_width') == 'Width') {
            $Width = 0.0;
        } else {
            $Width = trim($product->getData('web_level_2_width')) == "" ? 0.0 : $product->getData('web_level_2_width');
        }

        if ($product->getData('web_level_2_height') == 'Height') {
            $Height = 0.0;
        } else {
            $Height = trim($product->getData('web_level_2_height')) == "" ? 0.0 : $product->getData('web_level_2_height');
        }
        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_2_cost')) == "" ? 0.000 : $product->getData('web_level_2_cost');
        $Price = trim($product->getData('web_level_2_price')) == "" ? 0.000 : $product->getData('web_level_2_price');
        $level_2 = $read->fetchAll('SELECT * FROM partmaster_packlevels WHERE PartMaster_ID = "' . $PartID . '" AND Level = 2');
        if (count($level_2) == 0) {
            $level_qry = "INSERT INTO partmaster_packlevels 
				(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, CubicFeet, Cost, Price) VALUES 
				('" . $PartID . "', '2', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . (str_replace(',', '', $Qty) * 1)
                    . "', '" . $Length . "', '" . $Width . "', '" . $Height . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        } else {
            $level_qry = "UPDATE partmaster_packlevels SET 
				PackageTypes_ID = '" . $PackageTypes_ID . "', 
				LabelFormat_ID = '" . $LabelFormat_ID . "', 
				Qty = '" . (str_replace(',', '', $Qty) * 1) . "', 
				Length = '" . $Length . "', 
				Width = '" . $Width . "', 
				Height = '" . $Height . "', 
				CubicFeet = '" . $CubicFeet . "', 
				Cost = '" . $Cost . "',
				Price = '" . $Price . "'
				WHERE PartMaster_ID = '" . $PartID . "' AND Level = 2
			";
        }
        $this->log($level_qry);
        $write->query($level_qry);

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_3'), 'web_pack_qty_3');
        $PackageTypes_ID = $PackageTypes_ID == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_3'), 'web_label_format_3');
        $LabelFormat_ID = $LabelFormat_ID == "" ? 0 : $LabelFormat_ID;
        $Qty = $product->getData('web_level_3_qty') == "" ? 0 : $product->getData('web_level_3_qty');
        if ($product->getData('web_level_3_length') == 'Length') {
            $Length = 0.0;
        } else {
            $Length = trim($product->getData('web_level_3_length')) == "" ? 0.0 : $product->getData('web_level_3_length');
        }
        if ($product->getData('web_level_3_width') == 'Width') {
            $Width = 0.0;
        } else {
            $Width = trim($product->getData('web_level_3_width')) == "" ? 0.0 : $product->getData('web_level_3_width');
        }

        if ($product->getData('web_level_3_height') == 'Height') {
            $Height = 0.0;
        } else {
            $Height = trim($product->getData('web_level_3_height')) == "" ? 0.0 : $product->getData('web_level_3_height');
        }
        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_3_cost')) == "" ? 0.000 : $product->getData('web_level_3_cost');
        $Price = trim($product->getData('web_level_3_price')) == "" ? 0.000 : $product->getData('web_level_3_price');
        $level_3 = $read->fetchAll('SELECT * FROM partmaster_packlevels WHERE PartMaster_ID = "' . $PartID . '" AND Level = 3');
        if (count($level_3) == 0) {
            $level_qry = "INSERT INTO partmaster_packlevels 
				(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, CubicFeet, Cost, Price) VALUES 
				('" . $PartID . "', '3', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . (str_replace(',', '', $Qty) * 1)
                    . "', '" . $Length . "', '" . $Width . "', '" . $Height . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        } else {
            $level_qry = "UPDATE partmaster_packlevels SET 
				PackageTypes_ID = '" . $PackageTypes_ID . "', 
				LabelFormat_ID = '" . $LabelFormat_ID . "', 
				Qty = '" . (str_replace(',', '', $Qty) * 1) . "', 
				Length = '" . $Length . "', 
				Width = '" . $Width . "', 
				Height = '" . $Height . "', 
				CubicFeet = '" . $CubicFeet . "', 
				Cost = '" . $Cost . "',
				Price = '" . $Price . "'
				WHERE PartMaster_ID = '" . $PartID . "' AND Level = 3
			";
        }
        $this->log($level_qry);
        $write->query($level_qry);

        $this->addProductLog($product, 'update', $this->_dbName(self::DB));
    }

    private function insertProduct($product) {
        $write = $this->_db(self::DB);

        $PartNumber = $product->getSku();
        $Description = $product->getData('web_description');

        $PartClassID = $this->getClassId($product->getData('web_class'));
        $PartSubClassID = $this->getSubClassId($product->getData('web_subclass'), $PartClassID);
        $MakeID = 0;
        $LongDescription = '';

        $Brands_ID = $this->getBrandId($product->getData('web_brand'));
        $CountryOfOrigin_ID = $this->getCountryId($product->getData('country_of_manufacture'));

        $Taxable = trim($product->getData('web_taxable')) == "" ? 0 : $product->getData('web_taxable');
        $DisplayInSubClass = trim($product->getData('web_sub_class_display')) == "" ? 0 : $product->getData('web_sub_class_display');
        $AvailForPurchase = trim($product->getData('web_avail_for_purchase')) == "" ? 0 : $product->getData('web_avail_for_purchase');
        $Active = $product->getData('status');

        $ImageDetail = $product->getData('image') == 'no_selection' ? '' : $product->getData('image');
        $ImageDetailInclOnWebpage = $product->getData('image') == 'no_selection' ? 0 : 1;
        $ImageDisplay = $product->getData('small_image') == 'no_selection' ? '' : $product->getData('small_image');
        $ImageDisplayInclOnWebPage = $product->getData('small_image') == 'no_selection' ? 0 : 1;
        $ImageThumb = $product->getData('thumbnail') == 'no_selection' ? '' : $product->getData('thumbnail');
        $ImageThumbInclOnWebPage = $product->getData('thumbnail') == 'no_selection' ? 0 : 1;

        $insertQry = "INSERT INTO partmaster 
			(PartNumber, Description, PartClassID, PartSubClassID, MakeID, Taxable, Brands_ID, "
                . ($CountryOfOrigin_ID != "" ? "CountryOfOrigin_ID, " : '') . " LongDescription, ImageDetail, 
					ImageDetailInclOnWebpage, ImageDisplay, ImageDisplayInclOnWebPage, ImageThumb, 
					ImageThumbInclOnWebPage, DisplayInSubClass, AvailForPurchase, Active)
				VALUES ('" . $PartNumber . "', '" . addslashes($Description) . "', '"
                . ($PartClassID == "" ? 0 : $PartClassID) . "', '"
                . ($PartSubClassID == "" ? 0 : $PartSubClassID) . "', '"
                . ($MakeID == "" ? 0 : $MakeID) . "', '"
                . $Taxable . "', '" . ($Brands_ID == "" ? 0 : $Brands_ID) . "', "
                . ($CountryOfOrigin_ID != "" ? "'" . $CountryOfOrigin_ID . "', " : '') . " '" . $LongDescription . "', '"
                . $ImageDetail . "', '" . $ImageDetailInclOnWebpage . "', '" . $ImageDisplay . "', '" . $ImageDisplayInclOnWebPage . "', '"
                . $ImageThumb . "', '" . $ImageThumbInclOnWebPage . "', '" . $DisplayInSubClass . "', '" . $AvailForPurchase . "', '"
                . $Active . "')";
        $write->query($insertQry);
        $PartID = $write->lastInsertId();

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_0'), 'web_pack_qty_0');
        $PackageTypes_ID = $PackageTypes_ID == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_0'), 'web_label_format_0');
        $LabelFormat_ID = $LabelFormat_ID == "" ? 0 : $LabelFormat_ID;
        $Qty = $product->getData('web_level_0_qty') == "" ? 0 : $product->getData('web_level_0_qty');
        $Length = $product->getData('web_level_0_length') == "" ? 0.0 : $product->getData('web_level_0_length');
        $Width = $product->getData('web_level_0_width') == "" ? 0.0 : $product->getData('web_level_0_width');
        $Height = $product->getData('web_level_0_height') == "" ? 0.0 : $product->getData('web_level_0_height');
        $Weight = $product->getData('weight') == "" ? 0.0 : $product->getData('weight');
        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_0_cost')) == "" ? 0.000 : $product->getData('web_level_0_cost');
        $Price = trim($product->getData('price')) == "" ? 0.000 : $product->getData('price');
        $level_inser_qry = "INSERT INTO partmaster_packlevels 
			(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, Weight, CubicFeet, Cost, Price)
			VALUES ('" . $PartID . "', '0', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . ($Qty != "" ? $Qty : 0 ) . "', '"
                . $Length . "', '" . $Width . "', '" . $Height . "', '" . $Weight . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        $this->log($level_inser_qry);
        $write->query($level_inser_qry);

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_1'), 'web_pack_qty_1');
        $PackageTypes_ID = trim($PackageTypes_ID) == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_1'), 'web_label_format_1');
        $LabelFormat_ID = trim($LabelFormat_ID) == "" ? 0 : $LabelFormat_ID;
        $Qty = trim($product->getData('web_level_1_qty')) == "" ? 0 : $product->getData('web_level_1_qty');
        $Length = trim($product->getData('web_level_1_length')) == "" ? 0.0 : $product->getData('web_level_1_length');
        $Width = trim($product->getData('web_level_1_width')) == "" ? 0.0 : $product->getData('web_level_1_width');
        $Height = trim($product->getData('web_level_1_height')) == "" ? 0.0 : $product->getData('web_level_1_height');
        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_1_cost')) == "" ? 0.000 : $product->getData('web_level_1_cost');
        $Price = trim($product->getData('web_level_1_price')) == "" ? 0.000 : $product->getData('web_level_1_price');
        $level_inser_qry = "INSERT INTO partmaster_packlevels 
			(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, CubicFeet, Cost, Price) VALUES 
			('" . $PartID . "', '1', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . (str_replace(',', '', $Qty) * 1) . "', '"
                . $Length . "', '" . $Width . "', '" . $Height . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        $this->log($level_inser_qry);
        $write->query($level_inser_qry);

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_2'), 'web_pack_qty_2');
        $PackageTypes_ID = $PackageTypes_ID == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_2'), 'web_label_format_2');
        $LabelFormat_ID = $LabelFormat_ID == "" ? 0 : $LabelFormat_ID;
        $Qty = trim($product->getData('web_level_2_qty')) == "" ? 0 : $product->getData('web_level_2_qty');
        $Length = trim($product->getData('web_level_2_length')) == "" ? 0.0 : $product->getData('web_level_2_length');
        $Width = trim($product->getData('web_level_2_width')) == "" ? 0.0 : $product->getData('web_level_2_width');
        $Height = trim($product->getData('web_level_2_height')) == "" ? 0.0 : $product->getData('web_level_2_height');
        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_2_cost')) == "" ? 0.000 : $product->getData('web_level_2_cost');
        $Price = trim($product->getData('web_level_2_price')) == "" ? 0.000 : $product->getData('web_level_2_price');
        $level_inser_qry = "INSERT INTO partmaster_packlevels 
			(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, CubicFeet, Cost, Price) VALUES 
			('" . $PartID . "', '2', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . (str_replace(',', '', $Qty) * 1) . "', '"
                . $Length . "', '" . $Width . "', '" . $Height . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        $this->log($level_inser_qry);
        $write->query($level_inser_qry);

        $PackageTypes_ID = $this->getPackageTypesID($product->getData('web_pack_qty_3'), 'web_pack_qty_3');
        $PackageTypes_ID = $PackageTypes_ID == "" ? 0 : $PackageTypes_ID;
        $LabelFormat_ID = $this->getLabelFormatID($product->getData('web_label_format_3'), 'web_label_format_3');
        $LabelFormat_ID = $LabelFormat_ID == "" ? 0 : $LabelFormat_ID;
        $Qty = trim($product->getData('web_level_3_qty')) == "" ? 0 : $product->getData('web_level_3_qty');
        $Length = trim($product->getData('web_level_3_length')) == "" ? 0.0 : $product->getData('web_level_3_length');
        $Width = trim($product->getData('web_level_3_width')) == "" ? 0.0 : $product->getData('web_level_3_width');
        $Height = trim($product->getData('web_level_3_height')) == "" ? 0.0 : $product->getData('web_level_3_height');
        $CubicFeet = $Length * $Width * $Height / 1728;
        $CubicFeet = $CubicFeet == 0 ? '0.0000' : $CubicFeet;
        $Cost = trim($product->getData('web_level_3_cost')) == "" ? 0.000 : $product->getData('web_level_3_cost');
        $Price = trim($product->getData('web_level_3_price')) == "" ? 0.000 : $product->getData('web_level_3_price');
        $level_inser_qry = "INSERT INTO partmaster_packlevels 
			(PartMaster_ID, Level, PackageTypes_ID, LabelFormat_ID, Qty, Length, Width, Height, CubicFeet, Cost, Price) VALUES 
			('" . $PartID . "', '3', '" . $PackageTypes_ID . "', '" . $LabelFormat_ID . "', '" . (str_replace(',', '', $Qty) * 1) . "', '"
                . $Length . "', '" . $Width . "', '" . $Height . "', '" . $CubicFeet . "', '" . $Cost . "', '" . $Price . "');";
        $this->log($level_inser_qry);
        $write->query($level_inser_qry);

        $this->addProductLog($product, 'insert', $this->_dbName(self::DB));
    }

    private function insertQbProduct($product) {
        $Name = $product->getSku();
        $FullName = $product->getSku();
        $PurchaseDesc = $product->getName();
        $SalesDesc = $product->getName();
        $IsActive = $product->getData('status') ? 'true' : 'false';
        $SalesPrice = trim($product->getData('price')) == "" ? 0.000 : $product->getData('price'); //price
        $PurchaseCost = trim($product->getData('web_level_0_cost')) == "" ? 0.000 : $product->getData('web_level_0_cost'); //cost

        $CustomField1 = $this->getClassId($product->getData('web_class'));
        $CustomField11 = $this->getSubClassId($product->getData('web_subclass'), $CustomField1);
        $CustomField5 = trim($product->getData('web_level_1_qty')) == "" ? 0 : $product->getData('web_level_1_qty');
        ; // Level 1 Qty
        $CustomField7 = trim($product->getData('web_level_2_qty')) == "" ? 0 : $product->getData('web_level_2_qty');
        ; // Level 2 Qty
        $CustomField9 = trim($product->getData('web_level_3_qty')) == "" ? 0 : $product->getData('web_level_3_qty');
        ; // Level 3 Qty
        $CustomField4 = trim($product->getData('weight')) == "" ? 0.0 : $product->getData('weight'); // Level 0 Weight
        $CustomField6 = trim($product->getData('import_min')) == "" ? 0.0 : $product->getData('import_min'); // Import Min
        $CustomField8 = trim($product->getData('import_max')) == "" ? 0.0 : $product->getData('import_max'); // Import Max
        $CustomField10 = trim($product->getData('web_level_3_weight')) == "" ? 0.0 : $product->getData('web_level_3_weight'); // Level 3 Weight
        $CustomField13 = trim($product->getData('web_level_0_qty')) == "" ? 0 : $product->getData('web_level_0_qty');
        ; // Level 0 Qty
        $CustomField14 = trim($product->getData('local_min')) == "" ? 0 : $product->getData('local_min');
        ; // Local Min
        $CustomField15 = trim($product->getData('local_max')) == "" ? 0 : $product->getData('local_max');
        ; // Local Max

        $CustomField3 = trim($product->getData('web_drawing_no')) == "" ? "" : $product->getData('web_drawing_no'); // web_drawing_no

        $COGSAccountDetails = $this->getCogsAccount($product->getData('web_cogs_account'));
        $COGSAccountRef_ListID = isset($COGSAccountDetails['ListID']) ? $COGSAccountDetails['ListID'] : '';
        $COGSAccountRef_FullName = isset($COGSAccountDetails['FullName']) ? $COGSAccountDetails['FullName'] : '';

        $manufacturerDetails = $this->getManufacturer($product->getData('manufacturer'));
        $PrefVendorRef_ListID = isset($manufacturerDetails['ListID']) ? $manufacturerDetails['ListID'] : '';
        $PrefVendorRef_FullName = isset($manufacturerDetails['Name']) ? $manufacturerDetails['Name'] : '';

        $unitOfMeasureSetRefDetails = $this->getUnitOfMeasureSetRef($product->getData('web_pack_qty_0'), 'web_pack_qty_0');
        $UnitOfMeasureSetRef_ListID = 'NULL';
        $UnitOfMeasureSetRef_FullName = isset($unitOfMeasureSetRefDetails['Name']) ? $unitOfMeasureSetRefDetails['Name'] : ''; //Level 1 Package Type

        $salestaxcodeDetails = $this->getSalestaxcode($product->getData('web_tax_code'));
        $SalesTaxCodeRef_ListID = isset($salestaxcodeDetails['ListID']) ? $salestaxcodeDetails['ListID'] : '';
        $SalesTaxCodeRef_FullName = isset($salestaxcodeDetails['Name']) ? $salestaxcodeDetails['Name'] : '';

        $IncomeAccountDetails = $this->getIncomeAccount($product->getData('web_income_account'));
        $IncomeAccountRef_ListID = isset($IncomeAccountDetails['ListID']) ? $IncomeAccountDetails['ListID'] : '';
        $IncomeAccountRef_FullName = isset($IncomeAccountDetails['FullName']) ? $IncomeAccountDetails['FullName'] : '';

        $AssetAccountDetails = $this->getAssetAccount($product->getData('web_asset_account'));
        $AssetAccountRef_ListID = isset($AssetAccountDetails['ListID']) ? $AssetAccountDetails['ListID'] : '';
        $AssetAccountRef_FullName = isset($AssetAccountDetails['FullName']) ? $AssetAccountDetails['FullName'] : '';

        $product_type = $this->getProductType($product->getData('web_product_type'), 'web_product_type');
        $product_type = $product_type == "" ? 'Parts' : $product_type;
        $TimeCreated = date("m/d/Y g:i:s A", strtotime($product->getCreatedAt()));
        $TimeModified = date("m/d/Y g:i:s A", strtotime($product->getCreatedAt()));
        $ListID = "MAGENTO-" . time() . rand(10, 1000);
        $inser_qry = "INSERT INTO " . $this->qb_db_prefix . "." . ($product_type == 'Parts' ? 'iteminventory' : 'iteminventoryassembly') . " 
			(ListID, UnitOfMeasureSetRef_ListID, UnitOfMeasureSetRef_FullName, TimeCreated,TimeModified, Name,
				FullName, PurchaseDesc, SalesDesc, IsActive, SalesPrice, PurchaseCost, CustomField1, CustomField3,
				CustomField11, CustomField5, CustomField7, CustomField9, CustomField13, CustomField4, CustomField6,
				CustomField8, CustomField14, CustomField15, CustomField10, COGSAccountRef_ListID,
				COGSAccountRef_FullName, PrefVendorRef_ListID, PrefVendorRef_FullName,
				SalesTaxCodeRef_ListID, SalesTaxCodeRef_FullName, IncomeAccountRef_ListID,
				IncomeAccountRef_FullName,  AssetAccountRef_ListID,  AssetAccountRef_FullName,
				Status)
			VALUES 
			('" . $ListID . "', NULL, '" . addslashes($UnitOfMeasureSetRef_FullName) . "', '" . $TimeCreated . "', '"
                . $TimeModified . "', '" . addslashes($Name) . "', '" . addslashes($FullName) . "', '" . addslashes($PurchaseDesc)
                . "', '" . addslashes($SalesDesc) . "', '" . $IsActive . "', '" . $SalesPrice . "', '" . $PurchaseCost . "', '"
                . $CustomField1 . "', '" . $CustomField3 . "', '" . $CustomField11 . "', '" . $CustomField5 . "', '" . $CustomField7
                . "', '" . $CustomField9 . "', '" . $CustomField13 . "', '" . $CustomField4 . "', '" . $CustomField6 . "', '"
                . $CustomField8 . "', '" . $CustomField14 . "', '" . $CustomField15 . "', '" . $CustomField10 . "', '"
                . $COGSAccountRef_ListID . "', '" . addslashes($COGSAccountRef_FullName) . "', '" . $PrefVendorRef_ListID . "', '"
                . addslashes($PrefVendorRef_FullName) . "', '" . $SalesTaxCodeRef_ListID . "', '"
                . addslashes($SalesTaxCodeRef_FullName) . "', '" . $IncomeAccountRef_ListID . "', '"
                . addslashes($IncomeAccountRef_FullName) . "', '" . $AssetAccountRef_ListID . "', '"
                . addslashes($AssetAccountRef_FullName) . "', 'ADD')";
        self::log($inser_qry);

        $this->_db(self::QBDB)->query($inser_qry);

        $this->addProductLog($product, 'insert', $this->_dbName(self::QBDB));
    }

    private function updateQbProduct($product, $PartID, $status,
            $table = 'iteminventory') {
        $Name = $product->getSku();
        $FullName = $product->getSku();
        $PurchaseDesc = $product->getName();
        $SalesDesc = $product->getName();
        $IsActive = $product->getData('status') ? 'true' : 'false';
        $SalesPrice = trim($product->getData('price')) == "" ? 0.000 : $product->getData('price'); //price
        $PurchaseCost = trim($product->getData('web_level_0_cost')) == "" ? 0.000 : $product->getData('web_level_0_cost'); //cost

        $CustomField1 = $this->getClassId($product->getData('web_class'));
        $CustomField11 = $this->getSubClassId($product->getData('web_subclass'), $CustomField1);
        $CustomField5 = trim($product->getData('web_level_1_qty')) == "" ? 0 : $product->getData('web_level_1_qty');
        ; // Level 1 Qty
        $CustomField7 = trim($product->getData('web_level_2_qty')) == "" ? 0 : $product->getData('web_level_2_qty');
        ; // Level 2 Qty
        $CustomField9 = trim($product->getData('web_level_3_qty')) == "" ? 0 : $product->getData('web_level_3_qty');
        ; // Level 3 Qty
        $CustomField13 = trim($product->getData('web_level_0_qty')) == "" ? 0 : $product->getData('web_level_0_qty');
        ; // Level 0 Qty
        $CustomField14 = trim($product->getData('local_min')) == "" ? 0 : $product->getData('local_min');
        ; // Local Min
        $CustomField15 = trim($product->getData('local_max')) == "" ? 0 : $product->getData('local_max');
        ; // Local Max
        $CustomField4 = trim($product->getData('weight')) == "" ? 0.0 : $product->getData('weight'); // Level 0 Weight
        $CustomField6 = trim($product->getData('import_min')) == "" ? 0.0 : $product->getData('import_min'); // Import Min
        $CustomField8 = trim($product->getData('import_max')) == "" ? 0.0 : $product->getData('import_max'); // Level 2 Weight
        $CustomField10 = trim($product->getData('web_level_3_weight')) == "" ? 0.0 : $product->getData('web_level_3_weight'); // Level 3 Weight
        $CustomField3 = $product->getData('web_drawing_no') == "" ? "" : $product->getData('web_drawing_no'); // web_drawing_no

        $COGSAccountDetails = $this->getCogsAccount($product->getData('web_cogs_account'));
        $COGSAccountRef_ListID = isset($COGSAccountDetails['ListID']) ? $COGSAccountDetails['ListID'] : '';
        $COGSAccountRef_FullName = isset($COGSAccountDetails['FullName']) ? $COGSAccountDetails['FullName'] : '';

        $manufacturerDetails = $this->getManufacturer($product->getData('manufacturer'));
        $PrefVendorRef_ListID = isset($manufacturerDetails['ListID']) ? $manufacturerDetails['ListID'] : '';
        $PrefVendorRef_FullName = isset($manufacturerDetails['Name']) ? $manufacturerDetails['Name'] : '';

        $unitOfMeasureSetRefDetails = $this->getUnitOfMeasureSetRef($product->getData('web_pack_qty_0'), 'web_pack_qty_0');
        $UnitOfMeasureSetRef_ListID = 'NULL';
        $UnitOfMeasureSetRef_FullName = isset($unitOfMeasureSetRefDetails['Name']) ? $unitOfMeasureSetRefDetails['Name'] : ''; //Level 1 Package Type

        $salestaxcodeDetails = $this->getSalestaxcode($product->getData('web_tax_code'));
        $SalesTaxCodeRef_ListID = isset($salestaxcodeDetails['ListID']) ? $salestaxcodeDetails['ListID'] : '';
        $SalesTaxCodeRef_FullName = isset($salestaxcodeDetails['Name']) ? $salestaxcodeDetails['Name'] : '';

        $IncomeAccountDetails = $this->getIncomeAccount($product->getData('web_income_account'));
        $IncomeAccountRef_ListID = isset($IncomeAccountDetails['ListID']) ? $IncomeAccountDetails['ListID'] : '';
        $IncomeAccountRef_FullName = isset($IncomeAccountDetails['FullName']) ? $IncomeAccountDetails['FullName'] : '';

        $AssetAccountDetails = $this->getAssetAccount($product->getData('web_asset_account'));
        $AssetAccountRef_ListID = isset($AssetAccountDetails['ListID']) ? $AssetAccountDetails['ListID'] : '';
        $AssetAccountRef_FullName = isset($AssetAccountDetails['FullName']) ? $AssetAccountDetails['FullName'] : '';
        $TimeCreated = date("m/d/Y g:i:s A", strtotime($product->getCreatedAt()));
        $TimeModified = date("m/d/Y g:i:s A", strtotime($product->getUpdatedAt()));
        $updateQry = "UPDATE " . $this->qb_db_prefix . "." . $table . " SET 
				EditSequence = '',
				TimeCreated = '" . $TimeCreated . "',
				TimeModified = '" . $TimeModified . "',
				Name = '" . addslashes($Name) . "',
				FullName = '" . addslashes($FullName) . "',
				PurchaseDesc = '" . addslashes($PurchaseDesc) . "',
				SalesDesc = '" . addslashes($SalesDesc) . "',
				IsActive = '" . $IsActive . "',
				SalesPrice = '" . $SalesPrice . "',
				PurchaseCost = '" . $PurchaseCost . "',
				CustomField1 = '" . $CustomField1 . "',
				CustomField3 = '" . $CustomField3 . "',
				CustomField11 = '" . $CustomField11 . "',
				CustomField5 = '" . $CustomField5 . "',
				CustomField13 = '" . $CustomField13 . "',
				CustomField7 = '" . $CustomField7 . "',
				CustomField14 = '" . $CustomField14 . "',
				CustomField15 = '" . $CustomField15 . "',
				CustomField9 = '" . $CustomField9 . "',
				CustomField4 = '" . $CustomField4 . "',
				CustomField6 = '" . $CustomField6 . "',
				CustomField8 = '" . $CustomField8 . "',
				CustomField10 = '" . $CustomField10 . "',
				COGSAccountRef_ListID = '" . $COGSAccountRef_ListID . "',
				COGSAccountRef_FullName = '" . addslashes($COGSAccountRef_FullName) . "',
				UnitOfMeasureSetRef_ListID = $UnitOfMeasureSetRef_ListID,
				UnitOfMeasureSetRef_FullName = '" . addslashes($UnitOfMeasureSetRef_FullName) . "',
				PrefVendorRef_ListID = '" . $PrefVendorRef_ListID . "',
				PrefVendorRef_FullName = '" . addslashes($PrefVendorRef_FullName) . "',
				SalesTaxCodeRef_ListID = '" . $SalesTaxCodeRef_ListID . "',
				SalesTaxCodeRef_FullName = '" . addslashes($SalesTaxCodeRef_FullName) . "',
				IncomeAccountRef_ListID = '" . $IncomeAccountRef_ListID . "',
				IncomeAccountRef_FullName = '" . addslashes($IncomeAccountRef_FullName) . "',
				AssetAccountRef_ListID = '" . $AssetAccountRef_ListID . "',
				AssetAccountRef_FullName = '" . addslashes($AssetAccountRef_FullName) . "',
				Status = '" . ($status == 'ADD' ? 'ADD' : 'UPDATE') . "'
			WHERE Name = '" . $product->getSku() . "'";
        $this->log($updateQry);

        $this->_db(self::QBDB)->query($updateQry);

        $this->addProductLog($product, 'update', $this->_dbName(self::QBDB));
    }

    public function addProductLog($product, $action, $dbname) {
        $sku = $product->getSku();
        $product_id = $product->getId();
        $added_data = serialize($product->getData());
        $added_data = addslashes($added_data);
        try {
            $this->_resourceConnection->getConnection()
                    ->query("INSERT INTO `external_product_log`(`product_sku`,`product_id`,`added_data`,`status`,`database`)"
                            . " VALUES('{$sku}','{$product_id}','{$added_data}','{$action}','{$dbname}')");
        } catch (\Exception $e) {
            $this->_logger->critical($e->getMessage());
        }
    }

    private function log($sql) {
        $this->_logger->notice($sql . "\n;\n\n");
    }

    private function getClassId($value) {
        if ($value) {
            $read = $write = $this->_db(self::DB);
            $ID = $read->fetchCol('SELECT ID FROM partclass WHERE magento_id = "' . $value . '"');
            if (!count($ID)) {
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');

                $attribute_code = $attribute_model->getIdByCode('catalog_product', 'web_class');
                $attribute = $attribute_model->load($attribute_code);
                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);
                foreach ($options as $option) {
                    if ($option['value'] == $value) {
                        $insertQry = "INSERT INTO partclass (QBClassID, Active, ProductTree, WebHTML, SampleImage, Description, magento_id)"
                                . " VALUES (0, 1, 0, '', '', '" . $option['label'] . "', '" . $option['value'] . "')";
                        $write->query($insertQry);
                        return $write->lastInsertId();
                    }
                }
            }
            return $ID[0];
        }
        return '';
    }

    private function getSubClassId($value, $partClassID = null) {
        if ($value) {
            $read = $write = $this->_db(self::DB);
            $ID = $read->fetchCol(sprintf('SELECT ID FROM partsubclass WHERE magento_id=%d', $value));
            if (!count($ID)) {
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attributeId = $attribute_model->getIdByCode('catalog_product', 'web_subclass');
                $attribute = $attribute_model->load($attributeId);
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table')
                        ->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);
                foreach ($options as $option) {
                    if ($option['value'] == $value) {
                        $sql = sprintf('SELECT ID FROM partsubclass WHERE magento_id=0 AND Description="%s"'
                                . ($partClassID ? sprintf(' AND PartClassID=%d', $partClassID) : ''), addslashes($option['label'])
                        );
                        $id = $read->fetchOne($sql);

                        if ($id) {
                            $sql = sprintf('UPDATE partsubclass SET magento_id=%d WHERE ID=%d', $option['value'], $id
                            );
                            $this->log($sql);
                            $write->query($sql);
                        } else {
                            $sql = sprintf('INSERT INTO partsubclass (PartClassID, Description, magento_id, RollupAppData, Active, ProductTree) '
                                    . ' VALUES (%d, "%s", %d, 1, 1, 1)', $partClassID, addslashes($option['label']), addslashes($option['value'])
                            );
                            $this->log($sql);
                            $write->query($sql);
                            $id = $write->lastInsertId();
                            echo sprintf('partsubclass: inserted missing option value: PartClassID=%d, Description="%s", magento_id=%d, ID=%d<br/>', $partClassID, htmlspecialchars($option['label']), $option['value'], $id
                            );
                        }
                        return $id;
                    }
                }
            }
            return $ID[0];
        }
        return '';
    }

    private function getBrandId($brandId) {
        if ($brandId) {
            $write = $this->_db(self::DB);
            $ID = $write->fetchCol('SELECT ID FROM brands'
                    . ' WHERE magento_id = "' . (int) $brandId . '"');

            if (!count($ID)) {
                $attribute_model = Mage::getModel('eav/entity_attribute');
                $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');
                $attribute_code = $attribute_model->getIdByCode('catalog_product', 'web_brand');
                $attribute = $attribute_model->load($attribute_code);
                $attribute_options_model->setAttribute($attribute);
                $options = $attribute_options_model->getAllOptions(false);
                foreach ($options as $option) {
                    if ($option['value'] == $brandId) {
                        $insertQry = "INSERT INTO brands (Description, magento_id)"
                                . " VALUES ('" . addslashes($option['label']) . "', '" . addslashes($option['value']) . "')";
                        $write->query($insertQry);
                        return $write->lastInsertId();
                    }
                }
            }
            return $ID[0];
        }
        return '';
    }

    private function getCountryId($country_id) {
        if ($country_id) {
            $read = $write = $this->_db(self::DB);
            $ID = $read->fetchCol('SELECT ID FROM countryoforigin'
                    . ' WHERE magento_id = "' . (int) $country_id . '"');
            if (!count($ID)) {
                $options = $this->_countryCollectionFactory->create()->loadByStore()->toOptionArray(true);
                foreach ($options as $option) {
                    if ($option['value'] == $country_id) {
                        $insertQry = "INSERT INTO countryoforigin (Description, SDC_CountryCode, magento_id)"
                                . " VALUES ('" . addslashes($option['label']) . "', '" . addslashes($option['value']) . "', '" . $country_id . "')";
                        $write->query($insertQry);
                        return $write->lastInsertId();
                    }
                }
            }
            return $ID[0];
        }
        return '';
    }

    private function getPackageTypesID($value, $field) {
        if ($value) {
            $read = $write = $this->_db(self::DB);

//            $attribute_model = Mage::getModel('eav/entity_attribute');
//            $attribute_options_model = Mage::getModel('eav/entity_attribute_source_table');
            $attribute = $this->eavConfig->getAttribute('catalog_product', $field);
//            $attribute = $attribute_model->load($attribute_code);
            $options = $attribute->getSource()->getAllOptions();
//            $attribute_options_model->setAttribute($attribute);
//            $options = $attribute_options_model->getAllOptions(false);

            $selected = [];
            foreach ($options as $option) {
                if ($option['value'] == $value) {
                    $selected = $option;
                }
            }
            $ID = $read->fetchCol('SELECT ID FROM packagetypes '
                    . 'WHERE magento_id = "' . $this->clean($selected['label']) . '"');
            if (!count($ID)) {
                $insertQry = "INSERT INTO packagetypes (Description, magento_id)"
                        . " VALUES ('" . addslashes($selected['label']) . "', '" . addslashes($this->clean($selected['label'])) . "')";
                $write->query($insertQry);
                return $write->lastInsertId();
            }
            return $ID[0];
        }
        return '';
    }

    private function getLabelFormatID($value, $field) {
        if ($value) {
            $read = $write = $this->_db(self::DB);

            $attribute = $this->eavConfig->getAttribute('catalog_product', $field);
            $options = $attribute->getSource()->getAllOptions();

            $selected = [];
            foreach ($options as $option) {
                if ($option['value'] == $value) {
                    $selected = $option;
                }
            }

            $ID = $read->fetchCol('SELECT ID FROM labelformat'
                    . ' WHERE magento_id = "' . $this->clean($selected['label']) . '"');
            if (!count($ID)) {
                $insertQry = "INSERT INTO labelformat (Description, magento_id)"
                        . " VALUES ('" . addslashes($selected['label']) . "', '" . addslashes($this->clean($selected['label'])) . "')";
                $write->query($insertQry);
                return $write->lastInsertId();
            }
            return $ID[0];
        }
        return '';
    }

    private function getCogsAccount($cogId) {
        if ($cogId) {
            $ID = $this->_db(self::QBDB)->fetchAll('SELECT ListID, FullName FROM ' . $this->qb_db_prefix . '.account'
                    . ' WHERE magento_id = "' . (int) $cogId . '"');
            if (count($ID)) {
                return $ID[0];
            }
        }
        return '';
    }

    private function getIncomeAccount($incomeId) {
        if ($incomeId) {
            $ID = $this->_db(self::QBDB)->fetchAll('SELECT ListID, FullName FROM ' . $this->qb_db_prefix . '.account'
                    . ' WHERE magento_income_id = "' . (int) $incomeId . '"');
            if (count($ID)) {
                return $ID[0];
            }
        }
        return '';
    }

    private function getAssetAccount($assetId) {
        if ($assetId) {
            $ID = $this->_db(self::QBDB)->fetchAll('SELECT ListID, FullName FROM ' . $this->qb_db_prefix . '.account'
                    . ' WHERE magento_asset_id = "' . (int) $assetId . '"');
            if (count($ID)) {
                return $ID[0];
            }
        }
        return '';
    }

    private function getManufacturer($manufacturerId) {
        if ($manufacturerId) {
            $ID = $this->_db(self::QBDB)->fetchAll('SELECT ListID, Name FROM ' . $this->qb_db_prefix . '.vendor'
                    . ' WHERE magento_id = "' . (int) $manufacturerId . '"');
            if (count($ID)) {
                return $ID[0];
            }
        }
        return '';
    }

    private function getUnitOfMeasureSetRef($unitOfMeasureSetRefId, $field) {
        if ($unitOfMeasureSetRefId) {

            $attribute = $this->eavConfig->getAttribute('catalog_product', $field);
            $options = $attribute->getSource()->getAllOptions();

            $selected = [];
            foreach ($options as $option) {
                if ($option['value'] == $unitOfMeasureSetRefId) {
                    $selected = $option;
                }
            }
            $ID = $this->_db(self::QBDB)->fetchAll('SELECT ListID, Name FROM ' . $this->qb_db_prefix . '.unitofmeasureset'
                    . ' WHERE magento_id = "' . addslashes($this->clean($selected['value'])) . '"');
            if (count($ID) != 0) {
                return $ID[0];
            }
        }
        return "";
    }

    private function getSalestaxcode($salestaxcodeId) {
        if ($salestaxcodeId != "") {
            $ID = $this->_db(self::QBDB)->fetchAll('SELECT ListID, Name FROM ' . $this->qb_db_prefix . '.salestaxcode'
                    . ' WHERE magento_id = "' . (int) $salestaxcodeId . '"');
            if (count($ID) != 0) {
                return $ID[0];
            }
        }
        return "";
    }

    private function getProductType($unitOfMeasureSetRefId, $field) {
        if ($unitOfMeasureSetRefId != "") {

            $attribute = $this->eavConfig->getAttribute('catalog_product', $field);
            $options = $attribute->getSource()->getAllOptions();

            $selected = array();
            foreach ($options as $option) {
                if ($option['value'] == $unitOfMeasureSetRefId) {
                    $selected = $option;
                }
            }
            return $selected['label'];
        }
        return '';
    }

    public function forlatest200productssyncAction() {
        ini_set('max_execution_time', 0);

        $read = $this->_db(self::DB);
        $readQuickbook = $this->_db(self::QBDB);

        $collection = $this->_products->create()
                ->addAttributeToSort('entity_id', 'DESC')
                ->setPage(0, 200);

        if (!empty($collection)) {
            foreach ($collection as $product) {
                echo $pid = $product->getId() . '<br />';

                $sku = addslashes($product->getSku());

                $product_web = $read->fetchAll('SELECT * FROM partmaster WHERE PartNumber = "' . $sku . '"');
                if (!count($product_web)) {
                    $this->insertProduct($product);
                } else {
                    $this->updateProduct($product, $product_web[0]['ID']);
                }

                $product_qb = $readQuickbook->fetchAll('SELECT * FROM ' . $this->qb_db_prefix . '.iteminventory WHERE Name = "' . $sku . '"');
                if (!count($product_qb)) {
                    $product_qb = $readQuickbook->fetchAll('SELECT * FROM ' . $this->qb_db_prefix . '.iteminventoryassembly WHERE Name = "' . $sku . '"');
                    if (!count($product_qb)) {
                        $this->insertQbProduct($product);
                    } else {
                        $this->updateQbProduct($product, $product_qb[0]['Name'], $product_qb[0]['Status'], 'iteminventoryassembly');
                    }
                } else {
                    $this->updateQbProduct($product, $product_qb[0]['Name'], $product_qb[0]['Status'], 'iteminventory');
                }
            }
        }
    }

    private function clean($string) {
        $string = str_replace(' ', '-', $string); // Replaces all spaces with hyphens.
        $string = preg_replace('/[^A-Za-z0-9\-]/', '', $string); // Removes special chars.
        return strtolower(preg_replace('/-+/', '-', $string)); // Replaces multiple hyphens with single one.
    }

    public function checkAction() {
        var_dump($_SERVER);
    }

}
