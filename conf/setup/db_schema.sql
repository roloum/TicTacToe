-- @author: Rolando Umana<rolando.umana@gmail.com>
-- MySQL database schema for the TicTacToe game

DELIMITER @@

CREATE TABLE IF NOT EXISTS Player (
	player_id	BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Auto-generated player id',
	user_name	VARCHAR(25) COMMENT 'Slack user name. The reason for saving the username and not the id is because we do not get the opponent user_id in the request',
	creation_date	DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Row creation datetime',
	UNIQUE INDEX Idx_Player_user_name (user_name) COMMENT 'Index used when querying by user_name'
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table that stores players'@@

CREATE TABLE IF NOT EXISTS Game (
	game_id	BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Auto-generated game id',
	channel_id	VARCHAR(100) COMMENT 'Slack channel ID',
	status	ENUM('pending','declined','active','draw','win') DEFAULT 'pending' COMMENT 'Different stages of the game',
	next_player_id BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Next turn corresponds to player_id.',
	winner_player_id BIGINT UNSIGNED NULL DEFAULT NULL COMMENT 'Player that wins the game.',
	creation_date	DATETIME DEFAULT CURRENT_TIMESTAMP COMMENT 'Row creation datetime',
	last_modified	TIMESTAMP COMMENT 'Last time the row was modified',
	INDEX Idx_Game_channel_id_status (channel_id, status) COMMENT 'Index that allows to query game by channel and status',
	CONSTRAINT FK_Game_next_player_id FOREIGN KEY (next_player_id) REFERENCES Player (player_id),
	CONSTRAINT FK_Game_winner_player_id FOREIGN KEY (winner_player_id) REFERENCES Player (player_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Table that stores games'@@

CREATE TABLE IF NOT EXISTS Player_Game (
	player_id	BIGINT UNSIGNED NOT NULL COMMENT 'Player id',
	game_id	BIGINT UNSIGNED NOT NULL COMMENT 'Game id',
	role ENUM ('challenger', 'opponent') COMMENT 'Player role in the game',
	symbol ENUM ('X', 'Y') COMMENT 'Player\'s choice',
	PRIMARY KEY (player_id, game_id) COMMENT 'Composite key',
	CONSTRAINT FK_Player_Game_player_id FOREIGN KEY (player_id) REFERENCES Player (player_id),
	CONSTRAINT FK_Player_Game_game_id FOREIGN KEY (game_id) REFERENCES Game (game_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Players per game'@@

CREATE TABLE IF NOT EXISTS Move (
	move_id	BIGINT UNSIGNED NOT NULL AUTO_INCREMENT PRIMARY KEY COMMENT 'Auto-generated move id',
	player_id	BIGINT UNSIGNED NOT NULL COMMENT 'Player id',
	game_id	BIGINT UNSIGNED NOT NULL COMMENT 'Game id',
	x TINYINT UNSIGNED NOT NULL,
	y TINYINT UNSIGNED NOT NULL,
	INDEX Idx_Move_game_id_player_id (game_id, player_id) COMMENT 'Index used when querying moves per game',
	CONSTRAINT FK_Move_player_id FOREIGN KEY (player_id) REFERENCES Player (player_id),
	CONSTRAINT FK_Move_game_id FOREIGN KEY (game_id) REFERENCES Game (game_id)
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COMMENT='Stores all the moves made in a game'@@

DELIMITER ;
