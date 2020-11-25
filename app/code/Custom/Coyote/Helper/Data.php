<?php

namespace Custom\Coyote\Helper;

use Magento\Framework\App\Helper\AbstractHelper;
use Magento\Framework\UrlInterface;

class Data extends AbstractHelper {
    
    protected $_urlInterface;

    public function __construct(UrlInterface $urlInterface){
        $this->_urlInterface = $urlInterface;
    }


    public static function runItself($url = null) {
        if (!$url) {
            $url = $this->_urlInterface->getUrl('*/*/*', ['_current' => true]);
        }

        echo 'Running ' . $url;

        $ch = curl_init();
        curl_setopt_array($ch, array(
            CURLOPT_URL => $url,
            CURLOPT_FRESH_CONNECT => true,
            CURLOPT_TIMEOUT => 1,
        ));
        curl_exec($ch);
        curl_close($ch);
    }

}
