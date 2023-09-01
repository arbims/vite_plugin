<?php

namespace VitePlugin\View\Helper;

use Cake\Cache\Cache;
use Cake\View\Helper;

class ViteHelper extends Helper {

    public string $manifest = '';
    public string $url = '';

    private $manifestData = null;

    public function assets(string $url,string $isDev, $lib = null) {
        $base = 'http://localhost:3000/assets';
        $html = '';
        if($isDev) {
            if ($lib === 'react') {
                $html .= <<<HTML
                <script type="module" src="{$base}/@vite/client"></script>
                HTML;
                    $html .= '<script type="module">
                    import RefreshRuntime from "'.$base.'/@react-refresh"
                    RefreshRuntime.injectIntoGlobalHook(window)
                    window.$RefreshReg$ = () => {}
                    window.$RefreshSig$ = () => (type) => type
                    window.__vite_plugin_react_preamble_installed__ = true
                </script>';    
            }
            
            $html .= <<<HTML
            <script src="{$base}/{$url}" type="module" defer></script>
            HTML;
            return $html;
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