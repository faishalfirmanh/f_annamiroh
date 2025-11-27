<?php

class User extends CI_Controller
{

    function __construct()
    {
        parent::__construct();
        $this->load->model('User_model', '', TRUE);
        $this->load->database();
        $this->load->helper('url', 'session');
        $this->load->library('grocery_CRUD');
    }

    private function _init()
    {
        $this->output->set_template('admin');
        $ide = $this->session->userdata('level');
        $this->output->set_output_data('menu', $this->main_model->get_menu($ide));
        $this->load->js('assets/themes/default/js/jquery-1.9.1.min.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-transition.js');
        $this->load->js('assets/themes/default/hero_files/bootstrap-collapse.js');
    }
    var $crud = null;
    function register()
    {
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $state = $this->crud->getState();
        echo "state = $state";
        $this->crud->set_table('admin')->fields('username', 'nama', 'alamat')->unset_texteditor('alamat')->field_type('username', 'readonly')->set_subject('Username')->unset_back_to_list();
        $this->show();
    }
    //user managemnt untuk jamaah, leader dan agen
    function cek_user($x)
    {
        //$uname = $this->input->post('username',true);
        $uname = $x;
        $query = $this->db->query("select id_admin from admin where username='" . $uname . "'");
        // echo $this->db->last_query();
        echo $query->num_rows();
    }
    function restore($x)
    {
        if ($x == "293827984678565874xdeuy92uyriueyiru" and false) {
            $d = $this->db->query("SELECT  data_baru FROM transaksi_paket_log");
            foreach ($d->result() as $row) {
                // echo( $row->data_baru);
                $d = json_decode($row->data_baru);
                if (isset($d->paket_umroh))
                    if ($d->paket_umroh == "125") {
                        echo "Paket = " . $d->paket_umroh;
                        print_r($d);
                        $this->db->insert('transaksi_paket', $d);
                        echo "<br>";
                    }
            }
        }
    }
    function jamaah()
    {
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->load->library('grocery_CRUD_extended');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $state = $this->crud->getState();
        $this->crud->callback_add_field('username', function () {
            return '
        <style>
        /* Container */
.container{
    margin: 0 auto;
    width: 70%;
}

/* Registration */
#div_reg{
    border: 1px solid gray;
    border-radius: 3px;
    width: 470px;
    height: 370px;
    box-shadow: 0px 2px 2px 0px  gray;
    margin: 0 auto;
}

#div_reg h1{
    margin-top:0px;
    font-weight: normal;
    padding:10px;
    background-color:cornflowerblue;
    color:white;
    font-family:sans-serif;
}

#div_reg div{
    clear: both;
    margin-top: 10px;
    padding: 5px;
}

#div_reg .textbox{
    width: 96%;
    padding: 7px;
}

#div_reg input[type=submit]{
    padding: 7px;
    width: 100px;
    background-color: lightseagreen;
    border: 0px;
    color: white;
}

/* Response */
.response{
    padding: 6px;
    display: none;
}

.not-exists{
    color: green;
}

.exists{
    color: red;
}
        </style>
        
        <div>
            <input type="text" class="textbox" id="username" name="username" placeholder="Username"  />
            <div id="uname_response" class="response"></div>
        </div><script>
    $(document).ready(function(){

        $("#username").change(function(){

            var uname = $("#username").val().trim();

            if(uname != \'\'){

                $("#uname_response").show();

                $.ajax({
                    url: \'' . base_url() . 'user/cek_user/\'+uname,
                    type: \'post\',
                    data: {uname:uname},
                    success: function(response){
                       
                        if(response > 0){
                            $("#uname_response").html("<span class=\'exists\'>* Username sudah digunakan, silahkan gunakan username yang lain.</span>");

                        }else{
                            $("#uname_response").html("<span class=\'not-exists\'>Username Ok.</span>");

                        }

                    }
                });
            }else{
                $("#uname_response").hide();
            }

        });

    });
