<?php


class RequestLogger
{

    private function isZscalerRequest()
    {
        return preg_match("/iuap1\.olcs\.demo\.dev\-dvsacloud\.uk/", $_SERVER['REQUEST_URI']);
    }

    public function execute()
    {

        $data = sprintf(
            "%s %s %s\n\nHTTP headers:\n",
            $_SERVER['REQUEST_METHOD'],
            $_SERVER['REQUEST_URI'],
            $_SERVER['SERVER_PROTOCOL']
        );

        foreach ($this->getHeaderList() as $name => $value) {
            $data .= $name . ': ' . $value . "\n";
        }

        $data .= "\nRequest body:\n";

        if ($this->isZscalerRequest()) {
            error_log(
                $data . file_get_contents('php://input') . "\n"
            );
        }
    }

    private function getHeaderList()
    {

        $headerList = [];
        foreach ($_SERVER as $name => $value) {
            if (preg_match('/^HTTP_/', $name)) {
                // convert HTTP_HEADER_NAME to Header-Name
                $name = strtr(substr($name, 5), '_', ' ');
                $name = ucwords(strtolower($name));
                $name = strtr($name, ' ', '-');

                // add to list
                $headerList[$name] = $value;
            }
        }

        return $headerList;
    }
}
