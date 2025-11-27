<?php
foreach ($output->css_files as $file) : ?>
    <link type="text/css" rel="stylesheet" href="<?php echo $file; ?>" />
<?php endforeach; ?>
<?php foreach ($output->js_files as $file) : ?>
    <script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>
<link href="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/css/select2.min.css" rel="stylesheet" />
<script src="https://cdnjs.cloudflare.com/ajax/libs/select2/4.0.6-rc.0/js/select2.min.js"></script>

<div class="container" style="margin-left: 50px;">
    <form class="<?= @$idKoperJamaah ? 'hidden' : '' ?> " id="contact" method="POST" action="<?= $urlSave ?>">
        <div style="text-align: left;margin-top: 20px;">
            <div class="line">
                <label style="text-align: left;" for="name">Tanggal Awal</label>
            </div>
            <div class="line">
                <input value="<?= @$selTglAwal ?>" style="margin-right: 30px; vertical-align: baseline;" type="date" name="tanggal_awal" id="tanggal_awal">
            </div>
        </div>

        <div style="text-align: left;margin-top: 20px;">
            <div class="line">
                <label style="text-align: left;" for="name">Tanggal Akhir</label>
            </div>
            <div class="line">
                <input value="<?= @$selTglAkhir ?>" style="margin-right: 30px; vertical-align: baseline;" type="date" name="tanggal_akhir" id="tanggal_akhir">
            </div>
        </div>

        <!-- <div style="text-align: left;margin-top: 20px;">
            <div class="line">
                <label style="text-align: left;" for="name">Nama Barang </label>
            </div>
            <div class="line">
                <select style="margin-right: 30px; vertical-align: baseline;" name="nama_barang" id="nama_barang">
                    <option value="0" <?= @$selBarang == "" ? 'selected' : '' ?>> ~ Semua Barang ~ </option>

                    <?php foreach ($allBarang as $v) : ?>
                        <option <?= @$selBarang == $v->id  ? 'selected' : '' ?> value="<?= $v->id ?>"><?= $v->nama ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
        </div> -->

        <div class="<?= @$idKoperJamaah ? 'hidden' : '' ?> ">
            <div style="text-align: left;margin-top: 20px;">
                <div class="line">
                    <label style="text-align: left;" for="name">Nama Jamaah </label>
                </div>
                <div class="line">
                    <select placeholder="Nama Jamaah" style="margin-right: 30px; vertical-align: baseline;" name="nama_jamaah" id="nama_jamaah">
                        <option value="0" <?= $selJamaah == "" ? 'selected' : '' ?>> ~ Semua Jamaah ~</option>

                        <?php foreach ($allJamaah as $v) : ?>
                            <option <?= $selJamaah == $v->id_jamaah  ? 'selected' : '' ?> value="<?= $v->id_jamaah ?>"><?= $v->nama_jamaah ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>

            <div style="text-align: left;margin-top: 20px;">
                <div class="line">
                    <label style="text-align: left;" for="name">Nama Paket </label>
                </div>
                <div class="line">
                    <select placeholder="Nama Paket" style="margin-right: 30px; vertical-align: baseline;" name="nama_paket" id="nama_paket">
                        <option value="0" <?= $selPaket == "" ? 'selected' : '' ?>> ~ Semua Paket ~ </option>

                        <?php foreach ($allPaket as $v) : ?>
                            <option <?= $selPaket == $v->id  ? 'selected' : '' ?> value="<?= $v->id ?>"><?= $v->estimasi_keberangkatan ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
            </div>
        </div>

        <div class="line" style="margin-top: 20px;">
            <input type="submit" name="submit" value=" Filter " class="button">
        </div>

    </form>
</div>
<?= $output->output ?>

<style>
    @media only screen and (min-width : 80px) {

        /* h1 {
            font: 500 160%/100% Ubuntu;
            margin: 0 0 20px 10px;
        } */

        label,
        input {
            display: inline-block;
            font-weight: bolder;

        }

        #contact label {
            font-size: 100%;
            width: 20%;
            text-align: right;
        }

        #contact label[for=message] {
            vertical-align: top
        }

        /* #contact input[type=text],
        #contact input[type=number],
        #contact textarea {
            width: 66.6667%;
        }

        #contact .button {
            margin-left: 24%;
            font-size: 90%;
        } */
    }




    inputs #contact input[type=text],
    #contact input[type=date],
    #contact input[type=number],
    #contact textarea {
        color: #666;
        box-sizing: border-box;
        border: 1px solid #CCC;
        border-radius: 3px;
        padding: 5px 8px;
    }

    #contact input[type=text],
    #contact input[type=date],
    #contact input[type=number] {
        height: 30px
    }

    #contact textarea {
        height: 200px;
        min-height: 200px;
        font-family: Ubuntu;
        overflow: auto;
        /* Removing default Scrollbar from IE */
    }

    #contact input[type=text]:focus,
    #contact input[type=date]:focus,
    #contact input[type=number]:focus,
    #contact textarea:focus {
        outline: none;
        box-shadow: inset 1px 2px 3px #CCC;
    }

    /* Submit button */
    #contact .button {
        padding: 10px 15px;
        border: 1px solid #1A4F82;
        border-radius: 3px;
        color: #CFE6FC;
        font-family: Ubuntu;
        font-weight: 300;
        background-color: #1A4F82;
        background-image: linear-gradient(#215F9C, #1A4F82);
        -o-background-image: linear-gradient(#215F9C, #1A4F82);
        cursor: pointer;
    }

    #contact .button:hover {
        -webkit-transition: all 0.5s ease-in-out 0s;
        -moz-transition: all 0.5s ease-in-out 0s;
        -o-transition: all 0.5s ease-in-out 0s;
        transition: all 0.5s ease-in-out 0s;
        background-image: linear-gradient(#2B75BD, #1F5F9C);
        -o-background-image: linear-gradient(#2B75BD, #1F5F9C);
        color: #FFF;
        box-shadow: 2px 2px 3px #999;
    }
</style>

<script>
    $(document).ready(function() {
        $('a[href="#filtering-form-search"]').addClass('hidden');
        $('#nama_barang').select2();
        $('#nama_paket').select2();
        $('#nama_jamaah').select2();
    });
</script>