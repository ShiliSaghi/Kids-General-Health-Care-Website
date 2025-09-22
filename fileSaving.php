<?php

session_start();

if($_SERVER['REQUEST_METHOD']== "POST"){

    print_r($_REQUEST);
    print_r($_FILES['filename']);

    //Apply security on uploaded file in different level of security-----
    //extract extension-----
    $ext = strtolower(end(explode('.',$_FILES['filename']['name'])));
    echo $ext;
    //restrict extension-----
    $allowedExtensions = ['jpg', 'jpeg', 'png', 'gif'];
    if(in_array($ext,$allowedExtensions)) {
        // upload file only if in allowed types

        //mime type is some kind of meta data that shows the type of file in thier header, br(binery), (png) ...
        // echo mime_content_type('php.gift')."\n";
        // echo mime_content_type('test.php');
        //....

        //random filename, then append extracted extension-----
        $filename = substr(str_shuffle(MD5(microtime())), 0, 10).'.'.$ext;

        // // Save the file to a designated final location on disk----- lab4
        // $uploadPath= "./final/".$filename;
        // move_uploaded_file($_FILES["filename"]['tmp_name'], $uploadPath);


        //Save encrypted file in local path----- lab5
        //AES256 encryption
        $secretkey = "Shili_24092";
        $method = "aes256"; //aes128 etc
        $upload_dir = 'uploads/';

        //Be sure that the uploads directory exists
        if (!is_dir($upload_dir)) {
            mkdir($upload_dir, 0755, true);
        }
        $savedPath = $upload_dir.$filename.'.enc';

        //Read uploaded file
        $content = file_get_contents($_FILES["filename"]['tmp_name']);

        $encrypted_message = openssl_encrypt($content, $method, $secretkey);
        file_put_contents($savedPath, $encrypted_message);

        echo "File uploaded successfully!";
    }

    echo "Form submitted successfully!";
}