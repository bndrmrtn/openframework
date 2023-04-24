<?php

namespace Core\Cache;

use Exception;
use Core\Base\Base;

class View extends Base {

    public static $store_dir = CACHE . '/views';
    private static $ez_tags = [ '{{', '}}', '*', '!', '--' ];
    public static $views_dir = ROOT . '/views';
    private static $view__autorender_file = '.view.{ext}';
    private static array $custom_replace = [];
    
    public static function boot():void {
        $config = require config('view');
        if(isset($config['ez-tags'])) self::$ez_tags = $config['ez-tags'];
        if(isset($config['view-folder'])) self::$views_dir = $config['view-folder'];
        if(isset($config['replace-tags-to'])) self::$custom_replace = $config['replace-tags-to'];
        if(isset($config['view-render-file-ext'])) self::$view__autorender_file = $config['view-render-file-ext'];
    }

    public static function filedata($file){
        $renderFile = false;
        $v_p = self::$views_dir;
        $atp = '';
        
        if(str_starts_with($file,'.src/:')) {
            $file = str_replace('.src/:', '', $file);
            $v_p = core('template/views');
            $data = require core('applock.token.php');
            $atp = startStrSlash($data['framework_builtin_views_directory']);
        }
        $file_ext = 'php';
        if(str_contains($file, '.')) {
            $ext = explode('.', $file);
            $file_ext = $ext[array_key_last($ext)];
            unset($ext[array_key_last($ext)]);
            $file = implode('.', $ext);
        }
        $varf = str_replace('{ext}', $file_ext, self::$view__autorender_file);
        if(file_exists($v_p . startStrSlash($file) . $varf)) {
            $view_file = $v_p . startStrSlash($file) . $varf;
            $cached_file = self::$store_dir . $atp . startStrSlash($file) . $varf;
            $renderFile = true;
            
        } else {
            $view_file = $v_p . startStrSlash($file) . '.' . $file_ext;
            $cached_file = self::$store_dir . $atp . startStrSlash($file) . '.' . $file_ext;
        }
        if($file_ext !== 'php' && $renderFile) {
            $cached_file .= '.php';
        }
        return [
            'renderFile' => $renderFile,
            'view_file' => $view_file,
            'cached_file' => $cached_file,
            'file_ext' => $file_ext,
        ];
    }

    public static function include(string $file){

        $genTime = microtime(true);
        $filedata = self::filedata($file);

        $renderFile = $filedata['renderFile'];
        $view_file = $filedata['view_file'];
        $cached_file = $filedata['cached_file'];
        $file_ext = $filedata['file_ext'];

        if(!file_exists($cached_file) || (_env('APP_DEV',false) && _env('RERE_VIEWS',false))){

            $view = $view_file;
            if(!file_exists($view)){
                $ex = new \Exception();
                $trace = $ex->getTrace();
                $final_call = $trace[1];
                if(str_starts_with($view_file, self::$views_dir)) $view_file_path = str_replace(self::$views_dir, '{VIEWS_DIR}', $view_file);
                if(str_starts_with($view_file, core('template/views'))) $view_file_path = str_replace(core('template/views'), '{TEMPLATES_DIR}', $view_file);

                throw new Exception('Trying to import a non-existing file (' . $view_file_path . ')');
            }

            $view_data = file_get_contents($view);

            if($renderFile){
                while(str_starts_with($view_data, '@extends:')) {
                    $view_data = ViewBuilder::extended($view_data, $file, $view);
                }

                $view_data = self::auto_tags($view_data);
    
                $view_data = self::inline_operators($view_data);
    
                $view_data = self::inline_operations($view_data);
    
                $view_data = str_replace('#@','@',$view_data);
            }
            createPath(dirname($cached_file));
            //$view_data = str_replace("\n", "", $view_data);
            //$view_data = str_replace("  ", " ", $view_data);
            
            if($renderFile) $view_data .= "<?php\n/*\nGenerated at: " . date('Y-m-d H:i:s') .  "\nFile Hash: " . hash('sha256',$view_data . microtime(true)) . "\nRender Time: " . microtime(true) - $genTime . "s\n*/\n?>";
    
            file_put_contents($cached_file,$view_data);
        }

        return $cached_file;
    }


    // not working properly
    private static function auto_tags($data){
        if(!empty(self::$custom_replace)){
            foreach(self::$custom_replace as $r => $rto){
                if(str_contains($data,$r)){
                    $np_poz = strpos($data, $r)-1;
                    if(substr($data, $np_poz, 1) != '#'){
                        $data = str_replace_first($r,'<?php ' . $rto . ' ?>',$data);
                    } else {
                        $data = rem_inx($data,$np_poz);
                    }
                }
            }
        }
        return $data;
    }

    private static function inline_operations($view_data){
        $arr = self::parser($view_data,'@','):',['end','import']);

        while(!empty($arr)){
            foreach($arr as $k => $data){
                $search = '@' . $data . '):';
                $view_data = str_replace($search, '<?php ' . $data . '): ?>', $view_data);
                $endtag = 'end' . explode('(',$data)[0];
                $view_data = str_replace('@' . $endtag,'<?php ' . $endtag . ' ?>',$view_data);
            }
            $arr = self::parser($view_data,'@','):',['end','import']);
        }
        return $view_data;
    }

    public static function parser($data, $start_tag, $end_tag,?array $nsw = NULL){
        $arr = [];
        while(1){
            $parsed = string_between($data, $start_tag, $end_tag);
            if(!$parsed)
                break;
            $np_poz = strpos($data, $start_tag)-1;
            $strposition = strpos($data, $end_tag);
            if(substr($data, $np_poz, 1) != '#'){
                if(!is_null($nsw)){
                    if(!self::str_starts_with_array($parsed,$nsw)){
                        array_push($arr,$parsed);
                    }
                } else {
                    array_push($arr,$parsed);
                }
            } else {
                $data = rem_inx($data,$np_poz);
            }
            $nextString = substr($data, $strposition+1, strlen($data));
            $data = $nextString;
        }
        return $arr;
    }

    private static function str_starts_with_array($str,$needle){
        $sw = false;
        foreach($needle as $n){
            if(str_starts_with($str,$n)) $sw = true;
        }
        return $sw;
    }

    private static function inline_operators($view_data){
        $data = $view_data;
        $arr = self::parser($data, self::$ez_tags[0], self::$ez_tags[1]);
        foreach($arr as $data){
            if(str_starts_with($data, self::$ez_tags[2])){
                $replace_to = substr($data, strlen(self::$ez_tags[2]));
            } else if(str_starts_with($data,self::$ez_tags[3])){
                $replace_to = ' echo htmlspecialchars(' . substr($data, strlen(self::$ez_tags[3])) . ')';
            } else if(str_starts_with($data,self::$ez_tags[4])){
                $replace_to = ' /*' . substr($data, strlen(self::$ez_tags[4])) . '*/';
            } else {
                $replace_to = ' echo ' . $data;
            }

            $replace_to = "<?php {$replace_to} ?>";
            
            $view_data = str_replace(self::$ez_tags[0] . $data . self::$ez_tags[1],$replace_to,$view_data);
        }
        return $view_data;
    }

}