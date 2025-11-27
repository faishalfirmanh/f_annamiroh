<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class UserActivityLogModel extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function log_activity($user_id, $activity, $old_data = null, $new_data = null, $deleted_data = null) {
        $data = array(
            'user_id' => $user_id,
            'activity' => $activity,
            'old_data' => $old_data,
            'new_data' => $new_data,
            'deleted_data' => $deleted_data,
            'created_at' => date('Y-m-d H:i:s')
        );

        return $this->db->insert('user_activity_logs', $data);
    }
}