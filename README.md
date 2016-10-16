# Eden-Json-Rest
is a RESTful JSON API built using [Eden V4](https://github.com/eden-php)

### requirements
- PHP 5.6+
- Apache 2
- composer

### setup
- point your VirtualHost to `/public`
- run `composer install`
- run `mkdir upload && chmod 777 upload` for files

### features
- JWT authentication
- Lazy CRUD
- RESTful Module
- File Upload
- Image Render
- CSV Tool

### structure

	---------- Modules ------
	|			|			|
	Resource -> Service -> Controller
	|			|			|
	|			|			- presentation
	|			- business logic
	- database objects
