<?php
// Muaz Khan     - www.MuazKhan.com 
// MIT License   - https://www.webrtc-experiment.com/licence/
// Documentation - https://github.com/muaz-khan/RecordRTC

exec("mkdir -p " . $_POST['appnode'] . "/" . $_POST['recording-path']);
exec("chmod -R 777 /var/www/html/survey/public/recordings/" . $_POST['appnode'] . "/" . $_POST['recording-path']);

//header("Access-Control-Allow-Origin: *");
error_reporting(E_ALL);
ini_set('display_errors', 1);


set_error_handler("someFunction");

function someFunction($errno, $errstr) {
    echo '<h2>Upload failed.</h2><br>';
    echo '<p>'.$errstr.'</p>';
}


function selfInvoker()
{
        
    $fileName = '';
    $tempName = '';
    $file_idx = '';
    
    if (!empty($_FILES['audio-blob'])) {
        $file_idx = 'audio-blob';
        $fileName = $_POST['audio-filename'];
        $tempName = $_FILES[$file_idx]['tmp_name'];
    } else {
        $file_idx = 'videoblob';
        $fileName = $_POST['filename'];
        $tempName = $_FILES[$file_idx]['tmp_name'];
    }
    
    if (empty($fileName) || empty($tempName)) {
        if(empty($tempName)) {
            echo 'Invalid temp_name: '.$tempName;
            return;
        }

        echo 'Invalid file name: '.$fileName;
        return;
    }

   
    $filePath ="/var/www/html/survey/public/recordings/". $_POST['appnode'] . "/" . $_POST['recording-path'] . $fileName;
    
    // make sure that one can upload only allowed audio/video files
    $allowed = array(
        'webm',
        'wav',
        'mp4',
        "mkv",
        'mp3',
        'ogg'
    );
   
    
    if (!move_uploaded_file($tempName, $filePath)) {
        echo 'Problem saving file: '.$tempName;
        return;
    }
    
    echo 'success';
}


selfInvoker();

?>
