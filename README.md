# ViteHelper plugin for CakePHP 4

## Installation

You can install this plugin into your CakePHP application using [composer](https://getcomposer.org).

The recommended way to install composer packages is:

```
composer require arbims/vite-plugin
```

Load the plugin in your Application.php:

```
bin/cake plugin load VitePlugin
```

Load the Helper in your AppView.php:

```
$this->loadHelper('VitePlugin.Vite');
```

add config Vite environment in /config/bootstrap.php

```
Configure::write('IS_DEV', true);
```

## Usage

run this command 

if you want use vue replace react with vue or preact

```
bin/cake vite_plugin react
```

In your php-layout, include this in your html head:

```
<?= $this->Vite->assets('js/main.jsx', \Cake\Core\Configure::read('IS_DEV'), 'react') ?>
```

if want to use vue

```
<?= $this->Vite->assets('js/main.js', \Cake\Core\Configure::read('IS_DEV')) ?>
```

then run 
```
npm install
npm install -D sass
npm run dev
```


wee nedd change IS_DEV variable false if we are in production.

```
Configure::write('IS_DEV', false);
```

then run 
```
npm run build
```



