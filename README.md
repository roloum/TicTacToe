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
 - cd into src/ and run "php composer.phar install" to install the Dependencies
 - Run test cases: "phpunit --bootstrap src/vendor/autoload.php test/TicTacToe/CLI.php"

Limitations
 - Due to time constraints, this program does not validate if the user exists within a channel.
 - If you challenge a user that does not exist in the channel, the channel will be locked until an administrator removes it from the database

Features that were considered but are yet to be implemented due to time constraints:
 - Logging: Logging all queries in DEBUG mode
 - Update winner_player_id in Game table when there is a winner
 - Save Slack (and any other client tokens) in the database
 - Replace Models with ORM

Potential features considered in the design (Features were not implemented due to time constraints)
 - Game challenge could be accepted or rejected
 - Players could choose the symbol they want to play with
 - Other type of board games, for example Connect 4
