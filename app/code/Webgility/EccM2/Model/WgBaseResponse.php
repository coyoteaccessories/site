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

class WgBaseResponse
{
    private $responseArray = [];
    public function setStatusCode($StatusCode)
    {
        $this->responseArray['StatusCode'] = $StatusCode;
    }
    public function setStatusMessage($StatusMessage)
    {
        $this->responseArray['StatusMessage'] =$StatusMessage;
    }
    public function getBaseResponce()
    {
        return $this->responseArray;
    }
    public function message()
    {
        $str = '<div><strong>Webgility extension for Fedex Hold At Location</strong></div><div>&nbsp;</div>';
        $str .= '<div>Webgility provides software to help simplify and automate eCommerce your business.
        We offer a suite of products that enable online retailers to integrate with QuickBooks,
        streamline order fulfillment, and easily manage inventory. As a certified FedEx CSP partner,
        our software seamlessly integrates with FedEx services to provide comprehensive shipping solutions.
        With Hold at FedEx Location, you can offer more shipping options for your customers.
        With Shiplark, you can process orders and print shipping labels instantly.
        And eCC allows you to manage shipping, inventory, and QuickBooks from one central place.
        Webgility has helped thousands of small- and medium-sized companies save time and money by
        automating their eCommerce. Learn more about us.<br>';
        $str .= 'To get started, visit 
        <a target="_blank" href="http://staging.webgility.com/hold-at-location.php">www.webgility.com</a>';
        $str .= " <div>";
        return $str;
    }
    public function faq()
    {
        $str .= '<div> For FAQ\'s related to the Hold at FedEx Location Plugin,please
        <a target="_blank" href="http://community.webgility.com/webgility/topics/hold_at_fedex_location_faqs">
        click here</a> ';
        $str .= " </div>";
        return $str;
    }
}
