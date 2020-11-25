<?php

namespace Webgility\EccM2\Model;

class EccWgBaseResponse
{
    private $responseArray = [];

    public function setStatusCode($StatusCode)
    {
        $this->responseArray['StatusCode'] = $StatusCode;
    }
    public function setVersion($StatusCode)
    {
        $this->responseArray['Version'] = $StatusCode;
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
        $str .= '<b>STORE MODULE ADDRESS</b><br>';
        $str .= "You will need to copy and paste your Webgility Store
        Module address into eCC during the Add a Store process.<br>";
        $str .= " <div>";
        $str .= '<div> <a target="_blank" href="'.$this->eccUrl().'">'.$this->eccUrl().'</a></div><div>&nbsp;</div>';
        $str .= '<div><b>DOWNLOAD THE INSTALLER</b><br>If you need to download the eCC installer, click
        <a target="_blank" href="http://download.webgility.com/downloads/eCCInstaller.zip">here</a></div>';
        $str .= '<div><br><b>WEBGILITY SUPPORT</b><br>For help docs, support chat,
        or to submit a ticket, visit <a target="_blank" href=" http://support.webgility.com/ecc/">Webgility Support</a>.
        You can also reach our Support Team by phone at (877) 753-5373 ext. 3.</div>';
        $str .= '<div><br>Copyright &copy; 2020 Webgility Inc.</div>';
        return $str;
    }
}
