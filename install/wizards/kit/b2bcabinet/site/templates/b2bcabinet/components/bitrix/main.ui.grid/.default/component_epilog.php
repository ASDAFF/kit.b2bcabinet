<?
$TMP  = $this->__template;
$scriptFolder = '/js/';

$absPath = $_SERVER["DOCUMENT_ROOT"] . $this->__template->__folder . $scriptFolder;
$relPath = $TMP->__folder . $scriptFolder;

if (is_dir($absPath)) {
    $dir = opendir($absPath);
    if ($dir) {
        while (($file = readdir($dir)) !== false) {
            $ext = getFileExtension($file);

            if ($ext === 'js' && !(mb_strpos($file, 'map.js') !== false || mb_strpos($file,
                        'min.js') !== false)) {
                $this->__template->addExternalJs($relPath . $file);
            }
        }
        closedir($dir);
    }
}
?>