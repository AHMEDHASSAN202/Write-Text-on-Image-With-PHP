<?php

$action = strip_tags(htmlentities($_GET['action']));
$text = strip_tags(htmlentities($_GET['text']));

if ($text) {

    switch ($action) {
        case 'view':


            if (!file_exists('db.php')) {
                die('Oops! not fount db file');
            }

            require_once 'db.php';

            $settings = $db->prepare('SELECT * FROM `settings`');
            $settings->execute();

            $data =[];

            foreach ($settings->fetchAll() AS $key => $value) {

                $data[$value->key] = $value->val;

            }

            //set the content type
            header('Content-type: image/jpeg');

            //create image
            $image = imagecreatefromjpeg($data['main-image']);

            //allocate a color for the text
            list($red, $green, $blue) = explode(',' , $data['color-font']);
            $color = imagecolorallocate($image , $red ,$green ,$blue);

            //font path
            $font = $data['path-font'];

            //print text on image
            imagettftext($image, $data['font-size'], $data['angle'], $data['x'], $data['y'], $color, $font, $text);

            //send image to browser

            //$uploaded = $data['uploaded-folder'] . sha1(time().mt_rand()).'.jpg';
            //imagejpeg($image , $uploaded, 2000);
            imagejpeg($image);

            //clear memory
            imagedestroy($image);


            break;

        case 'download':

            if (!file_exists('db.php')) {
                die('Oops! not fount db file');
            }

            require_once 'db.php';

            $settings = $db->prepare('SELECT * FROM `settings`');
            $settings->execute();

            $data =[];

            foreach ($settings->fetchAll() AS $key => $value) {

                $data[$value->key] = $value->val;

            }

            //set the content type
            header('Content-type: image/jpeg');

            //create image
            $image = imagecreatefromjpeg($data['main-image']);

            //allocate a color for the text
            list($red, $green, $blue) = explode(',' , $data['color-font']);
            $color = imagecolorallocate($image , $red ,$green ,$blue);

            //font path
            $font = $data['path-font'];

            //print text on image
            imagettftext($image, $data['font-size'], $data['angle'], $data['x'], $data['y'], $color, $font, $text);

            //download image in uploaded folder

            if (!is_dir($data['uploaded-folder'])) {
                mkdir($data['uploaded-folder'], 0777);
            }

            $ex_type = strtolower(pathinfo($data['main-image'], PATHINFO_EXTENSION));

            $imageName = time().mt_rand().'.'.$ex_type;
            $uploaded = $data['uploaded-folder'].$imageName;
            imagejpeg($image , $uploaded, 2000);

            switch ($ex_type) {
                case 'png': $ctype='image/png'; break;
                case 'jpeg':
                case 'jpg': $ctype='image/jpg'; break;
                default: $ctype='application/force-download';
            }


            $size = filesize($uploaded);
            header("Pragma: public"); // required
            header("Expires: 0");
            header("Cache-Control: must-revalidate");
            header("Cache-Control: private",false); // required for certain browsers
            header("Content-Type: $ctype");
            header("Content-Type: image/jpg");
            header("Content-Disposition: attachment; filename=\"".basename($uploaded)."\";" );
            header("Content-Transfer-Encoding: binary");
            header("Content-Length: ".$size);
            ob_clean();
            ob_flush();

            readfile($uploaded);

            unlink($uploaded);

            break;

        default:
            die('Oops! 4o4');
    }
    
}else {
    die('<h1 style="color: red; text-align: center;margin-top: 50px">4o4</h1>');
}