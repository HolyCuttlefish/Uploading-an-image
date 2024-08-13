<?php
$uploadDir = 'FileImeg/';
$args = null;
$json = null;
$img = null;
$fileTypes = array('image/jpg', 'image/jpeg', 'image/png');
$uploadedFiles = array();

if($_SERVER['REQUEST_METHOD'] == 'POST') 
{
	$json = $_POST['json'];
	$data = json_decode($json, true);
	$args = $data['fio'];	

	if((isset($_FILES['images'])) && (is_array($_FILES['images']['name'])))
	{
		$fileCount = count($_FILES['images']['name']);

		// Убедитесь, что директория назначения существует
		if (!is_dir($uploadDir)) {
   	 	if(!mkdir($uploadDir, 0755, true)){
					var_dump(error_get_last());

					die;
			}
		}

		for($count = 0; $count < $fileCount; ++$count)
		{
			if($_FILES['images']['error'][$count] ===	UPLOAD_ERR_OK)
			{
				$img = $_FILES['images'];

				if($img['size'][$count] <= 1000000)
				{
					if(in_array($img['type'][$count], $fileTypes))
					{
    				$uploadFilePath = $uploadDir . uniqid('', true) . basename($img['name'][$count]);

    				if (move_uploaded_file($img['tmp_name'][$count], $uploadFilePath)) 
						{
       				$uploadedFiles[] = 	uniqid('', true) . basename($img['name'][$count]);
    				} else 
						{
        			echo json_encode(array("Error" => "Ошибка при загрузке файла."));
    					http_response_code(400);

							die;
						}
					} else
					{
						continue;
					} 
				}else
				{
					continue;
				}
			} else
			{
				echo json_encode(array("Error" => "Ошибка при загрузке изображений"));
				http_response_code(400);

				die;
			}
		}
		
		echo json_encode(array("Succes" => "Подходящие файлы были загружены"));
		http_response_code(200);
	} else
	{
    echo json_encode(array("Error" => "Файл не был загружен."));
		http_response_code(400);

		die;
	}
} else
{
	echo json_encode(array("Error" =>"Не было запроса"));
	http_response_code(400);

	die;
}
