<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExportEntity
 */


declare(strict_types=1);

namespace Amasty\ProductExportEntity\Export\FieldsClass;

use Amasty\ExportCore\Api\Config\Entity\Field\FieldInterfaceFactory;
use Amasty\ExportCore\Export\Config\Eav\Attribute\OptionsConverter;
use Amasty\ExportCore\Export\DataHandling\ActionConfigBuilder;
use Amasty\ExportCore\Export\FieldsClass\EavAttribute;
use Amasty\ExportCore\Export\Filter\FilterConfigBuilder;
use Amasty\ExportCore\Export\Filter\FilterTypeResolver;
use Magento\Catalog\Model\ResourceModel\Eav\Attribute;
use Magento\Customer\Api\Data\AttributeMetadataInterface;
use Magento\Eav\Api\AttributeRepositoryInterface;
use Magento\Framework\Api\SearchCriteriaBuilder;

class EavAttributeBlackListed extends EavAttribute
{
    /**
     * @var array|string[]
     */
    private $blackListAttributeCodes = [];

    public function __construct(
        FieldInterfaceFactory $fieldConfigFactory,
        FilterConfigBuilder $filterConfigBuilder,
        ActionConfigBuilder $actionConfigBuilder,
        FilterTypeResolver $filterTypeResolver,
        OptionsConverter $attributeOptionsConverter,
        SearchCriteriaBuilder $searchCriteriaBuilder,
        AttributeRepositoryInterface $attributeRepository,
        string $eavEntityTypeCode = '',
        array $blackListAttributeCodes = []
    ) {
        parent::__construct(
            $fieldConfigFactory,
            $filterConfigBuilder,
            $actionConfigBuilder,
            $filterTypeResolver,
            $attributeOptionsConverter,
            $searchCriteriaBuilder,
            $attributeRepository,
            $eavEntityTypeCode
        );
        $this->blackListAttributeCodes = $blackListAttributeCodes;
    }

    /**
     * Get eav attributes
     *
     * @return Attribute[]
     */
    protected function getEavAttributes()
    {
        $attributes = [];
        if ($this->eavEntityTypeCode) {
            if (!empty($this->blackListAttributeCodes)) {
                $this->searchCriteriaBuilder->addFilter(
                    AttributeMetadataInterface::ATTRIBUTE_CODE,
                    $this->blackListAttributeCodes,
                    'nin'
                );
            }
            $criteria = $this->searchCriteriaBuilder->create();
            $attributes = $this->attributeRepository->getList($this->eavEntityTypeCode, $criteria)->getItems();
        }

        return $attributes;
    }
}
