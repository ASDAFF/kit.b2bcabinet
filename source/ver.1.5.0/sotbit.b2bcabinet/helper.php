<?
CModule::IncludeModule("main");
CModule::IncludeModule("iblock");

set_time_limit(0);

if (!function_exists("GetCurVersion")){
    function GetCurVersion($versionFile){
        $ver = false;
        if(file_exists($versionFile)){
            $arModuleVersion = array();
            include($versionFile);
            $ver = trim($arModuleVersion["VERSION"]);
        }
        return $ver;
    }
}

if (!function_exists("removeDirectory"))
{
	function removeDirectory($directory) {
		$dir = opendir($directory);

		while(($file = readdir($dir)))
		{
			if ( is_file ($directory."/".$file))
			{
				unlink ($directory."/".$file);
			}
			else if ( is_dir ($directory."/".$file) && ($file != ".") && ($file != ".."))
			{
				removeDirectory ($directory."/".$file);
			}
		}
		closedir ($dir);
		rmdir($directory);
	}
}

if (!function_exists("CreateBakFile")){
    function CreateBakFile($file, $curVersion = CURRENT_VERSION){
        $file = trim($file);
        if(file_exists($file)){
            $arPath = pathinfo($file);
            $backFile = $arPath['dirname'].'/_'.$arPath['basename'].'.back'.$curVersion;
            if(!file_exists($backFile)){
                @copy($file, $backFile);
            }
        }
    }
}

/*if (!function_exists("UpdaterLog")){
    function UpdaterLog($str){
        static $fLOG;
        if($bFirst = !$fLOG){
            $fLOG = $_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/'.MODULE_NAME.'/updaterlog.txt';
        }
        if(is_array($str)){
            $str = print_r($str, 1);
        }
        @file_put_contents($fLOG, ($bFirst ? PHP_EOL : '').date("d.m.Y H:i:s", time()).' '.$str.PHP_EOL, FILE_APPEND);
    }
}*/


if (!function_exists("GetSites")){
    function GetSites(){
        $arRes = array();
        $dbRes = CSite::GetList($by="sort", $order="desc", array("ACTIVE" => "Y"));
        while($item = $dbRes->Fetch()){
            $dbResTemplate = CSite::GetTemplateList($item["LID"]);
            while($itemTemplate = $dbResTemplate->Fetch()){
                $item["TEMPLATE"][$itemTemplate["TEMPLATE"]] = $itemTemplate;
            }
            $arRes[$item["LID"]] = $item;
        }
        return $arRes;
    }
}

if (!function_exists("changeMacroses")){
	function changeMacroses($macro)
	{
		$dir = __DIR__.'/';
		$files = readDirectory($dir);
		if($files)
		{
			foreach($files as $file)
			{
				changeMacro($file,$macro);
			}
		}
	}
}

