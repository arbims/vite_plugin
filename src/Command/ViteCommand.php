<?php

declare(strict_types=1);

namespace VitePlugin\Command;

use VitePlugin\StubsPathTrait;
use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;


class ViteCommand extends Command
{

  /**
   * Filesystem utility object
   *
   * @var \AssetMix\Utility\FileUtility
   */
  private $filesystem;

  /**
   * Preset type provided via argument.
   *
   * @var string|null
   */
  private $preset;

  /**
   * Directory name where all assets(js, css) files will reside.
   */
  public const ASSETS_DIR_NAME = 'resources';

  public const VITE_FILE_CONFIG = 'vite.config.js';
  public const VITE_PACKAGE = 'package.json';


  /**
   * @inheritDoc
   */
  protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
  {
    $parser = parent::buildOptionParser($parser);

    return $parser;
  }

  /**
   * @inheritDoc
   */
  public function execute(Arguments $args, ConsoleIo $io)
  {
    $this->createFolder();
    if ($args->getArguments()[0] === 'vue') {
      $this->moveViteConfig('vue');
      $this->createJsFile('vue');
    } elseif($args->getArguments()[0] === 'react') {
      $this->moveViteConfig('react');
      $this->createJsFile('react');
    }elseif ($args->getArguments()[0] === 'preact') {
      $this->moveViteConfig('preact');
      $this->createJsFile('preact');
      
    }
    $this->createCssFile();
    $io->info('Note: You should run "npm install && npm run dev" to compile your updated scaffolding.');

    return null;
  }

  public function moveViteConfig($sub_folder) {
    copy(dirname(dirname(__DIR__))."/stubs/$sub_folder/". self::VITE_FILE_CONFIG , ROOT. '/'. self::VITE_FILE_CONFIG);
    copy(dirname(dirname(__DIR__))."/stubs/$sub_folder/". self::VITE_PACKAGE , ROOT. '/'. self::VITE_PACKAGE );
  }

  public function createFolder() {
    $resources = ROOT . '/resources/';
    if (!file_exists($resources. 'css')) {
      mkdir($resources. 'css', 0777, true);
    }
    if (!file_exists($resources. 'js')) {
      mkdir($resources. 'js', 0777, true);
    }
    if (!file_exists($resources. 'scss')) {
      mkdir($resources. 'scss', 0777, true);
    }
  }

  public function createJsFile($vite_type) {
    $resources = ROOT . '/resources/js/';
    if ($vite_type === 'preact') {
      $mainjsx = fopen($resources ."main.jsx", "w");
      $txt = "import { render } from 'preact'\nimport { App } from './app'\nimport '../css/app.css'\nrender(<App />, document.getElementById('app'))";
      fwrite($mainjsx, $txt);
      fclose($mainjsx);
      $appjsx = fopen($resources . "app.jsx", "w");
      $txt = "export function App() {\n\nreturn (\n<>\n<div>\nWelcome Preact + Vite + cakephp\n</div>\n</>\n)\n}";
      fwrite($appjsx, $txt);
      fclose($appjsx);  
    } elseif($vite_type === 'react') {
        $mainjsx = fopen($resources ."main.jsx", "w");
        $txt = "import { render } from 'react'\nimport { App } from './app'\nimport '../css/app.css'\nrender(<App />, document.getElementById('app'))";
        fwrite($mainjsx, $txt);
        fclose($mainjsx);
        $appjsx = fopen($resources . "app.jsx", "w");
        $txt = "export function App() {\n\nreturn (\n<>\n<div>\nWelcome React + Vite + cakephp\n</div>\n</>\n)\n}";
        fwrite($appjsx, $txt);
        fclose($appjsx);  
    }elseif ($vite_type === 'vue') {
      $mainjs = fopen($resources ."main.js", "w");
      $txt = "import { createApp } from 'vue'\nimport App from './App.vue'\nimport '../css/app.css'\ncreateApp(App).mount('#app')";
      fwrite($mainjs, $txt);
      fclose($mainjs);
       $appvue = fopen($resources . "app.vue", "w");
      $txt = "<script setup>\nexport default {\n\ndata () {\nreturn {\n app_type: 'Welcome Vue + Vite + cakephp'\n}\n}\n}\n</script>\n\n<template>{{ app_type }}</template>";
      fwrite($appvue, $txt);
      fclose($appvue);
    } 
  }

  public function createCssFile() {
    $resources = ROOT . '/resources/css/';
    $appcss = fopen($resources . "app.css", "w");
    $txt = "body{color: blue; text-align:center;}";
    fwrite($appcss, $txt);
    fclose($appcss);
  }

}
