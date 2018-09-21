<?php

namespace Util;

class HeaderWriter { 
    public function writeHeader($statusCode , $statusText) {
        \header("HTTP/1.0". " " .$statusCode . " " . $statusText);
    }
}