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
    $this->moveViteConfig();
    $this->createFolder();
    $this->createJsFile();
    $this->createCssFile();
    $io->info('Note: You should run "npm install && npm run dev" to compile your updated scaffolding.');

    return null;
  }

  public function moveViteConfig() {
    copy(dirname(dirname(__DIR__)).'/stubs/preact/'. self::VITE_FILE_CONFIG , ROOT. '/'. self::VITE_FILE_CONFIG);
    copy(dirname(dirname(__DIR__)).'/stubs/preact/package.json', ROOT. '/package.json');
  }

  public function createFolder() {
    $resources = ROOT . '/resources/';
    if (!file_exists($resources. 'css')) {
      mkdir($resources. 'css', 0777, true);
    }
    if (!file_exists($resources. 'css')) {
      mkdir($resources. 'js', 0777, true);
    }
    if (!file_exists($resources. 'css')) {
      mkdir($resources. 'scss', 0777, true);
    }
  }

  public function createJsFile() {
    $resources = ROOT . '/resources/js/';
    $mainjsx = fopen($resources ."main.jsx", "w");
    $txt = "import { render } from 'preact'\nimport { App } from './app'\nimport '../css/app.css'\nrender(<App />, document.getElementById('app'))";
    fwrite($mainjsx, $txt);
    fclose($mainjsx);
    $appjsx = fopen($resources . "app.jsx", "w");
    $txt = "export function App() {\n\nreturn (
  <>
    <div>
      Welcome Preact + Vite + cakephp
    </div>
  </>
  )
}";
    fwrite($appjsx, $txt);
    fclose($appjsx);
  }


  public function createCssFile() {
    $resources = ROOT . '/resources/css/';
    $appcss = fopen($resources . "app.css", "w");
    $txt = "body{color: blue; text-align:center;}";
    fwrite($appcss, $txt);
    fclose($appcss);
  }

}
