<?php

/**
 * Description of User
 *
 * @author albertodiaz
 */
class User {

    private $_db,
            $_data,
            $_sessionName,
            $_cookieName,
            $_isLoggedIn;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
        $this->_sessionName = Config::get('session/session_name');
        $this->_cookieName = Config::get('remember/cookie_name');
        if (!$user) {
            if (Session::exists($this->_sessionName)) {
                $user = Session::get($this->_sessionName);

                if ($this->find($user)) {
                    $this->_isLoggedIn = true;
                } else {
                    // process log out
                }
            }
        } else {
            $this->find($user, 'id_num');
        }
    }

    public function update($fields = array(), $id = NULL){
        if(!$id && $this->isLoggedIn()){
            $id = $this->data()->id;
        }
        if(!$this->_db->update('users', $id, $fields)){
            throw new Exception('There was a problem updating an account.');
        }
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('users', $fields)) {
            throw new Exception('There was a problem creating an account.');
        }
    }

    public function delete($fields = array()) {
        if (!$this->_db->delete('users', $fields)) {
            throw new Exception('Three was a problem deleting an account.');
            
        }
        
    }

    public function find($type = null, $field = 'username') {
        if ($type) {
            $field = (is_numeric($type) && $field != 'id_num') ? 'id' : $field;
            $data = $this->_db->get('users', array($field, '=', $type));
            if ($data->count()) {
                $this->_data = $data->first();
                return $this->_data;
            }
        }
        return FALSE;
    }

    public function findByPass($password = null, $id = null) {
        if ($id && $password) {
            $sql = "SELECT * FROM users WHERE password = '{$password}' AND id = {$id}";
            $data = $this->_db->query($sql);
            if ($data->count()) {
                $this->_data = $data->first();
                return $this->_data;
            } else {
                return FALSE;
            }
        }
        return FALSE;
    }

    public function login($id_num = NULL, $password = NULL, $remember = false) {

        if (!$id_num && !$password && $this->exists()) {
            Session::put($this->_sessionName, $this->data()->id);
        } else {
            $user = $this->find($id_num, 'id_num');
            if ($user) {
                if ($this->data()->password === Hash::make($password, $this->data()->salt)) {
                    Session::put($this->_sessionName, $this->data()->id);
                    if ($remember) {
                        $hashCheck = $this->_db->get('users_session', array('id_user', '=', $this->data()->id));

                        if ($hashCheck->count()) {
                            $hash = $hashCheck->first()->hash;
                        } else {
                            $hash = Hash::unique();
                            $this->_db->insert('users_session', array(
                                'user_id' => $this->data()->id,
                                'hash' => $hash
                            ));
                        }

                        Cookie::put($this->_cookieName, $hash, Config::get('remember/cookie_expiry'));
                    }
                    return TRUE;
                }
            }
        }
        return FALSE;
    }

    public function hasPermission($key){
        $group = $this->_db->get('groups', array('id', '=', $this->data()->id_group));
        if($group->count()){
            $permissions = json_decode($group->first()->permissions, TRUE);
            if($permissions[$key] == true){
                return true;
            }
        }
        return false;
    }

    public function getUsers() {
        $data = $this->_db->get('users', array('id', '>', '0'));
        if ($data->count()) {
            return $data->results();
        } else {
            return false;
        }
    }

    function getFullName($plain = true) {
        if (!$plain) {
            $output = '<span>';
            $output .= $this->_data->firstName .' '. $this->_data->lastName;
            $output .= '</span>';
            echo $output;
        } else
            return $this->_data->firstName .' '. $this->_data->lastName;
    }

    function getEmail($plain = true) {
        if (!$plain) {
            $output = '<span>';
            $output .= $this->_data->email;
            $output .= '</span>';
            echo $output;
        } else
            return $this->_data->email;
    }

    function getUsername($plain = true) {
        if (!$plain) {
            $output = '<span>';
            $output .= $this->_data->username;
            $output .= '</span>';
            echo $output;
        } else
            return $this->_data->username;
    }

    function getPosition($plain = true) {
        $data = $this->_db->get('positions', array('id', '=', $this->_data->id_position));
        if ($data->count()) {
            $position = $data->first();

            if (!$plain) {
                $output = '<span>';
                $output .= $position->name;
                $output .= '</span>';
                echo $output;
            } else
                return $position->name;
        }
        return false;
    }

    public function exists(){
        return (!empty($this->_data)) ? true : false;
    }

    public function logout() {
        $this->_db->delete('users_session', array('user_id', '=', $this->data()->id));
        
        Session::delete($this->_sessionName);
        Cookie::delete($this->_cookieName);
    }

    public function data() {
        return $this->_data;
    }

    public function isLoggedIn() {
        return $this->_isLoggedIn;
    }

}
