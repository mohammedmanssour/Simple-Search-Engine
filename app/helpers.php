<?php

if(!function_exists('mime_content_type')){
    function mime_content_type($filename) {
        $result = new finfo();
    
        if (is_resource($result) === true) {
            return $result->file($filename, FILEINFO_MIME_TYPE);
        }
    
        return false;
    }
}