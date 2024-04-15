<?php
if(!function_exists('isLoginUser')){
    function isLoginUser(){
        $sessionUsername = getSessionUsername();
        $sessionIDUser = getSessionIdUser();
        if(empty($sessionUsername) || empty($sessionIDUser)){
            return false;
        }
        return true;
    }
}