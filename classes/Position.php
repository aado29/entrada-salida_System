<?php

/**
 * Description of Proyect
 *
 * @author albertodiaz
 */
class Position {

    private $_db,
            $_data;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
    }

    public function update($fields = array(), $id = NULL){
        if(!$this->_db->update('positions', $id, $fields)){
            throw new Exception('There was a problem updating.');
        }
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('positions', $fields)) {
            throw new Exception('There was a problem creating.');
        }
    }

    public function delete($fields = array()) {
        if (!$this->_db->delete('positions', $fields)) {
            throw new Exception('There was a problem deleting.');
        }
    }

    public function get($fields = array()) {
        $data = $this->_db->get('positions', $fields);
        if ($data->count())
            return $data->results();
        else
            return false;
    }

    public function getById($id = null) {
        if (!is_null($id)) {
            $data = $this->_db->get('positions', array('id', '=', $id));
            if ($data->count())
                return $data->first();
            else
                return false;
        }
        return false;
    }

}
