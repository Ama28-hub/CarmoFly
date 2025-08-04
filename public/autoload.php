<?php
ini_set('display_errors',1);
error_reporting(E_ALL);

spl_autoload_register(function (string $class) {
    $prefix   = 'App\\';                   // préfixe de tous tes namespaces
    $baseDir  = __DIR__ . '/../src/';     // dossier qui contient controller/, model/, config/, etc.

    // Si la classe n'utilise pas le préfixe "App\", on sort
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }

    // Récupère la partie après "App\"
    $relativeClass = substr($class, $len);

    // Remplace les "\" par "/" et ajoute .php
    $file = $baseDir . str_replace('\\', '/', $relativeClass) . '.php';

    if (file_exists($file)) {
        require $file;
    }
});
?>