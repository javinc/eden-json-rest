# Eden-Json-Rest
is a RESTful JSON API built using [Openovate's Eden Framework V3](https://github.com/Openovate/Framework)

### requirements
- PHP 5.6+
- Apache 2
- composer

### setup
- point your VirtualHost to `/repo/Api/public`
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
	Resource -> Service -> Endpoint
	|			|			|
	|			|			- RESTful
	|			- business logic
	- database obejects