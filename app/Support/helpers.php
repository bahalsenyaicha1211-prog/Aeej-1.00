<?php

if (! function_exists('asset_v')) {
    /**
     * Comme asset(), mais ajoute "?v=<mtime du fichier>" à l'URL.
     *
     * public/.htaccess sert les .css/.js avec Cache-Control: immutable,
     * max-age=1 an : sans ce paramètre de version, le navigateur d'un
     * visiteur qui a déjà chargé la page une fois ne revérifiera jamais si
     * le fichier a changé, et continuera d'afficher l'ancienne version
     * (styles/scripts obsolètes) après chaque déploiement. Le "?v=" change
     * dès que le fichier change sur le serveur, ce qui force un fichier
     * "neuf" aux yeux du navigateur.
     */
    function asset_v(string $path): string
    {
        $full = public_path(ltrim($path, '/'));
        $v = is_file($full) ? filemtime($full) : time();

        return asset($path) . '?v=' . $v;
    }
}
