<?php

require_once '../autoload.php';

use Hongbao\Hongbao;


$m1 = memory_get_usage();
$t1 = microtime(true);
$options = [
    'total_money' => isset($_POST['total_money']) ? (float)$_POST['total_money'] : 1000, // 总金额
    'total_number' => isset($_POST['total_number']) ? (int)$_POST['total_number'] : 1000, // 总红包数量
    'minimum_val' => isset($_POST['minimum_val']) ? (float)$_POST['minimum_val'] : 0.01, // 最小随机红包金额
    'maximum_val' => isset($_POST['maximum_val']) ? (float)$_POST['maximum_val'] : 20, // 最大随机红包金额
];
$error = '';
$data = [];
$money_left = 0.00;
try {
    $Hongbao = Hongbao::getInstance()->randomAmount($options);
    $i = 0;
    foreach ($Hongbao as $result) {
        foreach ($result['data'] as &$row) {
            $row = [$i, $row];
            $i++;
        }
        $data = array_merge($result['data'],$data);
        $money_left = $result['money_left'];
    }
} catch (\Exception $e) {
    $error = $e->getMessage();
}
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01//EN" "http://www.w3.org/TR/html4/strict.dtd">
<html>
<head>
    <meta http-equiv="Content-Type" content="text/html; charset=utf-8">
    <title>Flot Examples: Canvas text</title>
    <link href="jquery/examples.css" rel="stylesheet" type="text/css">
    <!--[if lte IE 8]><script language="javascript" type="text/javascript" src="../../excanvas.min.js"></script><![endif]-->
    <script
  src="https://code.jquery.com/jquery-3.5.0.slim.min.js"
  integrity="sha256-MlusDLJIP1GRgLrOflUQtshyP0TwT/RHXsI1wWGnQhs="
  crossorigin="anonymous"></script>
    <script language="javascript" type="text/javascript" src="jquery/jquery.flot.js"></script>
    <script type="text/javascript">

    $(function() {

        var oilPrices = <?php echo json_encode($data); ?>;

        var data = [
            { data: oilPrices, label: "红包金额 (￥)" },
        ];


        var plot = $.plot("#placeholder", data, {
            xaxes: [
                { position: 'bottom' },
                { position: 'top'}
            ],
            yaxes: [
                { position: 'left' },
                { position: 'left' },
                { position: 'right' },
                { position: 'left' }
            ]
        });

        // Create a div for each axis

        $.each(plot.getAxes(), function (i, axis) {
            if (!axis.show)
                return;

            var box = axis.box;

            $("<div class='axisTarget' style='position:absolute; left:" + box.left + "px; top:" + box.top + "px; width:" + box.width +  "px; height:" + box.height + "px'></div>")
                .data("axis.direction", axis.direction)
                .data("axis.n", axis.n)
                .css({ backgroundColor: "#f00", opacity: 0, cursor: "pointer" })
                .appendTo(plot.getPlaceholder());
        });

        // Add the Flot version string to the footer

        $("#footer").prepend("Flot " + $.plot.version + " &ndash; ");
    });

    </script>
</head>
<body>

    <div id="header">
        <h3>红包（<?php echo count($data);?>个, <?php echo bcsub($options['total_money'],$money_left,2);?>总金额），剩余金额：<?php echo $money_left;?></h2>
        <h4><?php echo $error;?></h4>
    </div>

    <div id="content">

        <div class="demo-container">
            <div id="placeholder" class="demo-placeholder"></div>
        </div>

        <p id="click"></p>
        <form class="" action="" method="post">
            <p>
            <label for="">总金额：
                <input type="text" name="total_money" value="<?php echo $options['total_money'];?>">
            </label>
            </p>
            <p>
            <label for="">总红包数：
                <input type="text" name="total_number" value="<?php echo $options['total_number'];?>">
            </label>
            </p>
            <p>
            <label for="">单个红包最小值区间：
                <input type="text" name="minimum_val" value="<?php echo $options['minimum_val'];?>">
            </label>
            </p>
            <p>
            <label for="">单个红包最大值区间：
                <input type="text" name="maximum_val" value="<?php echo $options['maximum_val'];?>">
            </label>
            </p>
            <p>
                <input type="submit" name="submit" value="提交">
            </p>
            <p>
                <?php
                echo "耗时：" . (microtime(true)-$t1);
                echo "</br>";
                echo "消耗内存：" . round((memory_get_usage()-$m1)/1024/1024,2)."MB</br>";
                ?>
            </p>
        </form>
    </div>
</body>
</html>
