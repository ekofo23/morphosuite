#!/bin/bash

# Activer le cache Laravel
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache

# Exécuter automatiquement les migrations sur la base PostgreSQL de Render
php artisan migrate --force

# Démarrer le serveur Web Apache
exec apache2-foreground