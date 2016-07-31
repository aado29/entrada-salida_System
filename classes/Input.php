<?php
/**
 * Description of Input
 *
 * @author Alberto Diaz
 */
class Input {

    public static function exists($param = null, $type = 'post'){
        if (is_null($param))
            switch ($type) {
                case 'post':
                    return (!empty($_POST)) ? TRUE : FALSE;
                    break;
                case 'get' :
                    return (!empty($_GET) ? TRUE : FALSE);
                    break;
                default:
                    return FALSE;
                    break;
            }
        else {
            switch ($type) {
                case 'post':
                    return (!empty($_POST[$param])) ? TRUE : FALSE;
                    break;
                case 'get' :
                    return (!empty($_GET[$param]) ? TRUE : FALSE);
                    break;
                default:
                    return FALSE;
                    break;
            }
        }
    }
    
    public static function get($item, $type = ''){
        switch ($type) {
            case 'POST':
                return $_POST[$item];
                break;

            case 'GET':
                return $_GET[$item];
                break;
            
            default:
                if (!empty($_POST[$item]))
                    return $_POST[$item];
                else
                    if (!empty($_GET[$item]))
                        return $_GET[$item];
                break;
        }
        return false;
    }
}
