<?php require APP_ROOT . '/views/admin/inc/head.php'; ?>
<script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/2.9.4/Chart.js">
</script>

<body>
    <?php require APP_ROOT . '/views/admin/inc/sidebar.php'; ?>

    <div class="main-content">


        <main>

            <h2 class="dash-title">TỔNG QUAN</h2>

            <div class="dash-cards">
                <div class="card-single">
                    <div class="card-body">
                        <span class="ti-briefcase"></span>
                        <div>
                            <h5>TỎNG DOANH THU</h5>
                            <h4><?= number_format($data['totalRevenue']['total'], 0, '', ',') ?> VND</h4>
                        </div>
                    </div>
                </div>

                <div class="card-single">
                    <div class="card-body">
                        <span class="ti-user"></span>
                        <div>
                            <h5>KHÁCH HÀNG</h5>
                            <h4><?= number_format($data['totalClient']['total'], 0, '', ',') ?></h4>
                        </div>
                    </div>
                </div>

                <div class="card-single">
                    <div class="card-body">
                        <span class="ti-check-box"></span>
                        <div>
                            <h5>ĐƠN HÀNG HOÀN THÀNH</h5>
                            <h4><?= $data['totalOrderCompleted']['total'] ?></h4>
                        </div>
                    </div>
                </div>
            </div>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Doanh thu tháng này</h3>
                        <canvas id="myChart" style="width:100%;max-width:700px"></canvas>
                    </div>
                </div>
            </section>
            <section class="recent">
                <div class="activity-grid">
                    <div class="activity-card">
                        <h3>Sản phẩm bán chạy trong tháng</h3>
                        <canvas id="myChart2" style="width:100%;max-width:700px"></canvas>
                    </div>
                </div>
            </section>

        </main>

    </div>
    <script>
        const d = new Date();
        var xValues = <?php echo "[";
                        for ($i = 0; $i < count($data['days']); $i++) {
                            if ($i + 1 < count($data['days'])) {
                                echo "'ngày " . $data['days'][$i] . "',";
                            } else {
                                echo "'ngày " . $data['days'][$i] . "'";
                            }
                        }
                        echo "]" ?>;
        var yValues = <?php echo "[";
                        for ($i = 0; $i < count($data['totals']); $i++) {
                            if ($i + 1 < count($data['totals'])) {
                                echo $data['totals'][$i] . ",";
                            } else {
                                echo $data['totals'][$i];
                            }
                        }
                        echo "]" ?>;
        var barColors = <?php echo "[";
                        for ($i = 0; $i < count($data['totals']); $i++) {
                            if ($i + 1 < count($data['totals'])) {
                                echo "'rgba(" . rand(100, 250) . "," . rand(100, 250) . "," . rand(100, 250) . ")', ";
                            } else {
                                echo "'rgba(" . rand(100, 250) . "," . rand(100, 250) . "," . rand(100, 250) . ")'";
                            }
                        }
                        echo "]" ?>;

        new Chart("myChart", {
            type: "bar",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "Biểu đồ doanh thu tháng "+d.getMonth()
                }
            }
        });

        var xValues = <?php echo "[";
                        for ($i = 0; $i < count($data['names']); $i++) {
                            if ($i + 1 < count($data['names'])) {
                                echo "'" . $data['names'][$i] . "',";
                            } else {
                                echo "'" . $data['names'][$i] . "'";
                            }
                        }
                        echo "]" ?>;
        var yValues = <?php echo "[";
                        for ($i = 0; $i < count($data['totalsoldCount']); $i++) {
                            if ($i + 1 < count($data['totalsoldCount'])) {
                                echo $data['totalsoldCount'][$i] . " ,";
                            } else {
                                echo $data['totalsoldCount'][$i];
                            }
                        }
                        echo "]" ?>;
        var barColors = <?php echo "[";
                        for ($i = 0; $i < count($data['totalsoldCount']); $i++) {
                            if ($i + 1 < count($data['totalsoldCount'])) {
                                echo "'rgba(" . rand(100, 250) . "," . rand(100, 250) . "," . rand(100, 250) . ")', ";
                            } else {
                                echo "'rgba(" . rand(100, 250) . "," . rand(100, 250) . "," . rand(100, 250) . ")'";
                            }
                        }
                        echo "]" ?>;

        new Chart("myChart2", {
            type: "pie",
            data: {
                labels: xValues,
                datasets: [{
                    backgroundColor: barColors,
                    data: yValues
                }]
            },
            options: {
                legend: {
                    display: false
                },
                title: {
                    display: true,
                    text: "Biểu đồ sản phẩm bán chạy trong tháng "+d.getMonth()
                }
            }
        });
    </script>
</body>

</html>