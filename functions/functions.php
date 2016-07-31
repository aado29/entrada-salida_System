<?php
	function escape($string){
	    return htmlentities($string, ENT_QUOTES, 'UTF-8');
	}

	function get_template( $template = null ) {
		if ($template) {
			require_once 'template/'.$template.'.php';
		}
	}

	function get_template_name() {
		return 'Default';
	}

	function get_page_name() {
		return Config::get('template/name');
	}

	function get_permalink($arg = array()) {
		$x = 1;
		$value = '?';
        foreach ($arg as $k => $n) {
        	if ($x < count($arg)) 
        		$value .= $k.'='.$n.'&';
        	else
        		$value .= $k.'='.$n;
        	$x ++;
        }
        echo $value;
	}

	function handle_messages($messages, $type = 'success') {
		if ($messages) {
			$output = '<div class="alert alert-'. $type .'" role="alert">';;
			$output .= '<a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>';
		        foreach ($messages as $message){
		            $output .= $message.'<br>';
		        }
	        $output .= '</div>';

	        echo $output;
	    } else {
	    	return false;
	    }
	}

	function getReportByDate($date = null) {
		if (is_null($date))
			$date = date('Y-m-d');
		$report = new Report();
		return $report->getByDate($date);
	}

	function getReportByRangeDate($date = null, $date_ = null) {
		if (is_null($date) || is_null($date_))
			return array();
		$report = new Report();
		return $report->getBetweenDates($date, $date_);
	}

	function getReportByUserId($id = null, $date = null) {
		$report = new Report();
		if (is_null($date))
			return $report->getByUserId($id);
		if (!is_null($id)) {
			return $report->getByUserId($id, $date);
		}
		return false;
	}

	function getReportRangeByUserId($id = null, $date = null, $date_ = null) {
		$report = new Report();
		if (is_null($date) || is_null($date_))
			return $report->getByUserId($id);
		if (!is_null($id)) {
			return $report->getRangeByUserId($id, $date, $date_);
		}
		return false;
	}

	function getUserById($id = null) {
		$user = new User();
		if (!is_null($id)) {
			$data = $user->find($id, 'id');
			return $data;
		}

		return false;
	}

	function getPositions() {
		$db = DB::getInstance();
		$data = $db->get('positions', array('id', '>', 0));
		return $data->results();
	}

	function getPositionById($id) {
		if (!is_null($id)) {
			$db = DB::getInstance();
			$data = $db->get('positions', array('id', '=', $id));
			return $data->first();
		} else {
			return false;
		}
	}

	function getGroups() {
		$db = DB::getInstance();
		$data = $db->get('groups', array('id', '>', 0));
		return $data->results();
	}

	function timeToJSON($time) {
		$date = $time->date;
		$hour = $time->hourIn;
		list($year, $month, $day) = explode('-', $date);
		list($hours, $minutes, $seconds) = explode(':', $hour);
		return json_encode(array(
			'year' 		=> intval($year),
			'month' 	=> intval($month)-1,
			'day' 		=> intval($day),
			'hour' 		=> intval($hours),
			'minutes' 	=> intval($minutes),
			'seconds' 	=> intval($seconds)
		));
	}
