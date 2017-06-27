<?php
class Sanitize{

    static function email_sanitize($rawEmail){
        $sanitizedEmail = filter_var(strtolower($rawEmail), FILTER_SANITIZE_EMAIL);
        return $sanitizedEmail;
    }

    static function email_validate($rawEmail){
        $validatedEmail = filter_var($rawEmail, FILTER_VALIDATE_EMAIL);
        return $validatedEmail;
    }

    static function sanitize_string($rawString){
        if(is_array($rawString)){
            foreach($rawString as $key => $value){
                $sanitizedKey = filter_var($key, FILTER_SANITIZE_STRING);
                $sanitizedString[$sanitizedKey] = filter_var($value, FILTER_SANITIZE_STRING);
            }
        } else{
            $sanitizedString = filter_var($rawString, FILTER_SANITIZE_STRING);
        }
        return $sanitizedString;
    }

}
