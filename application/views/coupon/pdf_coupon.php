<!DOCTYPE html>
<html>

<head>
    <style>
        body {
            font-family: Arial, sans-serif;
        }

        .coupon {
            border: 2px dashed #000;
            padding: 20px;
            margin: 15px 0;
            text-align: center;
            font-size: 24px;
            width: 90%;
            margin-left: auto;
            margin-right: auto;
        }

        .coupon-code {
            font-size: 32px;
            font-weight: bold;
            letter-spacing: 5px;
            margin: 15px 0;
        }
    </style>
</head>

<body>

    <?php
    $i = 0;
    foreach ($coupons as $coupon):
        if ($i % 3 == 0 && $i != 0)
            echo '<div style="page-break-before: always;"></div>';
        ?>
        <div class="coupon">
            <h2>Kupon Diskon</h2>
            <div class="coupon-code">
                <?php echo $coupon->code_coupon; ?>
            </div>
            <p>Gunakan kode ini untuk mendapatkan diskon</p>
            <small>Berlaku sekali pakai • Dibuat:
                <?php echo $coupon->created_at; ?>
            </small>
        </div>
        <?php
        $i++;
    endforeach;
    ?>

</body>

</html>