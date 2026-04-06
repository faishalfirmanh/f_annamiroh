<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>KLAIM FEE REFERRAL - An Namiroh Travelindo</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Inter:wght@400;500;700&amp;family=Playfair+Display:wght@700&display=swap');

        :root {
            --gold: #f5c800;
        }

        body {
            font-family: 'Inter', system-ui, sans-serif;
        }

        .voucher {
            max-width: 1100px;
            margin: 20px auto;
            background: linear-gradient(180deg, #9c0c2e 0%, #7a0822 100%);
            border-radius: 24px;
            box-shadow: 0 25px 50px -12px rgb(0 0 0 / 0.4);
            overflow: hidden;
            position: relative;
            border: 8px solid #f5c800;
        }

        .voucher::before {
            content: '';
            position: absolute;
            left: 0;
            top: 0;
            width: 280px;
            height: 100%;
            background: linear-gradient(90deg, rgba(245, 200, 0, 0.15) 0%, transparent 100%);
            z-index: 1;
            pointer-events: none;
        }

        .gold-curve {
            position: absolute;
            background: linear-gradient(90deg, #f5c800, #ffe066);
            height: 12px;
            filter: blur(1px);
            z-index: 2;
        }

        .logo-text {
            font-family: 'Playfair Display', serif;
            letter-spacing: -1px;
        }

        .amount-box {
            background: white;
            border-radius: 9999px;
            box-shadow: 0 10px 15px -3px rgb(0 0 0 / 0.2);
        }

        .blank-box {
            border: 3px dashed #d1d5db;
            background: white;
            border-radius: 16px;
            height: 90px;
        }

        @media print {
            body {
                margin: 0;
                padding: 0;
            }

            .voucher {
                box-shadow: none;
                margin: 0;
                border-radius: 0;
                border: 6px solid #f5c800;
                page-break-inside: avoid;
            }

            .page-break {
                page-break-before: always;
            }
        }
    </style>
</head>

<body class="bg-gray-100 py-8">
    <?php
    $i = 0;
    // Ganti $coupons dengan array data Anda jika ada (contoh: jumlah voucher yang ingin dicetak)
    // Untuk sekarang kita buat 1 desain saja, bisa di-loop sesuai kebutuhan
    $coupons = [1]; // ganti sesuai jumlah yang Anda butuhkan
    foreach ($coupons as $coupon):
        ?>

        <div class="voucher mx-auto">
            <!-- HEADER -->
            <div class="flex items-start px-8 pt-8 relative">

                <!-- LEFT PANEL -->
                <div class="w-72 flex-shrink-0 border-r-4 border-[#f5c800]/30 pr-8 relative z-10">
                    <div class="flex items-center gap-3 mb-6">
                        <!-- Logo -->
                        <div class="w-14 h-14 bg-white rounded-2xl flex items-center justify-center shadow-inner text-4xl">
                            🕌
                        </div>
                        <div>
                            <div class="text-[#f5c800] font-bold tracking-[2px] text-xl leading-none">AN NAMIROH</div>
                            <div class="text-white text-sm font-medium -mt-0.5">TRAVELINDO</div>
                        </div>
                    </div>

                    <div class="text-white">
                        <h1 class="text-4xl font-bold leading-none mb-3">KLAIM<br>FEE<br>REFERRAL</h1>
                    </div>

                    <!-- Amount Left -->
                    <div class="mt-8">
                        <div
                            class="inline-flex items-center gap-2 bg-white/10 backdrop-blur-md text-white text-2xl font-bold px-6 py-2 rounded-3xl border border-white/30">
                            <span class="text-[#f5c800] text-3xl">Rp</span>
                            5.000.000
                        </div>
                    </div>

                    <!-- Decorative gold line left -->
                    <div class="gold-curve w-40 top-12 left-8 rotate-12" style="height: 6px;"></div>
                </div>

                <!-- MAIN RIGHT SECTION -->
                <div class="flex-1 pl-8 relative z-10">

                    <!-- Top Logo -->
                    <div class="flex justify-between items-start mb-4">
                        <div class="flex items-center gap-3">
                            <div
                                class="w-12 h-12 bg-white rounded-2xl flex items-center justify-center shadow-inner text-4xl">
                                🕌
                            </div>
                            <div class="text-[#f5c800]">
                                <div class="font-bold text-2xl tracking-widest">AN NAMIROH</div>
                                <div class="text-white text-base -mt-1">TRAVELINDO</div>
                            </div>
                        </div>

                        <div class="text-right">
                            <div
                                class="inline-flex bg-white text-[#9c0c2e] text-xs font-bold px-5 py-1 rounded-3xl items-center gap-2 shadow">
                                <span class="text-lg">✦</span>
                                OFFICIAL
                            </div>
                        </div>
                    </div>

                    <!-- Title -->
                    <h1 class="text-white text-6xl font-bold tracking-[-2px] leading-none mb-6">
                        KLAIM FEE REFERRAL
                    </h1>

                    <!-- Big Amount -->
                    <div class="amount-box px-10 py-6 mb-8 flex items-center justify-center">
                        <div class="flex items-baseline gap-3">
                            <span class="text-[#9c0c2e] text-6xl font-bold">Rp</span>
                            <span class="text-[#9c0c2e] text-[92px] font-bold leading-none tracking-[-4px]">5.000.000</span>
                        </div>
                    </div>

                    <!-- Decorative gold curve bottom -->
                    <div class="gold-curve bottom-6 left-1/3 w-80 rotate-[-8deg]" style="height: 8px;"></div>

                    <!-- Contact & Info -->
                    <div class="grid grid-cols-2 gap-8 mt-10">
                        <div>
                            <div class="text-[#f5c800] text-sm font-bold tracking-widest mb-2">INFORMASI &amp; PENDAFTARAN
                            </div>
                            <div class="text-white space-y-1 text-lg font-semibold">
                                <div>0858 5766 6457</div>
                                <div>0813 2874 5647</div>
                                <div>0812 4938 8261</div>
                            </div>
                        </div>

                        <div>
                            <div class="flex flex-wrap gap-x-6 gap-y-1 text-white text-sm">
                                <a href="#" class="flex items-center gap-1 hover:text-[#f5c800]">
                                    <span class="text-[#f5c800]">📸</span> @annamiroh.pusat
                                </a>
                                <a href="#" class="flex items-center gap-1 hover:text-[#f5c800]">
                                    <span class="text-[#f5c800]">📸</span> @annamiroh_pusat
                                </a>
                                <a href="#" class="flex items-center gap-1 hover:text-[#f5c800]">
                                    <span class="text-[#f5c800]">📘</span> Namirohtravel
                                </a>
                                <a href="#" class="flex items-center gap-1 hover:text-[#f5c800]">
                                    <span class="text-[#f5c800]">🌐</span> namiroh.com
                                </a>
                                <a href="#" class="flex items-center gap-1 hover:text-[#f5c800]">
                                    <span class="text-[#f5c800]">▶️</span> an namiroh travelindo
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </div>

            <!-- BOTTOM SECTION -->
            <div class="px-8 pb-8 pt-4 border-t border-white/10 flex gap-8 items-end">

                <!-- Left Blank -->
                <div class="flex-1">
                    <div class="blank-box w-full flex items-center justify-center text-gray-400 text-sm">
                        <!-- Tempat tanda tangan / nama penerima -->
                    </div>
                </div>

                <!-- Center Blank -->
                <div class="flex-1">
                    <div class="blank-box w-full flex items-center justify-center text-gray-400 text-sm">
                        <!-- Tempat tanda tangan / nama penerima -->
                    </div>
                </div>

                <!-- Right side small text -->
                <div class="text-white text-xs max-w-[220px] leading-tight text-right">
                    Voucher ini sah dan dapat digunakan hanya satu kali.<br>
                    Terima kasih telah menjadi bagian dari An Namiroh Travelindo.
                </div>
            </div>

            <!-- Bottom gold accent -->
            <div class="h-4 bg-gradient-to-r from-[#f5c800] via-[#ffe066] to-[#f5c800]"></div>
        </div>

        <?php
        $i++;
        if ($i % 2 == 0) {
            echo '<div class="page-break"></div>'; // Page break setiap 2 voucher (bisa diubah)
        }
    endforeach;
    ?>

    <script>
        // Tailwind script sudah di-load
        function initTailwind() {
            return {
                config(userConfig = {}) {
                    return {
                        content: [],
                        theme: {
                            extend: {}
                        }
                    }
                },
                theme(userConfig = {}) {
                    return {
                        ...this.defaultTheme(),
                        ...userConfig,
                    }
                },
                defaultTheme() {
                    return {
                        extend: {
                            colors: {
                                gold: '#f5c800'
                            }
                        }
                    }
                }
            }
        }
        console.log('%c✅ Voucher HTML siap digunakan!', 'color:#f5c800; font-size:13px; font-weight:bold;');
    </script>
</body>

</html>