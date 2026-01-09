
<?php $this->load->view('themes/header_1', ['title'=>' reserve seat agen']); ?>
<div class="container-fluid">
    <h3> reserve seat agen</h3>
    <hr>

    <?php if($this->session->flashdata('success')): ?>
        <div class="alert alert-success"><?php echo $this->session->flashdata('success'); ?></div>
    <?php endif; ?>

    <div class="panel panel-default">
        <div class="panel-body">
            <form action="" method="post">

                <div class="form-group">
                    <label><strong>Jenis Jamaah : </strong></label>
                        <div class="form-check">
                            <input class="form-check-input" type="radio" name="jenis_jamaah" id="jamaah_kantor" 
                            value="tipe_jamaah_kantor">
                            <label class="form-check-label" for="jamaah_kantor">
                                Jamaah Kantor
                            </label>
                        </div>
                        <div class="form-check">
                            <input value="tipe_jamaah_agen" class="form-check-input" type="radio" name="jenis_jamaah" id="jamaah_agen" checked>
                            <label class="form-check-label" for="jamaah_agen">
                               Jamaah Agen
                            </label>
                        </div>
                </div>

                <div class="form-group" id="div_agen">
                    <label>
                        <strong>
                         Nama Agen
                        </strong>
                    </label>
                    <select name="agen" id="input_id_agen" class="form-control">
                        <option value="0">-- Pilih Agen --</option>
                        <?php foreach($list_agen as $ag): ?>
                            <option value="<?php echo $ag->id_jamaah; ?>"><?php echo $ag->nama_jamaah; ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="form-group">
                    <label>Jumlah Jamaah (Qty)</label>
                    <input type="number" name="jumlah_jamaah" class="form-control" placeholder="Contoh: 10" required>
                </div>
                
                <div class="form-group" style="margin-top:20px">
                    <button type="submit" name="submit" value="1" class="btn btn-primary">Generate Sekarang</button>
                </div>
            </form>
        </div>
    </div>

    <h4>10 Data Terakhir yang Di-generate</h4>
    <table class="table table-bordered table-striped">
        <thead>
            <tr>
                <th>Nama Jamaah</th>
                <th>Agen</th>
                <th>Action</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach($latest_jamaah as $j): ?>
            <tr>
                <td><?php echo $j->nama_jamaah; ?></td>
                <td><?php echo $j->nama_agen; ?></td>
                <td>
                    <?php if (!empty($j->random_uuid)): ?>
                    <?php $link_share = site_url('JamaahLinkShare/jamaahUUID/' . $j->random_uuid); ?>
                        <button type="button" class="btn btn-warning btn-xs" 
                            data-clipboard-text="<?php echo $link_share; ?>"
            onclick="copyToClipboard('<?php echo $link_share; ?>', this)">
                            <i class="fa fa-copy"></i> Link Edit Jamaah
                        </button>
                    <?php endif; ?>
                  
                </td>
            </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</div>
<?php $this->load->view('themes/footer_1'); ?>
<script>
    function copyToClipboard(text) {
        navigator.clipboard.writeText(text)
            .then(() => {
                alert('Link berhasil disalin!');
            })
            .catch(err => {
                alert('Gagal menyalin link: ' + err);
            });
    }

    $(document).ready(function() {
        $('input[name="jenis_jamaah"]').on('change', function() {
            if ($(this).is(':checked')) {
                var value = $(this).val();
                if(value == 'tipe_jamaah_kantor'){
                    $("#div_agen").css("display","none")
                    $("#input_id_agen").val(0)
                    $('#input_id_agen').prop('required', false);
                    $('#input_id_agen').removeAttr('required');
                }else{
                    $("#div_agen").css("display","block")
                    $('#input_id_agen').attr('required', true);
                }
            }
        });
        //let default_val =  $("#jamaah_kantor").is(':checked');
        // let default_val_agen =  $("#jamaah_agen").is(':checked');
        // if(default_val_agen){
        //       $("#div_agen").css("display","block")
        // }else{
        //       $("#div_agen").css("display","none")
        //       $("#input_id_agen").val(0)
        // }
       
    });


</script>
</html>