if (!function_exists("changeMacro")){
	function changeMacro($file = '',$macro){
		
		if(stripos($file, ".php")!==false)
		{
			$bool = false;
			$text = file_get_contents($file);
			if(strpos($text,'IncludeComponent') !== false)
			{
				//iblock
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0] && $macro[$digitals[0]])
						{
							$matchnew = str_replace($digitals[0],$macro[$digitals[0]],$match);
							$text = str_replace($match,$matchnew,$text);
							$bool = true;
						}
					}
				}
                //stores
                preg_match_all("#STORES(.*)\s*(.*)#", $text, $matches);
                if($matches[0])
                {
                    foreach($matches[0] as $match)
                    {
                        $matchnew = str_replace(6,$macro['STORE'],$match);
                        $text = str_replace($match,$matchnew,$text);
                        $bool = true;
                        break;
                    }
                }
				//site_dir
				preg_match_all("#SEF_FOLDER(.*),#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('#/(.*)/#', $match,$path);
						if($path[0] )
						{
							$matchnew = str_replace($path[0],$macro['SITE_DIR'].substr($path[0],1,strlen($path[0])-1),$match);
							$text = str_replace($match,$matchnew,$text);
							$bool = true;
						}
					}
				}
				//basket_dir
				preg_match_all("#BASKET_URL(.*),#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						if(strpos($match,'SITE_DIR') === false)
						{
							preg_match('#/(.*)/#', $match,$path);
							if($path[0] )
							{
								$matchnew = str_replace($path[0],$macro['SITE_DIR'].substr($path[0],1,strlen($path[0])-1),$match);
								$text = str_replace($match,$matchnew,$text);
								$bool = true;
							}
						}
					}
				}
				//
				preg_match_all("#PATH_TO_CATALOG(.*),#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						if(strpos($match,'SITE_DIR') === false)
						{
							preg_match('#/(.*)/#', $match,$path);
							if($path[0] )
							{
								$matchnew = str_replace($path[0],$macro['SITE_DIR'].substr($path[0],1,strlen($path[0])-1),$match);
								$text = str_replace($match,$matchnew,$text);
								$bool = true;
							}
						}
					}
				}
				//
				preg_match_all("#PATH_TO_CONTACT(.*),#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						if(strpos($match,'SITE_DIR') === false)
						{
							preg_match('#/(.*)/#', $match,$path);
							if($path[0] )
							{
								$matchnew = str_replace($path[0],$macro['SITE_DIR'].substr($path[0],1,strlen($path[0])-1),$match);
								$text = str_replace($match,$matchnew,$text);
								$bool = true;
							}
						}
					}
				}
				//forms
				preg_match_all("#WEB_FORM_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0] && $macro['FORMS'][$digitals[0]])
						{
							$matchnew = str_replace($digitals[0],$macro['FORMS'][$digitals[0]],$match);
							$text = str_replace($match,$matchnew,$text);
							$bool = true;
						}
					}
				}
			}
			if($bool)
			{
				file_put_contents($file, $text);
			}
		}
	}
}



if (!function_exists("getMacro")){
	function getMacro($arrSites)
	{
		$return = [];
		$siteDirs = [];
		foreach($arrSites as $sid => $path_site)
		{
			if(is_dir($_SERVER['DOCUMENT_ROOT'].$path_site['DIR'].'include/blocks'))
			{
				$siteDirs[$sid] = $path_site['DIR'];
			}
		}

		if($siteDirs)
		{
			foreach($siteDirs as $sid => $siteDir)
			{

				//advantages
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'include/blocks/advantages/content.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					preg_match('!\d+!', $matches[0][0],$digitals);
					if($digitals[0])
					{
						$return[12] = $digitals[0];
					}
				}
				//banners
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'include/blocks/banner/content.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					preg_match('!\d+!', $matches[0][0],$digitals);
					if($digitals[0])
					{
						$return[27] = $digitals[0];
					}
				}
				//blog
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'blog/index.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					preg_match('!\d+!', $matches[0][0],$digitals);
					if($digitals[0])
					{
						$return[14] = $digitals[0];
					}
				}
				//brands
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'brands/index.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return[13] = $digitals[0];
						}
					}
				}
				//news
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'news/index.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					preg_match('!\d+!', $matches[0][0],$digitals);
					if($digitals[0])
					{
						$return[11] = $digitals[0];
					}
				}
				//shops
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/local/templates/b2bcabinet/theme/contacts/1/content.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					preg_match('!\d+!', $matches[0][0],$digitals);
					if($digitals[0])
					{
						$return[26] = $digitals[0];
					}
				}
				//promotions
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'promotions/index.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					preg_match('!\d+!', $matches[0][0],$digitals);
					if($digitals[0])
					{
						$return[10] = $digitals[0];
					}
				}
				//services
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'services/index.php');
				preg_match_all("#IBLOCK_ID(.*),#", $text, $matches);
				if($matches[0])
				{
					preg_match('!\d+!', $matches[0][0],$digitals);
					if($digitals[0])
					{
						$return[5] = $digitals[0];
					}
				}

                //stores
                $text = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/catalog/index.php');
                preg_match_all("#STORES(.*)\s*(.*)#", $text, $matches);
                if($matches[0])
                {
                    foreach($matches[0] as $match)
                    {
                        preg_match_all('!\d+!', $match,$digitals);
                        $return['STORE'] = $digitals[0][1];
                        break;
                    }
                }

				//forms
				//1
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/local/templates/b2bcabinet/theme/footers/1/content.php');
				preg_match_all("#WEB_FORM_ID(.*)\n#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return['FORMS'][1] = $digitals[0];
						}
					}
				}
				//2
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/local/templates/b2bcabinet/components/bitrix/news/sotbit_origami_shops/detail.php');
				preg_match_all("#WEB_FORM_ID(.*)\n#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return['FORMS'][2] = $digitals[0];
						}
					}
				}
				//3
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].'/local/templates/b2bcabinet/theme/contacts/1/content.php');
				preg_match_all("#WEB_FORM_ID(.*)\n#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return['FORMS'][3] = $digitals[0];
						}
					}
				}
				//4
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'/include/ajax/callbackphone.php');
				preg_match_all("#WEB_FORM_ID(.*)\n#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return['FORMS'][4] = $digitals[0];
						}
					}
				}
				//5
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'/include/ajax/foundcheaper.php');
				preg_match_all("#WEB_FORM_ID(.*)\n#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return['FORMS'][5] = $digitals[0];
						}
					}
				}
				//6
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'/include/ajax/wantgift.php');
				preg_match_all("#WEB_FORM_ID(.*)\n#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return['FORMS'][6] = $digitals[0];
						}
					}
				}
				//7
				$text = file_get_contents($_SERVER['DOCUMENT_ROOT'].$siteDir.'/include/ajax/checkstock.php');
				preg_match_all("#WEB_FORM_ID(.*)\n#", $text, $matches);
				if($matches[0])
				{
					foreach($matches[0] as $match)
					{
						preg_match('!\d+!', $match,$digitals);
						if($digitals[0])
						{
							$return['FORMS'][7] = $digitals[0];
						}
					}
				}
			}
		}
		return $return;
	}
}


