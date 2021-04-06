# bocasay-Symfony
Test PHP/Node/React pour Bocasay

## Before All
### `composer install`
Change the config database into .env on directory folder
DATABASE_URL="mysql://username:password@127.0.0.1:3306/bocasay_test"

### `yarn install`
yarn install to add encore and webpack SCSS

## Deploy/Install
### `symfony console doctrine:database:create`
### `symfony console doctrine:migrations:migrate`

## How to run
### `symfony serve`
Open [http://127.0.0.1:8000](http://127.0.0.1:8000)