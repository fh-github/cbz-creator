<?php

set_time_limit(0);
ini_set("display_errors","on");

/*
shell_exec("open -a File\ Juicer");
shell_exec("sh filejuicer.sh");
*/

//répertoire(s) contenant des images
$dirs_to_scan = array(
	"/Volumes/WD300/_EBOOKS/_JPG"
);
//répertoire de destination des cbz
$dir_cbz = "/Volumes/WD300/_EBOOKS/_CBZ";
$dirs_to_ignore = array('..', '.', '.DS_Store');

foreach ($dirs_to_scan as $dir_to_scan)
{
	$dirs_jpg = array_diff(scandir($dir_to_scan), $dirs_to_ignore);
	if (count($dirs_jpg))
	{	
		foreach ($dirs_jpg as $dir_jpg)
		{
			if (!file_exists("./stop.txt"))
			{
				$filename = str_replace(" Juice", "", $dir_jpg);
				if (!file_exists($dir_cbz."/".$filename.".cbz"))
				{
					$jpgs = array_diff(scandir($dir_to_scan."/".$dir_jpg), $dirs_to_ignore);
					//$jpgs = glob($dir_jpg."/".$subdir_jpg."/*.{jpg}", GLOB_BRACE);
					if (count($jpgs))
					{
						$zip = new ZipArchive();
						if ($zip->open($dir_cbz."/".$filename.".cbz", ZipArchive::CREATE) === TRUE) 
						{
							foreach ($jpgs as $jpg)
							{
								$ext = substr($jpg, -3);
								if (in_array($ext, array("jpg", "png", "gif")))
								{
									if ($jpg == $filename.".".$ext)
										$zip->addFile($dir_to_scan."/".$dir_jpg."/".$jpg, str_replace(".".$ext, "-0.".$ext, $jpg));
									else
										$zip->addFile($dir_to_scan."/".$dir_jpg."/".$jpg, $jpg);
								}
							}
							$zip->close();
						}
					}
				}
			}
		}	
	}
}

?>
