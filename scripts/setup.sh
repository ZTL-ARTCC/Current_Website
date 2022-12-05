#!/bin/bash

cp ./.git-hooks/pre-commit ./.git/hooks/.

composer install
npm ci
cp .env.example .env
php artisan key:generate

./scripts/start-db.sh

echo ""
echo "⏲️  Waiting for DB to start..."
echo ""
sleep 10

php artisan migrate
php artisan db:seed

