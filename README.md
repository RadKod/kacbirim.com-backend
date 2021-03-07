# Kaç Birim

Install
-
* .env.example copy -> .env
* ``php artisan key:generate``
* ``php artisan storage:link``
* ``php artisan migrate``
* ``php artisan db:seed``
* ``composer update``
* ``npm install``
* ``php artisan serve``

login, -u admin -p admin

http://127.0.0.1:8000/

API Docs
-
endpoints;

method | endpoint | detail
--- | --- | ---
`get` | `api/v1/posts` | Retrieve all posts.
`get` | `api/v1/posts/{id_or_slug}` | Retrieve post detail.
`get` | `api/v1/countries` | Retrieve all countries.

`api/v1/posts` parameters:

```
?greater=field,value
?greater_or_equal=field,value
?less=field,value
?less_or_equal=field,value
?between=field,value1,value2
?not_between=field,value1,value2
?sort=field
?sort=field,sort_type
?sort[0]=field1&sort[1]=field2
?in=field,value1,value2
?tags_in=field,value1,value2
?countries_in=field,value1,value2
?like=field,value1
?tags_like=field,value1
?countries_like=field,value1
?field=value
```
`api/v1/countries` parameters:

```
?sort=field
?sort=field,sort_type
?sort[0]=field1&sort[1]=field2
?like=field,value1
?field=value
```
