<?php
namespace InterNations\Component\Testing;

use Symfony\Component\HttpFoundation\Session\Session;
use Exception;

trait SessionTrait
{
    protected function initSessionState(Session $session, array $sessionData)
    {
        $session
            ->method('get')
            ->will(
                $this->returnCallback(
                    static function ($key, $default = null) use ($sessionData) {
                        if (!array_key_exists($key, $sessionData)) {
                            throw new Exception('Program tried to access undefined key in session: "' . $key . '"');
                        }

                        return $sessionData[$key] ?: $default;
                    }
                )
            );
        $session
            ->method('has')
            ->will(
                $this->returnCallback(
                    static function ($key) use ($sessionData) {
                        if (!array_key_exists($key, $sessionData)) {
                            throw new Exception('Program tried to access undefined key in session: "' . $key . '"');
                        }

                        return isset($sessionData[$key]);
                    }
                )
            );
    }
}
