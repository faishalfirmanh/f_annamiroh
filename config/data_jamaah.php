<?php
//session
$id = $this->session->userdata('id_jamaah');
//Data Jamaah
$jamaah = $this->Jamaah_model->get_jamaah_by_id($id);
//
$data['default']['id_jamaah'] = $jamaah->id_jamaah;
$data['default']['no_porsi'] = $jamaah->no_porsi;
$data['default']['nama_jamaah'] = $jamaah->nama_jamaah;
$data['default']['nama_ortu'] = $jamaah->nama_ortu;
?>