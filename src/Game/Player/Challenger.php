<?php

namespace Game\Player;

use Game\Player;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * Challenger class
 * Player that creates the game
 *
 */
class Challenger extends Player
{
    /**
     * Challenger player type
     * @var string
     */
    const TYPE = 'challenger';
    
    /**
     * Sets the user type
     * 
     * {@inheritDoc}
     * @see \Game\Player\Base::_setType()
     */
    protected function _setType()
    {
        $this->type = self::TYPE;
    }
}
