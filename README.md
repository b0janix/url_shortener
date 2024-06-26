## Technical details

I wrote a small web app, and this is the back-end part of the web application. I've used PHP 8.2, Laravel 11, MySQL 8.0.1, Docker and PHP Unit 9.5.0 as core technologies. 
In order for you to start the project, If you are not on Ubuntu OS and you don't have all these technologies installed on your machine,
you will have to have Docker and docker-compose installed. To start the application with docker, please run the following commands

```
docker-compose build
```

after the build is done and everything is OK run

```docker-compose up```

In order to run the tests, you will have to ssh into the app container or the app service and run

```
php artisan test or ./vendor/bin/phpunit
```

The back-end is built as an api. I have 3 routes registered in `routes/api.php`. All the routes resolve to a specific method
from the `UrlController`. The `index` method returns the initial data if there is any, and it returns paginated results or only the latest
5 urls that are being added. I've created a resource collection UrlCollection for the response of this method.

Then I have the `store` method which basically first checks whether the url is safe for browsing using google's safe browsing api.
In order for this to work you will have to set the `GOOGLE_SB_API_KEY` variable in your .env file. You can use mine from the 
.env.example. Unfortunately this part of the code is not working properly or the sb api is not returning the right responses. As I was trying 
to send some urls with malware and malicious content but the api still is not finding any match and is returning empty results
as per their documentation https://developers.google.com/safe-browsing/v4/lookup-api#http-post-response

```
Note: If there are no matches (that is, if none of the URLs specified in the request are found on any of the lists specified in a request), the HTTP POST response simply returns an empty object in the response body.
```

then I check if the url already is saved, 
(because there can be many urls this whole logic can be dispatched as a job in a queue, but I thought that would be an overkill for this task)
If it is I will return some kind of error response. Then if everything is fine I go on save the record and I generate the hash from the db id of the record. 
For this part I use Bijection or https://en.wikipedia.org/wiki/Bijection, so I wrote two methods in a separate service class `URLModifyingService`,
one that turns the id to hash and the other one is doing the opposite work. After the hash is created I'm checking whether is longer
than 6 characters even though that will never happen because the db needs to generate some huge id which is not supported by my id data type
which is INTEGER. And if everything is ok I'm updating that record with the freshly created hash.

And the third and the last method is the `redirectToOriginal` method which basically takes the hash as an arg and redirects from
the short url to the long url.

I also wrote unit and feature tests, you can check them out as well.
