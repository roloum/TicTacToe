<?php

namespace Game\Controller;

/**
 * 
 * @author Rolando Umana<rolando.umana@gmail.com>
 * 
 * Defines all the methods that must be implemented by any controller class
 *
 */
interface ControllerInterface
{
    /**
     * Entry point for all controllers
     * 
     * @param array $data
     * @return array
     */
    public function processRequest (array $data) : array;
    
}