</script>';
        });
        $this->crud->set_table('admin')->fields('username', 'fk', 'email', 'level', 'password')->unset_texteditor('alamat')->set_subject('Username')->columns('username', 'fk')->display_as('fk', 'Nama Jamaah / No Ktp')->set_relation('fk', 'data_jamaah', '{nama_jamaah} / {no_ktp}');
        $this->crud->callback_edit_field('password', array($this, 'set_password_input_to_empty'))->where(array('level' => 6))->field_type('level', 'hidden', 6);
        $this->crud->callback_before_insert(array($this, 'encrypt_password_callback'))->unset_delete();
        $this->crud->callback_after_update(array($this, 'log_user_after_update'));
        $this->crud->callback_add_field('username', function () {
            return '
        <style>
        /* Container */
.container{
    margin: 0 auto;
    width: 70%;
}

/* Registration */
#div_reg{
    border: 1px solid gray;
    border-radius: 3px;
    width: 470px;
    height: 370px;
    box-shadow: 0px 2px 2px 0px  gray;
    margin: 0 auto;
}

#div_reg h1{
    margin-top:0px;
    font-weight: normal;
    padding:10px;
    background-color:cornflowerblue;
    color:white;
    font-family:sans-serif;
}

#div_reg div{
    clear: both;
    margin-top: 10px;
    padding: 5px;
}

#div_reg .textbox{
    width: 96%;
    padding: 7px;
}

#div_reg input[type=submit]{
    padding: 7px;
    width: 100px;
    background-color: lightseagreen;
    border: 0px;
    color: white;
}

/* Response */
.response{
    padding: 6px;
    display: none;
}

.not-exists{
    color: green;
}

.exists{
    color: red;
}
        </style>
        
        <div>
            <input type="text" class="textbox" id="username" name="username" placeholder="Username"  />
            <div id="uname_response" class="response"></div>
        </div><script>
    $(document).ready(function(){

        $("#username").change(function(){

            var uname = $("#username").val().trim();

            if(uname != \'\'){

                $("#uname_response").show();

                $.ajax({
                    url: \'' . base_url() . 'user/cek_user/\'+uname,
                    type: \'post\',
                    data: {uname:uname},
                    success: function(response){
                       
                        if(response > 0){
                            $("#uname_response").html("<span class=\'exists\'>* Username sudah digunakan, silahkan gunakan username yang lain.</span>");

                        }else{
                            $("#uname_response").html("<span class=\'not-exists\'>Username Ok.</span>");

                        }

                    }
                });
            }else{
                $("#uname_response").hide();
            }

        });

    });
</script>';
        });
        $this->crud->callback_after_insert(array($this, 'log_user_after_update'));
        $this->show();
    }
    function leader()
    {
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $state = $this->crud->getState();
        $this->crud->set_table('admin')->fields('username', 'fk', 'alamat', 'level', 'password')->unset_texteditor('alamat')->set_subject('Username Leader')->columns('username', 'nama_admin')->display_as('fk', 'Nama / No Leader')->set_relation('fk', 'data_jamaah_agen', '{nama}/{id}', array('pangkat' => '1'));
        $this->crud->callback_edit_field('password', array($this, 'set_password_input_to_empty'));
        $this->crud->callback_before_insert(array($this, 'encrypt_password_callback'))->where(array('level' => 4))->field_type('level', 'hidden', 4);
        $this->crud->callback_add_field('username', function () {
            return '
        <style>
        /* Container */
.container{
    margin: 0 auto;
    width: 70%;
}

/* Registration */
#div_reg{
    border: 1px solid gray;
    border-radius: 3px;
    width: 470px;
    height: 370px;
    box-shadow: 0px 2px 2px 0px  gray;
    margin: 0 auto;
}

#div_reg h1{
    margin-top:0px;
    font-weight: normal;
    padding:10px;
    background-color:cornflowerblue;
    color:white;
    font-family:sans-serif;
}

#div_reg div{
    clear: both;
    margin-top: 10px;
    padding: 5px;
}

