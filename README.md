# bocasay-Symfony
Test PHP/Node/React pour Bocasay

## Before All
### `composer install`
Change the config database into .env on directory folder
DATABASE_URL="mysql://username:password@127.0.0.1:3306/bocasay_test"


## Create First user CLI
### `bin/console security:encode-password`
and get the Encoded password 

### `yarn install`
yarn install to add encore and webpack SCSS

## Deploy/Install
Generate password encrypt with this command
### `symfony console doctrine:database:create`
Then insert into database the new user
### INSERT INTO user (email, roles,first_name,last_name, password) VALUES ('admin@admin.com', '["ROLE_ADMIN"]','John','Doe' 'PASSWORD_ENCRYPTED_HERE');

## How to run
### `symfony serve`
Open [http://127.0.0.1:8000](http://127.0.0.1:8000)