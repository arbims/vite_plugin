<?php

declare(strict_types=1);

namespace VitePlugin\Command;

use Cake\Command\Command;
use Cake\Console\Arguments;
use Cake\Console\ConsoleIo;
use Cake\Console\ConsoleOptionParser;

class ViteCommand extends Command
{
    public const ASSETS_DIR = 'resources';
    public const VITE_FILE_CONFIG = 'vite.config.js';
    public const VITE_PACKAGE = 'package.json';

    /**
     * @inheritDoc
     */
    protected function buildOptionParser(ConsoleOptionParser $parser): ConsoleOptionParser
    {
        $parser = parent::buildOptionParser($parser);
        $parser->setDescription('Installe et configure Vite.js avec Vue, React ou Preact pour CakePHP.');
        $parser->addArgument('framework', [
            'help' => 'Le framework JS à installer (vue, react ou preact)',
            'required' => true,
        ]);

        return $parser;
    }

    /**
     * @inheritDoc
     */
    public function execute(Arguments $args, ConsoleIo $io)
    {
        $type = strtolower($args->getArgument('framework'));

        if (!in_array($type, ['vue', 'react', 'preact'])) {
            $io->error('Veuillez spécifier un framework valide : vue, react ou preact.');
            return Command::CODE_ERROR;
        }

        $io->out("📁 Création des dossiers /" . self::ASSETS_DIR . "...");
        $this->createFolders();

        $io->out("⚙️  Copie de la configuration Vite pour {$type}...");
        $this->copyViteConfig($type);

        $io->out("🧩 Création des fichiers JS et CSS de base...");
        $this->createJsFiles($type);
        $this->createCssFile();

        $io->success("✅ Vite.js a été configuré avec succès pour {$type} !");
        $io->info('💡 Exécutez maintenant : npm install && npm run dev');

        return Command::CODE_SUCCESS;
    }

    private function createFolders(): void
    {
        $resources = ROOT . '/' . self::ASSETS_DIR . '/';
        foreach (['css', 'js', 'scss'] as $dir) {
            $path = $resources . $dir;
            if (!file_exists($path)) {
                mkdir($path, 0777, true);
            }
        }
    }

    private function copyViteConfig(string $framework): void
    {
        $stubDir = dirname(dirname(__DIR__)) . "/stubs/{$framework}/";
        $root = ROOT . '/';

        copy($stubDir . self::VITE_FILE_CONFIG, $root . self::VITE_FILE_CONFIG);
        copy($stubDir . self::VITE_PACKAGE, $root . self::VITE_PACKAGE);
    }

    private function createJsFiles(string $framework): void
    {
        $resources = ROOT . '/' . self::ASSETS_DIR . '/js/';

        switch ($framework) {
            case 'preact':
                file_put_contents($resources . "main.jsx", <<<JS
import { render } from 'preact'
import { App } from './app'
import '../css/app.css'

render(<App />, document.getElementById('app'))
JS);
                file_put_contents($resources . "app.jsx", <<<JS
export function App() {
  return (
    <>
      <div>Welcome Preact + Vite + CakePHP</div>
    </>
  )
}
JS);
                break;

            case 'react':
                file_put_contents($resources . "main.jsx", <<<JS
import { createRoot } from 'react-dom/client'
import { App } from './app'
import '../css/app.css'

createRoot(document.getElementById('app')).render(<App />)
JS);
                file_put_contents($resources . "app.jsx", <<<JS
export function App() {
  return (
    <>
      <div>Welcome React + Vite + CakePHP</div>
    </>
  )
}
JS);
                break;

            case 'vue':
                file_put_contents($resources . "main.js", <<<JS
import { createApp } from 'vue'
import App from './App.vue'
import '../css/app.css'

createApp(App).mount('#app')
JS);
                file_put_contents($resources . "App.vue", <<<VUE
<template>
  <div>{{ app_type }}</div>
</template>

<script setup>
export default {
  data() {
    return {
      app_type: 'Welcome Vue + Vite + CakePHP'
    }
  }
}
</script>
VUE);
                break;
        }
    }

    private function createCssFile(): void
    {
        $resources = ROOT . '/' . self::ASSETS_DIR . '/css/';
        file_put_contents($resources . "app.css", "body { color: blue; text-align: center; }");
    }
}
