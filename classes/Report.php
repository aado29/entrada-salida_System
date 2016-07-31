<?php

/**
 * Description of Proyect
 *
 * @author albertodiaz
 */
class Report {

    private $_db,
            $_data;

    public function __construct($user = null) {
        $this->_db = DB::getInstance();
    }

    public function update($fields = array(), $id = NULL){
        if(!$this->_db->update('reports', $id, $fields)){
            throw new Exception('There was a problem updating.');
        }
    }

    public function create($fields = array()) {
        if (!$this->_db->insert('reports', $fields)) {
            throw new Exception('There was a problem creating.');
        }
    }

    public function getNonFinished($user = null) {
        if (!is_null($user)) {
            $id_user = $user->data()->id;
            $sql = "SELECT * from reports WHERE id_user = {$id_user} AND hourOut IS NULL";
            $data = $this->_db->query($sql);
            if ($data->count())
                return $data->first();
        }
        return false;
    }

    public function hasFinished($user = null) {
        if (!is_null($user)) {
            $id_user = $user->data()->id;
            $sql = "SELECT * from reports WHERE id_user = {$id_user} AND hourOut IS NULL";
            $data = $this->_db->query($sql);

            if ($data->count() < 1)
                return true;
        }
        return false;
    }

    public function getByDate($date = null) {
        if (!is_null($date)) {
            $sql = "SELECT * from reports WHERE date = '{$date}'";
            $data = $this->_db->query($sql);
            if ($data->count())
                return $data->results();
        }
        return false;
    }

    public function getBetweenDates($date = null, $date_ = null) {
        if (is_null($date) || is_null($date_)) {
            return false;
        } else {
            $sql = "SELECT * from reports WHERE date BETWEEN '{$date}' AND '{$date_}'";
            $data = $this->_db->query($sql);
            if ($data->count())
                return $data->results();
        }
    }

    public function getByUserId($user_id = null, $date = null) {
        if (!is_null($user_id)) {
            if (!is_null($date))
                $sql = "SELECT * from reports WHERE id_user = {$user_id} AND date = '{$date}'";
            else
                $sql = "SELECT * from reports WHERE id_user = {$user_id}";

            $data = $this->_db->query($sql);
            if ($data->count())
                return $data->results();
        }
        return false;
    }

    public function getRangeByUserId($user_id = null, $date = null, $date_ = null) {
        if (is_null($date) || is_null($date_)) {
            return $this->getByUserId($user_id);
        } else {
            if (!is_null($user_id)) {
                $sql = "SELECT * from reports WHERE id_user = {$user_id} AND date BETWEEN '{$date}' AND '{$date_}'";

                $data = $this->_db->query($sql);
                if ($data->count())
                    return $data->results();
            }
            return false;
        }
    }

}
