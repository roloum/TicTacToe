<?php

namespace Game\Exception;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 *
 * Exception thrown when the Controller attemps to create a game with the --force option,
 * in a channel that currently has an active game
 */
class ActiveGame extends \Exception
{
}