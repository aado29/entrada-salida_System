<?php
/**
 * Description of Validation
 *
 * @author miguelduran
 */
class Validate {

    private $_passed = false, 
            $_errors = array(),
            $_db = null;
    public function __construct() {
        $this->_db = DB::getInstance();
    }
    public function check($source, $items = array()){
        foreach ($items as $item => $rules) {
            foreach ($rules as $rule => $rule_value) {
         
                $value = trim($source[$item]);
                $item = escape($item);
                $frotName = $item;
                if(!empty($items[$item]['display'])) {
                    $frotName = $items[$item]['display'];
                }
                
                if($rule === 'required' && empty($value)){
                    $this->addError("{$frotName} es requerido");
                }else if(!empty ($value)){
                    switch ($rule) {
                        case 'min':
                            if(strlen($value) < $rule_value){
                                $this->addError("{$frotName} debe tener un minimo {$rule_value} digitos.");
                            }
                            break;
                        case 'max':
                            if(strlen($value) > $rule_value){
                                $this->addError("{$frotName} debe tener un maximo {$rule_value} digitos.");
                            }
                            break;
                        case 'matches':
                            if($value != $source[$rule_value]){
                                $this->addError("{$rule_value} debe ser igual {$frotName}");
                            }
                            break;
                        case 'unique':
                            $check = $this->_db->get($rule_value, array($item, '=', $value ));
                            if($check->count()){
                                $this->addError("{$frotName} ya existe.");
                            }
                            break;
                        case 'uniques':

                            $sql = "SELECT * FROM {$rule_value['table']} WHERE {$rule_value['field_id']} = {$rule_value['id']} AND $item = '{$value}'";
                            $check = $this->_db->query($sql);
                            if($check->count()){
                                $this->addError("{$frotName} ya se ecuentra registrado por este usuario.");
                            }
                            break;
                        case 'numeric':
                            if(!is_numeric($value)){
                                $this->addError("{$items[$item]['display']} debe ser en numeros.");
                            }
                            break;
                        case 'register_in':
                            $check = $this->_db->get('reports', array('id_user', '=', $rule_value ));
                            if($check->count()){
                                if (is_null($check->last()->hourOut))
                                    $this->addError("Se encuentra Activo");
                            }
                            break;
                        case 'register_out':
                            $check = $this->_db->get('reports', array('id_user', '=', $rule_value ));
                            if($check->count()){
                                if (!is_null($check->last()->hourOut))
                                    $this->addError("Se encuentra Inactivo");
                            }
                            break;
                        case 'date_limit':
                            if(strtotime($value) > strtotime($rule_value))
                                $this->addError("Hay un error en {$items[$item]['display']}.");
                            break;
                        case 'email':
                            if ($rule_value)
                                if (!filter_var($value, FILTER_VALIDATE_EMAIL))
                                    $this->addError("El {$items[$item]['display']} es invalido.");
                            break;
                        case 'name':
                            if ($rule_value)
                                if (!preg_match("/^[a-z ñáéíóú ,.'-]+$/i", $value))
                                    $this->addError("El {$items[$item]['display']} es invalido.");
                            break;
                        case 'time':
                            if ($rule_value)
                                if (!preg_match("/^[0-2][0-3]:[0-5][0-9]:[0-5][0-9]$/i", $value))
                                    $this->addError("El {$items[$item]['display']} es invalido.");
                            break;
                    }
                }
            }
        }
        if(empty($this->_errors)){
            $this->_passed = true;
        }
        return $this;
    }
    
    private function addError($error){
        $this->_errors[] = $error;
    }
    
    public function errors(){
        return $this->_errors;
    }
    
    public function passed(){
        return $this->_passed;
    }
}
