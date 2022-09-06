<?php

namespace VitePlugin\View\Helper;

use Cake\Cache\Cache;
use Cake\View\Helper;

class ViteHelper extends Helper {

    public $manifest = '';
    public $url;

    private $manifestData = null;
    
    public function assets($url, $isDev) {
        $base = 'http://localhost:3000/assets';
        if($isDev) {
            return <<<HTML
            <script src="{$base}/{$url}" type="module" defer></script>
            HTML;
        } else {
            return $this->assetProd($url);
        }
    }

    public function assetProd($url) {
        $this->manifest = ROOT .'/webroot/assets/manifest.json';
        $cachemanifest = Cache::read('vite_manifest','default');
        if ($cachemanifest === null ){
            
            $cachemanifest = json_decode(file_get_contents($this->manifest), true);
            Cache::write('vite_manifest',$cachemanifest,'default');
        }
        $this->manifestData = $cachemanifest;
        $file = $this->manifestData [$url]['file'] ?? null;
        $cssFiles = $this->manifestData [$url]['css'] ?? [];
        if ($file === null) {
            return '';
        }
        $html = <<<HTML
            <script src="/assets/{$file}" type="module" defer></script>
            HTML;
        foreach($cssFiles as $css) {
            $html .=  <<<HTML
            <link rel="stylesheet" href="/assets/{$css}" />
            HTML;
        }
        return $html;
    }
}