<!DOCTYPE html>
<html>

<head>
    <title><?php echo $title; ?></title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 40px;
        }

        input[type="number"] {
            padding: 10px;
            width: 200px;
            font-size: 16px;
        }

        button {
            padding: 12px 20px;
            font-size: 16px;
            background: #28a745;
            color: white;
            border: none;
            cursor: pointer;
        }

        button:hover {
            background: #218838;
        }

        .result {
            margin-top: 30px;
            background: #f8f9fa;
            padding: 20px;
            border-radius: 8px;
        }
    </style>
</head>

<body>

    <h2>Generate Kode Coupon</h2>

    <?php if (isset($success) && $success): ?>
        <div class="result">
            <h3>✅ Berhasil generate <?php echo $total; ?> kode coupon!</h3>
            <pre><?php print_r($coupons); ?></pre>
        </div>
    <?php endif; ?>

    <?php if (isset($error)): ?>
        <p style="color:red;"><?php echo $error; ?></p>
    <?php endif; ?>

    <?php echo form_open('coupon'); ?>

    <p>
        <label for="jumlah"><strong>Masukkan Jumlah Coupon yang ingin dibuat:</strong></label><br>
        <input type="number" name="jumlah" id="jumlah" min="1" max="1000" value="<?php echo set_value('jumlah', 10); ?>"
            required>
    </p>

    <button type="submit">Generate Coupon</button>

    <?php echo form_close(); ?>

</body>

</html>