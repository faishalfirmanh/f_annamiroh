
<?php foreach($css_files as $file): ?>
	<link type="text/css" rel="stylesheet" href="<?= $file; ?>" />
<?php endforeach; ?>
<?php foreach($js_files as $file): ?>
	<script src="<?php echo $file; ?>"></script>
<?php endforeach; ?>

<div class="container">
    <h1> <?= $title ?> </h1>
    <form id="contact" method="POST" action="<?= $urlSave ?>">
        <input required type="hidden" name="id_tipe_koper" value="<?= $idTipeKoper ?>">
        <!-- <input required type="hidden" name="type" value="<?= $type ?>"> -->

        <div style="text-align: left;">
            <div class="line">
                <label style="text-align: left;" for="name">Nama Tipe Koper </label>
            </div>
            <div class="line">
                <input style="margin-right: 30px; vertical-align: baseline;" required type="text" name="nama" id="nama" value="<?= @$tipeKoper->nama ?>">
            </div>
        </div>

        <div style="text-align: left;margin-top:40px;">
            <div class="line">
                <label style="text-align: left;" for="name">Daftar Barang : </label>
            </div>
        </div>
        <?php
        foreach (@$barangAll as $barang) : ?>
            <div class="" style="margin-left:0px; margin-top:10px;">
                <div class="line">
                    <label style="margin-left: 30px;" for="name"><?= $barang->nama_barang ?></label>
                    <input style="margin-left: 30px;" class="checkbox" <?= @$barang->is_active ? "checked" : "" ?> data-id="<?= $barang->id ?>" type="checkbox" id="checkbox_<?= $barang->id ?>" name="checkbox_<?= $barang->id ?>">
                </div>
            </div>
        <?php endforeach ?>

        <!-- <div class="" style="margin-left:0px; margin-top:20px;">
            <div class="line">
                <label style="margin-left: 30px;" for="name">Pilih semua </label>
                <input style="margin-left: 30px;" type="checkbox" id="checkAll">

            </div>
        </div> -->

        <div class="line">
            <input type="submit" name="submit" value="finish" class="button">
        </div>

    </form>
</div>

<script src="<?= base_url() ?>/assets/themes/default/js/jquery-1.9.1.min.js"></script>

<script>
    $('#checkAll').change(function() {
        if (this.checked) {
            console.log("Dd")
            $(".checkbox").trigger('click');
        }
    })

    // $(".checkbox").change(function() {
    //     let id = $(this).data("id");
    //     console.log(id)

    //     if (this.checked) {
    //         console.log("y")
    //         $(`#check_${id}`).removeAttr('disabled')
    //     } else {
    //         console.log('else')
    //         $(`#qty_${id}`).attr('disabled', true)
    //     }
    // });
</script>
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