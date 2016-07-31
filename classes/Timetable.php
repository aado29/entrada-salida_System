<?php

/**
 * Description of Proyect
 *
 * @author albertodiaz
 */
class Timetable {

    private $_db,
            $_data;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
    }

    public function update($fields = array(), $id = NULL){
        if(!$this->_db->update('timetable', $id, $fields)){
            throw new Exception('There was a problem updating.');
        }
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('timetable', $fields)) {
            throw new Exception('There was a problem creating.');
        }
    }

    public function delete($fields = array()) {
        if (!$this->_db->delete('timetable', $fields)) {
            throw new Exception('There was a problem deleting.');
        }
    }

    public function get($fields = array()) {
        $data = $this->_db->get('timetable', $fields);
        if ($data->count())
            return $data->results();
        else
            return false;
    }

    public function getById($id = null) {
        if (!is_null($id)) {
            $data = $this->_db->get('timetable', array('id', '=', $id));
            if ($data->count())
                return $data->first();
            else
                return false;
        }
        return false;
    }

    public function getByIdUser($id = null) {
        if (!is_null($id)) {
            $data = $this->_db->get('meta_data', array('id_user', '=', $id));
            if ($data->count()) {
                $output = array();
                foreach ($data->results() as $value) {
                    $hourtable = $this->getDataById($value->id_data);
                    // output[] = hourtable;
                    // var_dump($hourtable);
                    $output[] = $hourtable;
                    // var_dump($output);
                }
                return $output;
            }
        }
    }

    public function getDataById($id = null) {
        if (!is_null($id)) {
            $data = $this->_db->get('timetable', array('id', '=', $id));
            if ($data->count())
                $hourtable = $data->first();
                return $hourtable;
        }
    }

    public function current($id_user = null, $time = null) {
        if (!is_null($time))
            $c_h = $time;
        else 
            $c_h = date('G:i:s');

        $data = $this->getByIdUser($id_user);
        foreach ($data as $value) {
            $in = strtotime($value->hourIn);
            $out = strtotime($value->hourOut);
            $c = strtotime($c_h);
            if ($c >= $in && $c <= $out)
                return $value;
        }

        return null;
    }

    public function maxVal($date, $date_) {
        $in = strtotime($date);
        $out = strtotime($date_);
        if ($in > $out)
            return $date;
        else
            return $date_;
    }

}