#div_reg .textbox{
    width: 96%;
    padding: 7px;
}

#div_reg input[type=submit]{
    padding: 7px;
    width: 100px;
    background-color: lightseagreen;
    border: 0px;
    color: white;
}

/* Response */
.response{
    padding: 6px;
    display: none;
}

.not-exists{
    color: green;
}

.exists{
    color: red;
}
        </style>
        
        <div>
            <input type="text" class="textbox" id="username" name="username" placeholder="Username"  />
            <div id="uname_response" class="response"></div>
        </div><script>
    $(document).ready(function(){

        $("#username").change(function(){

            var uname = $("#username").val().trim();

            if(uname != \'\'){

                $("#uname_response").show();

                $.ajax({
                    url: \'' . base_url() . 'user/cek_user/\'+uname,
                    type: \'post\',
                    data: {uname:uname},
                    success: function(response){
                       
                        if(response > 0){
                            $("#uname_response").html("<span class=\'exists\'>* Username sudah digunakan, silahkan gunakan username yang lain.</span>");

                        }else{
                            $("#uname_response").html("<span class=\'not-exists\'>Username Ok.</span>");

                        }

                    }
                });
            }else{
                $("#uname_response").hide();
            }

        });

    });
</script>';
        });
        $this->crud->callback_after_update(array($this, 'log_user_after_update'));
        $this->crud->callback_after_insert(array($this, 'log_user_after_update'));
        $this->show();
    }
    function agen()
    {
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $state = $this->crud->getState();
        $this->crud->callback_add_field('username', function () {
            return '
        <style>
        /* Container */
.container{
    margin: 0 auto;
    width: 70%;
}

/* Registration */
#div_reg{
    border: 1px solid gray;
    border-radius: 3px;
    width: 470px;
    height: 370px;
    box-shadow: 0px 2px 2px 0px  gray;
    margin: 0 auto;
}

#div_reg h1{
    margin-top:0px;
    font-weight: normal;
    padding:10px;
    background-color:cornflowerblue;
    color:white;
    font-family:sans-serif;
}

#div_reg div{
    clear: both;
    margin-top: 10px;
    padding: 5px;
}

#div_reg .textbox{
    width: 96%;
    padding: 7px;
}

#div_reg input[type=submit]{
    padding: 7px;
    width: 100px;
    background-color: lightseagreen;
    border: 0px;
    color: white;
}

/* Response */
.response{
    padding: 6px;
    display: none;
}

.not-exists{
    color: green;
}

.exists{
    color: red;
}
        </style>
        
        <div>
            <input type="text" class="textbox" id="username" name="username" placeholder="Username"  />
            <div id="uname_response" class="response"></div>
        </div><script>
    $(document).ready(function(){

        $("#username").change(function(){

            var uname = $("#username").val().trim();

            if(uname != \'\'){

                $("#uname_response").show();

                $.ajax({
                    url: \'' . base_url() . 'user/cek_user/\'+uname,
                    type: \'post\',
                    data: {uname:uname},
                    success: function(response){
                       
                        if(response > 0){
                            $("#uname_response").html("<span class=\'exists\'>* Username sudah digunakan, silahkan gunakan username yang lain.</span>");

                        }else{
                            $("#uname_response").html("<span class=\'not-exists\'>Username Ok.</span>");

                        }

                    }
                });
            }else{
                $("#uname_response").hide();
            }

        });

    });
