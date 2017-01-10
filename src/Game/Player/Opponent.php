<?php

namespace Game\Player;

use Game\Player;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * Opponent class
 * Player that accepts the challenge
 */
class Opponent extends Player
{
    
	/**
	 * Opponent player type
	 * 
	 * @var string
	 */
    const TYPE = 'opponent';
    
    /**
     * Sets the opponent user type
     * 
     * {@inheritDoc}
     * @see \Game\Player\Base::_setType()
     */
    protected function _setType()
    {
        $this->type = self::TYPE;
    }
}