if (!function_exists("backupFiles")){
	function backupFiles($arrSites)
	{
		$str = '';
		backupModuleFiles($str);
		backupTemplateFiles($str);
		backupPublicFiles($arrSites);
	}
}

if (!function_exists("backupModuleFiles"))
{
	function backupModuleFiles($str)
	{
		$dir = __DIR__.'/';
		$files = readDirectory($dir);
		if($files)
		{
			foreach($files as $file)
			{
				if(strpos($file,'wizards') !== false)
				{
					continue;
				}
				$path = str_replace($dir, '',$file);
				CreateBakFile($_SERVER['DOCUMENT_ROOT'].'/bitrix/modules/sotbit.b2bcabinet/'.$path);
			}
		}
	}
}

if (!function_exists("backupTemplateFiles"))
{
	function backupTemplateFiles($str)
	{
		$dir = __DIR__.'/install/wizards/sotbit/b2bcabinet/site/templates/b2bcabinet';
		if(!is_dir($dir )) return;
		$files = readDirectory($dir);
		if($files)
		{
			foreach($files as $file)
			{
				$path = str_replace($dir, '',$file);
				CreateBakFile($_SERVER['DOCUMENT_ROOT'].TEMPLATE_PATH.$path);
			}
		}
	}
}


if (!function_exists("backupPublicFiles"))
{
	function backupPublicFiles($arrSites)
	{
		$dir = __DIR__.'/install/wizards/sotbit/b2bcabinet';
		$files = readDirectory($dir);
		if($files && $arrSites)
		{
			foreach($arrSites as $sid => $path_site)
			{
				foreach($files as $file)
				{
					$path = str_replace($dir.'/', '',$file);
					if(strpos($file,'/local/') !== false)
					{
						CreateBakFile($_SERVER['DOCUMENT_ROOT'].'/'.$path);
					}
					else
					{
						CreateBakFile($path_site['PATH'].$path);
					}

					CopyDirFiles(__DIR__."/install/wizards/".PARTNER_NAME."/".MODULE_NAME_SHORT."/site/public/ru/".$path,$path_site['PATH'].$path,true,true);
				}
			}
		}
	}
}





