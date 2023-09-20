<?php

namespace app\core;

/**
 * Summary of Session
 * A simple flash-Message implementation
 * Flash messages are messages that are set and shown to the user only once,
 * and they are removed on the next request or page refresh.
 * 
 * @package app\core
 */
class Session
{
    // Constant to store flash messages in the session
    protected const FLASH_KEY = 'flash_messages';

    // Constructor: This method is called at the beginning of each request.
    public function __construct()
    {
        // Start or resume the PHP session
        session_start();
        
        // Retrieve existing flash messages or initialize an empty array
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        // Iterate through flash messages and mark them for removal on the next request
        foreach ($flashMessages as $key => &$flashMessage) {
            // Mark to be removed at the end of the request
            $flashMessage['remove'] = true;
        }

        // Update the session with the modified flash messages
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }

    /**
     * Summary of setFlash
     * Set a flash message with a specified key and message value.
     *
     * @param mixed $key
     * @param mixed $message
     * @return void
     */
    public function setFlash($key, $message)
    {
        // Create or update a flash message with the provided key and message
        $_SESSION[self::FLASH_KEY][$key] = [
            'remove' => false, // Mark as not to be removed on the next request
            'value' => $message,
        ];
    }

    /**
     * Summary of getFlash
     * Retrieve a flash message by its key.
     *
     * @param mixed $key
     * @return mixed
     */
    public function getFlash($key)
    {
        // Retrieve the value of the flash message if it exists, or return false
        return $_SESSION[self::FLASH_KEY][$key]['value'] ?? false;
    }
    
    public function set($key,$value)
    {
        $_SESSION[$key] = $value;
    }
    public function get($key){
        return $_SESSION[$key] ?? false;
    }

    public function remove($key)
    {
        unset($_SESSION[$key]);
    }

    // Destructor: This method is called at the end of the request.
    public function __destruct()
    {
        // Retrieve existing flash messages or initialize an empty array
        $flashMessages = $_SESSION[self::FLASH_KEY] ?? [];

        // Iterate through flash messages and remove those marked for removal
        foreach ($flashMessages as $key => &$flashMessage) {
            // Marked to be removed at the end of the request
            if ($flashMessage['remove']) {
                unset($flashMessages[$key]);
            }
        }

        // Update the session with the modified flash messages
        $_SESSION[self::FLASH_KEY] = $flashMessages;
    }
}
