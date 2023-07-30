<?php

if($_SERVER['REQUEST_METHOD'] === 'POST'){

    session_start();
    $storageLocation = __DIR__ . '/storage/';
    $request = $_GET['action'];
    $export = [];
    unset($_SESSION['file']);

    switch ($request) {
        case 'delete':
            deleteFile();
            break;
        case 'add':
            addFile();
            break;
        
        default:
            uploadFile();
            break;
    }
}

function uploadFile(){

    global $storageLocation;
    global $export;

    $temp_name = $_FILES['file']['tmp_name'];
    $error = $_FILES['file']['error'];

    if(!is_dir($storageLocation)){
        mkdir($storageLocation);
    }

    if($error != 0){
        $_SESSION['errors'] = 'Unable to read zip file';
    }

    $newfileName = $storageLocation . hash('sha256', time()) . '.zip';

    if(file_exists($newfileName)){
        $newfileName = $storageLocation . hash('sha256', time()) . '.zip';
    }

    if(move_uploaded_file($temp_name, $newfileName)){
        try {
            $fileID = explode('.', explode('/',$newfileName)[2])[0];
            $zip = new ZipArchive();
            $zip->open($newfileName);
            
            for ($index=0; $index < $zip->numFiles; $index++) { 
                $fileNames = $zip->statIndex($index);
                array_push($export, $fileNames);
            }
            $zip->close();
            $_SESSION['id'] = $fileID;
            $_SESSION['file']['data'] = $export;
            header('Location: index.php');
        } catch (\Exception $e) {
            error_log($e->getMessage());
            $_SESSION['errors'] = 'Unable to read zip file';
            die;
        }
    }
}



function deleteFile(){

    global $storageLocation;
    global $export;

    $fileName = $_POST['file-name'];
    $index = $_POST['file-index'];
    $fileID = $_POST['fileID'];
    $storedFile = $storageLocation . $fileID . '.zip';

    if(file_exists($storedFile) && is_writable($storedFile))
    {
        try {
            $zip = new ZipArchive();
            $zip->open($storedFile);
            
            if($zip->statName($fileName)){
                if($zip->deleteName($fileName)){
                    $zip->close();
                    $archiveData = fetchArchiveContents($storedFile, $export);
                    $_SESSION['id'] = $fileID;
                    $_SESSION['file']['data'] = $archiveData;
                    $zip->close();
                    header('Location: index.php');
                }
            }
        } catch (\Exception $e) {
            echo $e->getMessage();
            $_SESSION['errors']['file_error'] = 'Unable to read zip file';
            die;
        }
    }else{
        $_SESSION['errors']['exist'] = 'File does not exist or is not writable';
    }
   
}


function addFile(){
    //
}

function fetchArchiveContents(string $file, array $export){
    $data = new ZipArchive();
    if($data->open($file)){
        for ($index=0; $index < $data->numFiles; $index++) { 
            $fileNames = $data->statIndex($index);
            array_push($export, $fileNames);
        }
        $data->close();
        return $export;
    }
    return false;
}

//add file delete