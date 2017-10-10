<?php

if (!file_exists('db.php')) {
    die('db file not fount');
}


require_once 'db.php';


$x = $db->prepare('SELECT * FROM `settings`');
$x->execute();

$fetch = $x->fetchAll();

$settings = [];

foreach ($fetch AS $key => $value) {
    $settings[$value->key] = $value->val;
}


?>


<html>
    <head>
        <meta charset="utf-8">
        <style>

            @font-face {
                font-family: 'nobile';
                src: url('font/nobile_bold.ttf');
            }

            .container {
                text-align: center;
            }

            .box {
                position: relative;
                text-align: center;
                margin-top: 20px;
                display: inline-block;
            }
            .text {
                position: absolute;
                word-wrap: break-word;
                font-family: nobile;
            }
            .row {
                margin: 10px;
            }
            .row label {
                font-size: 20px;
                font-weight: bold;
                font-family: sans-serif;
            }
            .row input {
                padding: 10px;
                margin-left: 8px;
                border: 1px solid rgba(0,0,0,.2);
                width: 500px;
            }
            .row a {
                margin-top: 15px;
                cursor: pointer;
                color: dodgerblue;
                margin: 5px;
                font-size: 16px;
                display: inline-block;
            }
        </style>
    </head>
    <body>

        <div class="container">
            <div class="box">
                <img class="img" src="<?php echo $settings['main-image']; ?>">
                <div class="text" style="<?php echo "color: rgb(".$settings['color-font'].");font-size: ".$settings['font-size'].";top: ".$settings['y'].";left: ".$settings['x'].""; ?>">Write Something..</div>
                <div class="row">
                    <label>add text</label><input type="text" name="text" id="text">
                </div>
                <div class="row">
                    <a class="view" target="_blank" href="process.php?action=view&text=">view image</a>
                    <a class="download" target="_blank" href="process.php?action=download&text=">download image</a>
                    <a class="settings" target="_blank" href="admin.php">settings</a>
                </div>
            </div>
        </div>


        <script src="js/jquery-3.1.1.min.js"></script>
        <script>

            if (typeof jQuery == 'undefined') {
                throw new Error('error in load jquery');
            }

            $(function () {

                var text = $('#text');
                var textBox = $('.text');
                var viewUrl = $('.view').attr('href');
                var downloadUrl = $('.download').attr('href');

                text.on('keyup', function() {

                    //change text
                    textBox.text(text.val());

                    $('.view').attr('href', viewUrl + text.val());
                    $('.download').attr('href', downloadUrl + text.val());

                });

            });
        </script>
    </body>
</html>