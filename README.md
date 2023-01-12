

## Description 

This application is a test that consists of retrieving data from the following API to obtain a list of posts, but only the first 50 results. 
The API used is "https://jsonplaceholder.typicode.com/posts". 
The posts, along with the rating of each one, based on the number of words each post contains, will be inserted into the database.

Each word found in the title counts as two.
Each word found in the body counts as one.
The ID of the post and the ID of the user, which come from the API, will be respected.
In case of a duplicate post in the database, the "body" field of the database will be updated only.
Subsequently, the users who have written a post in these first 50 posts (excluding the rest) will be registered in the database, retrieving their data through this API "https://jsonplaceholder.typicode.com/users". 

The fields that we want to save of the users are: id, name, email and city.
In case a user already exists in the database, no update will be made on that user.

Once the data is stored, an API will be created from the data in the database, returning a JSON in each of the following API routes:

users: it will return the id, name, email and city of the user, along with each of their posts with their information (id, user_id, body, title), ordering the users by the average rating of posts of each user.
posts/top: it will return the best post of each user in the database based on the score we have previously stored (id, body, title, rating), along with the name and id of the user
posts/{id}: it will return the information of the post that is passed as a parameter (id, body, title) along with the user's name, or a 404 if it does not exist.

## Instruction 

