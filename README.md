#TicTacToe game for a coding exercise
Game is supposed to be played by two users in a Slack channel

Requirements
 - PHP 7.0.8+
 - MySQL 5.5.52+

Dependencies
 - Composer (checked in the source code)
 - Slim Framework (installed via composer)
 - Monolog (installed via composer)

Installation guide
 - Clone the git repo https://github.com/roloum/TicTacToe.git
 - Create MySQL database and user using configuration in conf/Settings.php.production (or create your own configuration file)
 - Create symbolic link for Settings.php (ln -s conf/Settings.php.production conf/Settings.php)
 - Load database schema: mysql -uXXX -pXXX < conf/setup/db_schema.sql
 - cd into src/ and run "php composer.phar install" to install the Dependencies (Although, this step might not be necessary because the vendor folder is checked in the repo)
 - Run test cases: "phpunit --bootstrap src/vendor/autoload.php test/TicTacToe/CLI.php"

