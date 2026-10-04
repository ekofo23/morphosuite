#!/bin/bash

# Lien symbolique pour les images/fichiers
php artisan storage:link --force

# Découverte et caches Laravel
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migration de la base de données PostgreSQL
php artisan migrate --force

# Démarrage d'Apache
exec apache2-foreground