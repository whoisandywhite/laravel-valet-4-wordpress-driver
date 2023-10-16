<?php

namespace Valet\Drivers\Custom;

use Valet\Drivers\ValetDriver;

class WordPressValetDriver extends ValetDriver
{
    /**
     * Determine if the driver serves the request.
     */
    public function serves(string $sitePath, string $siteName, string $uri): bool
    {
        if ( file_exists($sitePath.'/wp-config.php')
            || file_exists($sitePath.'/wp-config-sample.php') ) 
        {
            return true;
        }

        return false;
    }

    /**
     * Get the fully resolved path to the application's front controller.
     */
    public function frontControllerPath(string $sitePath, string $siteName, string $uri): string
    {
        $_SERVER['PHP_SELF'] = $uri;
        $_SERVER['SERVER_ADDR'] = '127.0.0.1';
        $_SERVER['SERVER_NAME'] = $_SERVER['HTTP_HOST'];

        return parent::frontControllerPath(
            $sitePath, $siteName, $this->forceTrailingSlash($uri)
        );
    }


    /**
     * Redirect to uri with trailing slash.
     *
     * @param string $uri
     * @return string
     */
    private function forceTrailingSlash($uri)
    {
        if (substr($uri, -1 * strlen('/wp-admin')) == '/wp-admin') {
            header('Location: ' . $uri . '/');
            die;
        }

        return $uri;
    }
}