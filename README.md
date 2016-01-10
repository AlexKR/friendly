# Simple friendship service written using [Slim](#https://github.com/edhaase/slim-skeleton), PHP and Neo4j

## Installation

Via composer:

`composer create-project alexkr/friendly [destination]`.

Via git:
* Clone this repo
* Run `composer install`
* Run `composer bootstrap` or `composer run-script post-create-project-cmd`

Copy source code:
* Fill `config.php`
* Run `php initDatabase.php`
* Start server with `composer serve`
* Enjoy!

## Endpoints
* Create user - `POST: /users`
* Get friends list - `GET: /users/{%user_id%}/friends`
* Get friends of friends list - `GET: /users/{%user_id%}/friendsOfFriends`
* Get friends of friends with n-depth list - `GET: /users/{%user_id%}/friendsOfFriends/{%depth%}`
* Get friendship requests - `GET: /users/{%user_id%}/friendshipRequests`
* Send friendship requests - `POST: /users/{%user_id%}/friendshipRequest` with POST-parameter `fromUserId`
* Accept friendship requests - `POST: /users/{%user_id%}/friendshipRequest` with POST-parameter `fromUserId`
* Decline friendship requests - `POST: /users/{%user_id%}/friendshipRequest` with POST-parameter `fromUserId`

## Structure
```
logs/   - Log output
public/ - Site configuration entry point limits what we expose
tests/  - Codeception tests
src/
    Controller/ - Route controllers
    Model/      - Data models
    Service/    - Service providers
    app.php     - Primary application
    routes.php  - Route creation 
vendor/ - Composer install directory
config.example.php  - Example application config constants
```

## Tests
Change url in `tests/api.suite.yml`

Run:

    composer test


## Scripts
Some scripts through composer for easier life.

```
composer
        codecept  - shortcut to codecept
        test      - alias for codecept run
        cs        - alias for "phpcs --standard=PSR2 src/",
        cbf       - alias for "phpcbf --standard=PSR2 src/",
        serve     - starts test server
        bootstrap - alias to @post-create-project-cmd
```