if (!function_exists("readDirectory"))
{
	function readDirectory($dir)
	{
		$handle = opendir($dir) or die("Can't open directory $dir");
		$files = Array();
		$subfiles = Array();
		while (false !== ($file = readdir($handle)))
		{
		  if ($file != "." && $file != "..")
		  {
			if(is_dir($dir."/".$file))
			{
			  $subfiles = readDirectory($dir."/".$file);
			  $files = array_merge($files,$subfiles);
			}
			else
			{
			  $files[] = $dir."/".$file;
			}
		  }
		}
		closedir($handle);
		return $files;
	}
}

if (!function_exists("ClearAllSitesCacheComponents")){
    function ClearAllSitesCacheComponents($arComponentsNames){
        if($arComponentsNames && is_array($arComponentsNames)){
            global $CACHE_MANAGER;
            $arSites = array();
            $rsSites = CSite::GetList($by = "sort", $order = "desc", array("ACTIVE" => "Y"));
            while($arSite = $rsSites->Fetch()){
              $arSites[] = $arSite;
            }
            foreach($arComponentsNames as $componentName){
                foreach($arSites as $arSite){
                    CBitrixComponent::clearComponentCache($componentName, $arSite["ID"]);
                }
            }
        }
    }
}


if (!function_exists("ClearAllSitesCacheDirs")){
    function ClearAllSitesCacheDirs($arDirs){
        if($arDirs && is_array($arDirs)){
            foreach($arDirs as $dir){
                $obCache = new CPHPCache();
                $obCache->CleanDir("", $dir);
            }
        }
    }
}

if (!function_exists("InitComposite")){
    function InitComposite($arSites){
        if(class_exists("CHTMLPagesCache")){
            if(method_exists("CHTMLPagesCache", "GetOptions")){
                if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
                    if($arHTMLCacheOptions["COMPOSITE"] !== "Y"){
                        $arDomains = array();
                        if($arSites){
                            foreach($arSites as $arSite){
                                if(strlen($serverName = trim($arSite["SERVER_NAME"], " \t\n\r"))){
                                    $arDomains[$serverName] = $serverName;
                                }
                                if(strlen($arSite["DOMAINS"])){
                                    foreach(explode("\n", $arSite["DOMAINS"]) as $domain){
                                        if(strlen($domain = trim($domain, " \t\n\r"))){
                                            $arDomains[$domain] = $domain;
                                        }
                                    }
                                }
                            }
                        }
                        
                        if(!$arDomains){
                            $arDomains[$_SERVER["SERVER_NAME"]] = $_SERVER["SERVER_NAME"];
                        }
                        
                        if(!$arHTMLCacheOptions["GROUPS"]){
                            $arHTMLCacheOptions["GROUPS"] = array();
                        }
                        $rsGroups = CGroup::GetList(($by="id"), ($order="asc"), array());
                        while($arGroup = $rsGroups->Fetch()){
                            if($arGroup["ID"] > 2){
                                if(in_array($arGroup["STRING_ID"], array("RATING_VOTE_AUTHORITY", "RATING_VOTE")) && !in_array($arGroup["ID"], $arHTMLCacheOptions["GROUPS"])){
                                    $arHTMLCacheOptions["GROUPS"][] = $arGroup["ID"];
                                }
                            }
                        }
                        
                        $arHTMLCacheOptions["COMPOSITE"] = "Y";
                        $arHTMLCacheOptions["DOMAINS"] = array_merge((array)$arHTMLCacheOptions["DOMAINS"], (array)$arDomains);
                        CHTMLPagesCache::SetEnabled(true);
                        CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
                        bx_accelerator_reset();
                    }
                }
            }
        }
    }
}

if (!function_exists("IsCompositeEnabled")){
    function IsCompositeEnabled(){
        if(class_exists("CHTMLPagesCache")){
            if(method_exists("CHTMLPagesCache", "GetOptions")){
                if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
                    if($arHTMLCacheOptions["COMPOSITE"] == "Y"){
                        return true;
                    }
                }
            }
        }
        return false;
    }
}

if (!function_exists("EnableComposite")){
    function EnableComposite(){
        if(class_exists("CHTMLPagesCache")){
            if(method_exists("CHTMLPagesCache", "GetOptions")){
                if($arHTMLCacheOptions = CHTMLPagesCache::GetOptions()){
                    $arHTMLCacheOptions["COMPOSITE"] = "Y";
                    CHTMLPagesCache::SetEnabled(true);
                    CHTMLPagesCache::SetOptions($arHTMLCacheOptions);
                    bx_accelerator_reset();
                }
            }
        }
    }
}

