
#!/bin/bash

# Lien symbolique pour les images
php artisan storage:link --force

# Caches Laravel
php artisan package:discover --ansi
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migration ET injection des données initiales (Seeders)
php artisan migrate:fresh --seed --force

# Démarrage Apache
exec apache2-foreground