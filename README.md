

## Overview
The goal of this project is to retrieve data from the jsonplaceholder.typicode.com API and store it in a SQLite database. The data is then displayed through an API. The API routes include:

- `/api/get-posts`: which will insert the first 50 posts from the API into the database and calculate a rating for each post based on the number of words in the title and body.
- `/api/users`: which will return the id, name, email and city of the user along with each of their posts, ordering the users by the average rating of their posts.
- `/api/posts/top`: which will return the best post of each user in the database based on the rating stored earlier (id, body, title, rating) along with the name and id of the user.
- `/api/posts/{id}`: which will return the information of the post passed by parameter (id, body, title) along with the username, or a 404 if it does not exist.

## Requirements
- PHP 7.3 or higher
-  PDO driver for sqlite3 
- Composer
- Laravel 8
- SQLite

## Installation

```sh
git clone https://github.com/tariik/backend-challenge.git
```
```sh
cd backend-challenge
```
```sh
php composer install
```
```sh
php artisan migrate
```
```sh
php artisan serve
```
The application should now be running on `http://localhost:8000`

Running the tests

```sh
php artisan test
```

Please note that you should use  `api/get-posts`  first to insert posts in the database before testing any other routes..

Note: The database file is located in `storage/database/database.sqlite`.