</script>';
        });
        $this->crud->set_table('admin')->fields('username', 'fk', 'alamat', 'level', 'password')->unset_texteditor('alamat')->set_subject('Username Agen')->columns('username', 'nama_admin')->display_as('fk', 'Nama Agen / No Agen')->set_relation('fk', 'data_jamaah_agen', '{nama}/{id}', array('pangkat' => '0'));
        $this->crud->callback_edit_field('password', array($this, 'set_password_input_to_empty'));
        $this->crud->callback_before_insert(array($this, 'encrypt_password_callback'))->where(array('level' => 5))->field_type('level', 'hidden', 5);
        $this->crud->callback_after_update(array($this, 'log_user_after_update'));
        $this->crud->callback_after_insert(array($this, 'log_user_after_update'));
        $this->show();
    }
    function management()
    {
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $state = $this->crud->getState();
        $this->crud->callback_add_field('username', function () {
            return '
        <style>
        /* Container */
.container{
    margin: 0 auto;
    width: 70%;
}

/* Registration */
#div_reg{
    border: 1px solid gray;
    border-radius: 3px;
    width: 470px;
    height: 370px;
    box-shadow: 0px 2px 2px 0px  gray;
    margin: 0 auto;
}

#div_reg h1{
    margin-top:0px;
    font-weight: normal;
    padding:10px;
    background-color:cornflowerblue;
    color:white;
    font-family:sans-serif;
}

#div_reg div{
    clear: both;
    margin-top: 10px;
    padding: 5px;
}

#div_reg .textbox{
    width: 96%;
    padding: 7px;
}

#div_reg input[type=submit]{
    padding: 7px;
    width: 100px;
    background-color: lightseagreen;
    border: 0px;
    color: white;
}

/* Response */
.response{
    padding: 6px;
    display: none;
}

.not-exists{
    color: green;
}

.exists{
    color: red;
}
        </style>
        
        <div>
            <input type="text" class="textbox" id="username" name="username" placeholder="Username"  />
            <div id="uname_response" class="response"></div>
        </div><script>
    $(document).ready(function(){

        $("#username").change(function(){

            var uname = $("#username").val().trim();

            if(uname != \'\'){

                $("#uname_response").show();

                $.ajax({
                    url: \'' . base_url() . 'user/cek_user/\'+uname,
                    type: \'post\',
                    data: {uname:uname},
                    success: function(response){
                       
                        if(response > 0){
                            $("#uname_response").html("<span class=\'exists\'>* Username sudah digunakan, silahkan gunakan username yang lain.</span>");

                        }else{
                            $("#uname_response").html("<span class=\'not-exists\'>Username Ok.</span>");

                        }

                    }
                });
            }else{
                $("#uname_response").hide();
            }

        });

    });
