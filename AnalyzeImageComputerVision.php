<?php
    //save file image
    $subscriptionKey="780253fb73b1447d876d0fc7e6f9ea86";
    $language="en";
	$uploadDir="upload/";
	$fileImage=$_FILES["fileImage"]["name"];    
    $ext=pathinfo($fileImage,PATHINFO_EXTENSION);

	//set name file image
    $nameFile=mktime().".".$ext;
    $uploadfile = $uploadDir.basename($nameFile);
    move_uploaded_file($_FILES["fileImage"]["tmp_name"],$uploadfile);

    //get url of image
    $pageURL='http';
    if($_SERVER["HTTPS"]=="on"){
        $pageURL.="s";
    }
    
    $pageURL.='://'.$_SERVER["SERVER_NAME"].'/'.'upload/'.$nameFile;
    //$pageURL.='://'.'upload.wikimedia.org/wikipedia/commons/1/12/Broadway_and_Times_Square_by_night.jpg';
    
    //set header curl
    $arrHeader=array();
    $arrHeader[]="Content-Type: application/json";
    $arrHeader[]="Ocp-Apim-Subscription-Key: ".$subscriptionKey;
    $url='https://southeastasia.api.cognitive.microsoft.com/vision/v1.0/analyze?visualFeatures=Categories,Tags,Description,Faces,Color,Adult&language='.$language;
    $ch=curl_init(); 
    curl_setopt($ch,CURLOPT_URL,$url); 
    curl_setopt($ch,CURLOPT_HEADER,false); 
    curl_setopt($ch,CURLOPT_POST,true);
    curl_setopt($ch,CURLOPT_HTTPHEADER,$arrHeader);
    $arrPostData=array();
    $arrPostData['Url']=$pageURL;
    curl_setopt($ch,CURLOPT_POSTFIELDS,json_encode($arrPostData));
    curl_setopt($ch,CURLOPT_RETURNTRANSFER,TRUE); 
    $head=curl_exec($ch);
    $httpCode=curl_getinfo($ch,CURLINFO_HTTP_CODE); 
    curl_close($ch);

    //send json
    $response = json_decode($head,true);
    echo json_encode($response);
?>