<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExport
 */


namespace Amasty\ProductExport\Export\Form;

use Amasty\ExportCore\Api\Config\EntityConfigInterface;
use Amasty\ExportCore\Api\Config\ProfileConfigInterface;
use Amasty\ExportCore\Api\FormInterface;
use Amasty\ExportCore\Export\Form\CompositeForm;
use Magento\Framework\App\RequestInterface;
use Magento\Framework\Exception\LocalizedException;
use Magento\Framework\View\Asset\Repository;

class Fields extends CompositeForm implements FormInterface
{
    /**
     * @var Repository
     */
    private $assetRepo;

    public function __construct(
        Repository $assetRepo,
        array $metaProviders
    ) {
        parent::__construct($metaProviders);
        $this->assetRepo = $assetRepo;
    }

    public function getMeta(EntityConfigInterface $entityConfig, array $arguments = []): array
    {
        $result = ['fields' => ['children' => []]];
        foreach ($this->getFormGroupProviders() as $formGroup) {
            $result['fields']['children'] = array_merge_recursive(
                $result['fields']['children'],
                $formGroup['metaClass']->getMeta($entityConfig, $formGroup['arguments'] ?? [])
            );
        }
        $this->modifyMeta($result);

        return $result;
    }

    public function getData(ProfileConfigInterface $profileConfig): array
    {
        $result = [];
        foreach ($this->getFormGroupProviders() as $formGroup) {
            $result = array_merge_recursive($result, $formGroup['metaClass']->getData($profileConfig));
        }
        if (empty($result)) {
            return [];
        }
        $result['fields']['catalog_product_entity']['use_custom_prefix'] = $profileConfig
            ->getExtensionAttributes()->getUseCustomPrefix();
        $result['fields']['catalog_product_entity']['field_postfix'] = $profileConfig
            ->getExtensionAttributes()->getFieldPostfix();

        return ['fields' => $result];
    }

    public function prepareConfig(ProfileConfigInterface $profileConfig, RequestInterface $request): FormInterface
    {
        $params = $request->getParams();
        $fields = $params['fields'] ?? [];
        unset($params['fields']);
        $params = array_merge_recursive($params, $fields);
        $request->setParams($params);
        foreach ($this->getFormGroupProviders() as $formGroup) {
            $formGroup['metaClass']->prepareConfig($profileConfig, $request);
        }
        $profileConfig->getExtensionAttributes()->setUseCustomPrefix(
            $params["fields"]["catalog_product_entity"]["use_custom_prefix"] ?? '0'
        );

        return $this;
    }

    protected function modifyMeta(array &$meta): void
    {
        $fieldPostfixMeta = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Prefix/Tag Name Delimiter'),
                        'dataType' => 'text',
                        'default' => '',
                        'visible' => true,
                        'formElement' => 'input',
                        'componentType' => 'field',
                        'sortOrder' => '5',
                        'tooltipTpl' => 'Amasty_ExportCore/form/element/tooltip',
                        'tooltip' => [
                            'description' => '<img src="'
                                . $this->assetRepo->getUrl(
                                    'Amasty_ProductExport::images/custom_prefix_tag_name.gif'
                                )
                                . '"/>'
                        ]
                    ]
                ]
            ]
        ];
        $useCustomPrefixMeta = [
            'arguments' => [
                'data' => [
                    'config' => [
                        'label' => __('Use Custom Prefix/Tag Name'),
                        'dataType' => 'boolean',
                        'prefer' => 'toggle',
                        'valueMap' => ['true' => '1', 'false' => '0'],
                        'default' => '',
                        'sortOrder' => '0',
                        'formElement' => 'checkbox',
                        'visible' => true,
                        'componentType' => 'field',
                        'tooltipTpl' => 'Amasty_ExportCore/form/element/tooltip',
                        'tooltip' => [
                            'description' => '<img src="'
                                . $this->assetRepo->getUrl(
                                    'Amasty_ProductExport::images/custom_prefix_tag_name.gif'
                                )
                                . '"/>'
                        ]
                    ]
                ]
            ]
        ];
        $fields = &$meta["fields"]["children"]["fieldsConfigAdvanced"]["children"]
            ["catalog_product_entity"]["children"];
        $this->processPrefixFields($fields);

        $meta["fields"]["children"]["fieldsConfigAdvanced"]["children"]
            ["catalog_product_entity"]["children"]['field_postfix'] = $fieldPostfixMeta;
        $meta["fields"]["children"]["fieldsConfigAdvanced"]["children"]
        ["catalog_product_entity"]["children"]['use_custom_prefix'] = $useCustomPrefixMeta;
        $meta["fields"]["children"]["fieldsConfigAdvanced"]["children"]
        ["catalog_product_entity"]["arguments"]["data"]["config"] = [
            "label" => __('Product (root entity)'),
            'componentType' => 'fieldset',
            'visible' => true,
            'collapsible' => true,
            'opened' => true,
            'additionalClasses' => 'amproductexport-fieldset-withtooltip',
            'template' => 'Amasty_ProductExport/form/fieldset',
            'tooltipTpl' => 'Amasty_ExportCore/form/element/tooltip',
            'tooltip' => [
                'description' => '<img src="'
                    . $this->assetRepo->getUrl(
                        'Amasty_ProductExport::images/product_root_entity.gif'
                    )
                    . '"/>'
            ]
        ];

        $codeOutputField = &$meta["fields"]["children"]["fieldsConfigAdvanced"]["children"]
        ["catalog_product_entity"]["children"]['field_code_output']['arguments']['data']['config'];
        $codeOutputField = array_merge($codeOutputField, [
            'sortOrder' => 1,
            'componentType' => 'field',
            'formElement' => 'input',
            'label' => __('Custom Prefix/Tag Name'),
            'component' => 'Amasty_ProductExport/js/form/element/input'
        ]);
        $meta["fields"]["children"]["fieldsConfigAdvanced"]["children"]
        ["catalog_product_entity"]["children"]['addField']['arguments']['data']['config']['sortOrder'] = 10;
    }

    private function processPrefixFields(array &$fields): void
    {
        foreach ($fields as $fieldName => &$field) {
            if (!isset($field['children'][$fieldName . '_container'])) {
                continue;
            }
            $placeholder = $field["children"][$fieldName . '_container']["children"]
            ["field_code"]["arguments"]["data"]["config"]['value'];
            $field["children"][$fieldName . '_container']["children"]
            ["field_code"]["arguments"]["data"]["config"]["formElement"] = 'hidden';

            $field['children'][$fieldName . '_container']['children']
            ['field_code_output']['arguments']['data']['config'] = [
                'label' => __('Custom Prefix/Tag Name'),
                'placeholder' => $placeholder,
                'componentType' => 'field',
                'formElement' => 'input',
                'component' => 'Amasty_ProductExport/js/form/element/input'
            ];
            $this->processPrefixFields($field["children"][$fieldName . '_container']["children"]);
        }
    }
}