</script>';
        });
        $this->crud->set_table('admin')->fields('username', 'nama_admin', 'alamat', 'level', 'password')->unset_texteditor('alamat')->set_subject('Username')->columns('username', 'level', 'nama_admin')->set_relation('level', 'group_level', 'nama')->display_as('nama_admin', 'nama');
        $this->crud->callback_edit_field('password', array($this, 'set_password_input_to_empty'));
        $this->crud->callback_before_insert(array($this, 'encrypt_password_callback'));
        $this->crud->callback_after_update(array($this, 'log_user_after_update'));
        $this->crud->callback_after_insert(array($this, 'log_user_after_update'));
        $this->show();
    }
    public function no()
    {
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $this->crud->set_table('x')->unset_add()->unset_edit()->unset_delete()->unset_print();
        echo '  <img src="' . base_url() . 'img/error.jpg" alt="Smiley face">';

        echo "Halaman yang dicari ndak ada e.";
        $this->show();
    }

    function agene()
    {
        $id = $this->session->userdata('id_admin');
        $iki = $this->uri->segment(4, 0);
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $this->crud->set_table('data_jamaah_agen')->fields('nama', 'alamat', 'telepon', 'email', 'hp', 'keterangan')->unset_texteditor('alamat')->set_subject('Profile')->unset_back_to_list();
        $fk = $this->session->userdata('fk');
        if ($iki != $fk)
            redirect('user/agene/edit/' . $fk);
        $this->show();
    }
    function customer()
    {
        $id = $this->session->userdata('id_admin');
        $iki = $this->uri->segment(4, 0);
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $this->crud->set_table('data_jamaah')->fields('nama_jamaah', 'alamat_jamaah', 'no_tlp', 'hp_jamaah', 'foto', 'kartukeluarga', 'ktp', 'surat_nikah')->unset_texteditor('alamat')->set_subject('Profile')->unset_back_to_list();
        $fk = $this->session->userdata('fk');
        if ($iki != $fk)
            redirect('user/customer/edit/' . $fk);
        $this->crud->set_field_upload('foto', 'assets/uploads/foto');
        $this->crud->set_field_upload('kartukeluarga', 'assets/uploads/kk');
        $this->crud->set_field_upload('ktp', 'assets/uploads/ktp');
        $this->crud->set_field_upload('surat_nikah', 'assets/uploads/nikah');
        $this->show();
    }
    function profile($action = 0, $user = 0)
    {
        $id = $this->session->userdata('id_admin');
        $iki = $this->uri->segment(4, 0);
        if ($iki != $id)
            if ($iki != "update_validation")
                redirect('user/profile/edit/' . $id);

        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $this->_init();
        $fk = $this->session->userdata('fk');
        $state = $this->crud->getState();
        if ($state == "list")
            redirect('user/profile/edit/' . $id);
        $level = $this->session->userdata('level');
        if ($level == 6) {
            redirect('user/customer/edit/' . $fk);
        } elseif ($level == 5) {
            redirect('user/agene/edit/' . $fk);
        } else {
            $this->crud->set_table('admin')->fields('username', 'nama_admin', 'alamat')->unset_texteditor('alamat')->field_type('username', 'readonly')->set_subject('Username')->unset_back_to_list()->display_as('nama_admin', 'Nama');
        }
        $this->show();
    }

    function set_password_input_to_empty()
    {
        return "<input type='password' name='password' value='' />";
    }
    function encrypt_password_callback($post_array)
    {
        if (!empty($post_array['password'])) {
            $post_array['password'] = md5($post_array['password']);
        } else {
            unset($post_array['password']);
        }
        return $post_array;
    }
    function password($action = 0, $user = 0)
    {
        $id = $this->session->userdata('id_admin');
        $iki = $this->uri->segment(4, 0);
        $idcocok = $id == $iki;
        $lagiedit = $iki == "update_validation";
        if ($idcocok)
            echo " "; //ok
        elseif ($lagiedit)
            echo " ";
        $this->load->model('main_model', '', TRUE);
        $this->load->library('grocery_CRUD');
        $this->crud = new grocery_CRUD();
        $state = $this->crud->getState();
        $this->_init();
        if ($state == "list")
            redirect('user/password/edit/' . $id);
        $this->crud->set_table('admin')->fields('username', 'password')->unset_texteditor('alamat');
        $this->crud->field_type('username', 'readonly');
        $this->crud->callback_edit_field('password', array($this, 'set_password_input_to_empty'))->set_subject('Ganti Password')->unset_back_to_list();
        $this->crud->callback_before_insert(array($this, 'encrypt_password_callback'));
        $this->crud->callback_after_update(array($this, 'log_user_after_update'));
        $this->show();
    }
    function log_user_after_update($post_array, $primary_key)
    {
        $user_logs_update = array(
            "password" => md5($post_array['password']) //$primary_key,

        );
        if (!empty($post_array['password']))
            $this->db->update('admin', $user_logs_update, array('id_admin' => $primary_key));

        return true;
    }
    private function show()
    {
        $this->crud->set_theme('twitter-bootstrap')->unset_export();
        $output = $this->crud->render();
        $this->load->view('ci_simplicity/admin', $output);
    }
}

/* End of file welcome.php */
/* Location: ./system/application/controllers/welcome.php */
