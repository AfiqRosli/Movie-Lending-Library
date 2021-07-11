# Movie Lending Library

A test project given by [TracknROLL](https://www.tracknroll.com/)

## Setting Up

### Setup Database
Go to `.env` file and setup your DB_DATABASE configuration

### Migrating Tables
```php
php artisan migrate
```

### Populating Tables
```php
php artisan db:seed
```

### Miscellaneous
Start the project by running:
```php
php artisan serve
```

Custom class aliase (Helper) is added in the project. In case the project can't compile, try running:
```php
composer dump-autoload
```

## Preview
![alt text][preview-1]
![alt text][preview-2]
![alt text][preview-3]
![alt text][preview-4]
![alt text][preview-5]

## Possible Improvements
 - Request validations
 - Reduce JavaScript code duplications
 - Movie post include images
 - CSS animations


[preview-1]: https://raw.githubusercontent.com/AfiqRosli/Movie-Lending-Library/main/github_images/lend-create_confirm-lend.PNG "lend.create page and confirm lending"
[preview-2]: https://raw.githubusercontent.com/AfiqRosli/Movie-Lending-Library/main/github_images/lend-index.PNG "lending.index page listing all the lending records"
[preview-3]: https://raw.githubusercontent.com/AfiqRosli/Movie-Lending-Library/main/github_images/lend-index_return-movie.PNG "lending.index page successfully returned movie"
[preview-4]: https://raw.githubusercontent.com/AfiqRosli/Movie-Lending-Library/main/github_images/movie-index_edit.PNG "movie.index page editing movie info"
[preview-5]: https://raw.githubusercontent.com/AfiqRosli/Movie-Lending-Library/main/github_images/movie-index_searching.PNG "movie.index page searching for a movie and not match found"
