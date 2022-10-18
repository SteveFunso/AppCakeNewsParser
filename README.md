<h1 align="center">
  <br>
  <br>
  AppCakeNews
  <br>
</h1>

<h4 align="center">A news parsing app.</h4>

## Key Features

- Parsing service from a news resource
- A page displaying the list of downloaded news
- A CLI command to start parsing
- Parsing features:
  - title
  - short description
  - picture
  - date added
  - checks the presence of the title in the database
  - makes note of last update if the news is already in the database,
  - database queries are optimized for heavy load
- Page for viewing news from the database should be available only after authorization in the system (registration is not required)
- Authorized users can be with one of two roles: admin or moderator (the administrator can delete articles)
- there must be pagination at the end of the list of articles

- Stack:
  - Symfony 5.4
  - Php 7.4
  - Mysql
  - Bootstrap 5.1
  - Docker (docker-compose)
  - RabbitMQ

## How To Use

```bash
# Clone this repository
$ git clone https://github.com/SteveFunso/AppCakeNewsParser.git

# Go into the repository
$ cd AppCakeNewsParser

# Install dependencies
$ php bin/console migrate

# Migrate the db
$ php bin/console migrate

# To seed user data
$ php bin/console user:seed

# To import posts
$ php bin/console post:import

# To start server
$ symfony server:start -d

```

> **Note**
> navigate to the site via the url generated in the console from the start command.

## Credentials

Admin:
Email: admin@example.com
Password: pass123

Moderator:
Email: moderator@example.com
Password: pass123

## License

MIT
