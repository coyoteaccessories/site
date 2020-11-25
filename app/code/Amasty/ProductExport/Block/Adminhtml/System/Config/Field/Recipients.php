<?php
/**
 * @author Amasty Team
 * @copyright Copyright (c) 2020 Amasty (https://www.amasty.com)
 * @package Amasty_ProductExport
 */


namespace Amasty\ProductExport\Block\Adminhtml\System\Config\Field;

use Magento\Config\Block\System\Config\Form\Field;
use Magento\Framework\Data\Form\Element\AbstractElement;

class Recipients extends Field
{
    protected function _getElementHtml(AbstractElement $element)
    {
        $js = '<script type="text/javascript">
            require([
                "Magento_Ui/js/lib/view/utils/async",
                "Amasty_ProductExport/js/form/tag-it"
            ], function ($) {
                "use strict";

                var input = "#' . $element->getHtmlId() . '";
                $.async(input, (function() {
                    $(input).tagit();
                }));
            });
            </script>';
        $element->setAfterElementJs($js);

        return parent::_getElementHtml($element);
    }
}