if (!function_exists("ReplaceMacros")){
    function ReplaceMacros($filePath, $arReplace, $skipSharp = false)
    {
        clearstatcache();

        if (!is_file($filePath) || !is_writable($filePath) || !is_array($arReplace))
            return;

        @chmod($filePath, BX_FILE_PERMISSIONS);

        if (!$handle = @fopen($filePath, "rb"))
            return;

        $content = @fread($handle, filesize($filePath));
        @fclose($handle);

        if (!($handle = @fopen($filePath, "wb")))
            return;

        if (flock($handle, LOCK_EX))
        {
            $arSearch = array();
            $arValue = array();

            foreach ($arReplace as $search => $replace)
            {
                if ($skipSharp)
                    $arSearch[] = $search;
                else
                    $arSearch[] = "#".$search."#";

                $arValue[] = $replace;
            }

            $content = str_replace($arSearch, $arValue, $content);
            @fwrite($handle, $content);
            @flock($handle, LOCK_UN);
        }
        @fclose($handle);
    }
}

if (!function_exists("ReplaceMacrosRecursive")){
    function ReplaceMacrosRecursive($filePath, $arReplace)
    {
        clearstatcache();

        if ((!is_dir($filePath) && !is_file($filePath)) || !is_array($arReplace))
            return;

        if ($handle = @opendir($filePath))
        {
            while (($file = readdir($handle)) !== false)
            {
                if ($file == "." || $file == ".." || (trim($filePath, "/") == trim($_SERVER["DOCUMENT_ROOT"], "/") && ($file == "bitrix" || $file == "upload")))
                    continue;
                    
                if (is_dir($filePath."/".$file))
                {
                    ReplaceMacrosRecursive($filePath.$file."/", $arReplace);
                }
                elseif (is_file($filePath."/".$file))
                {
                    if(GetFileExtension($file) <> "php")
                        continue;

                    if (!is_writable($filePath."/".$file))
                        continue;

                    @chmod($filePath."/".$file, BX_FILE_PERMISSIONS);

                    if (!$handleFile = @fopen($filePath."/".$file, "rb"))
                        continue;

                    $content = @fread($handleFile, filesize($filePath."/".$file));
                    @fclose($handleFile);

                    if (!($handleFile = @fopen($filePath."/".$file, "wb")))
                        continue;

                    if (flock($handleFile, LOCK_EX))
                    {
                        $arSearch = array();
                        $arValue = array();

                        foreach ($arReplace as $search => $replace)
                        {
                            $arSearch[] = "#".$search."#";
                            $arValue[] = $replace;
                        }

                        $content = str_replace($arSearch, $arValue, $content);
                        @fwrite($handleFile, $content);
                        @flock($handleFile, LOCK_UN);
                    }
                    @fclose($handleFile);

                }
            }
            @closedir($handle);
        }
    }
}


if (!function_exists("CopyDirFiles")){
    function CopyDirFiles($path_from, $path_to, $ReWrite = True, $Recursive = False, $bDeleteAfterCopy = False, $strExclude = ""){
        if (strpos($path_to."/", $path_from."/")===0 || realpath($path_to) === realpath($path_from))
            return false;

        if (is_dir($path_from)){
            CheckDirPath($path_to."/");
        }
        elseif(is_file($path_from)){
            $p = bxstrrpos($path_to, "/");
            $path_to_dir = substr($path_to, 0, $p);
            CheckDirPath($path_to_dir."/");

            if (file_exists($path_to) && !$ReWrite)
                return False;

            @copy($path_from, $path_to);
            if(is_file($path_to))
                @chmod($path_to, BX_FILE_PERMISSIONS);

            if ($bDeleteAfterCopy)
                @unlink($path_from);

            return True;
        }
        else{
            return True;
        }

        if ($handle = @opendir($path_from)){
            while (($file = readdir($handle)) !== false){
                if ($file == "." || $file == "..")
                    continue;

                if (strlen($strExclude)>0 && substr($file, 0, strlen($strExclude))==$strExclude)
                    continue;

                if (is_dir($path_from."/".$file) && $Recursive){
                    CopyDirFiles($path_from."/".$file, $path_to."/".$file, $ReWrite, $Recursive, $bDeleteAfterCopy, $strExclude);
                    if ($bDeleteAfterCopy)
                        @rmdir($path_from."/".$file);
                }
                elseif (is_file($path_from."/".$file)){
                    if (file_exists($path_to."/".$file) && !$ReWrite)
                        continue;

                    @copy($path_from."/".$file, $path_to."/".$file);
                    @chmod($path_to."/".$file, BX_FILE_PERMISSIONS);

                    if($bDeleteAfterCopy)
                        @unlink($path_from."/".$file);
                }
            }
            @closedir($handle);

            if ($bDeleteAfterCopy)
                @rmdir($path_from);

            return true;
        }

        return false;
    }
}

