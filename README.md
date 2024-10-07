# POS2

## Instalasi
1. Clone Repo
2. ```composer install```
3. ```npm install```
4. Sesuaikan file `.env` (Sambungkan database)
5. ```php artisan migrate:fresh --seed```
6. ```php artisan key:generate```
7. ```php artisan jwt:secret```
8. ```php artisan storage:link```

## Menjalankan
1. Pastikan database menyala
2. ```php artisan serve```
3. ```npm run dev```