if (!function_exists("installModuleHands")){

    function installModuleHands($module,$modulesThear) {

        $obModule = CModule::CreateModuleObject($module);
        if(!is_object($obModule)) {
            return false;
        }

        if(!$obModule->IsInstalled()) {
            if(in_array($module,$modulesThear)){
                $obModule->InstallFiles();
                $obModule->InstallDB();
                $obModule->InstallEvents();

                if(!$obModule->IsInstalled()) {
                    RegisterModule($module);
                }
                return true;
            }
        }

    }
}


if (!function_exists("B2BCabinetImportIBlockFromXML")) {
    function B2BCabinetImportIBlockFromXML($xmlFile, $iblockCode, $iblockType, $siteID, $permissions = [])
    {
        if(!CModule::IncludeModule("iblock")) {
            return false;
        }

        $rsIBlock = CIBlock::GetList([], ["CODE" => $iblockCode, "TYPE" => $iblockType]);
        if($arIBlock = $rsIBlock->Fetch()) {
            return false;
        }

        if(!is_array($siteID)) {
            $siteID = [$siteID];
        }

        require_once($_SERVER["DOCUMENT_ROOT"]."/bitrix/modules/iblock/classes/".strtolower($GLOBALS["DB"]->type)
            ."/cml2.php");
        ImportXMLFile($xmlFile, $iblockType, $siteID, $section_action = "N", $element_action = "N");

        $iblockID = false;
        $rsIBlock = CIBlock::GetList([], ["CODE" => $iblockCode, "TYPE" => $iblockType]);
        if($arIBlock = $rsIBlock->Fetch()) {
            $iblockID = $arIBlock["ID"];

            if(empty($permissions)) {
                $permissions = [1 => "X", 2 => "R"];
            }

            CIBlock::SetPermission($iblockID, $permissions);
        }

        return $iblockID;
    }
}

if (!function_exists("B2BcheckIblockType")) {
    function B2BcheckIblockType($code) {
        $res = CIBlockType::GetList([], ['ID'=>$code])->Fetch();
        if(!empty($res)) {
            return true;
        }

        return false;
    }
}

if (!function_exists("B2BcheckIblockCode")) {
    function B2BcheckIblockCode($code) {
        $res = CIBlock::GetList([], ['CODE'=>$code])->Fetch();
        if(!empty($res)) {
            return true;
        }

        return false;
    }
}

if (!function_exists("B2BcheckIblockId")) {
    function B2BcheckIblockId($id) {
        $res = CIBlock::GetList([], ['ID'=>$id])->Fetch();
        if(!empty($res)) {
            return true;
        }

        return false;
    }
}
if (!function_exists("B2BrecursiveRemoveDir")) {
    function B2BrecursiveRemoveDir($dir)
    {
        $includes = glob($dir.'/{,.}*', GLOB_BRACE);
        $systemDots = preg_grep('/\.+$/', $includes);
        foreach($systemDots as $index => $dot) {
            unset($includes[$index]);
        }
        foreach($includes as $include) {
            if(is_dir($include) && !is_link($include)) {
                B2BrecursiveRemoveDir($include);
            } else {
                unlink($include);
            }
        }
        rmdir($dir);
    }
}
?>