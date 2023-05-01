<?php 

/**
 * SERVER
 */
define("SERVER", $_SERVER['SERVER_NAME'] ?? "localhost");

/**
 * AUTHENTICATION
 */
define("AUTH_ALGORITHM", "HS256");
define("AUTH_SECRET_KEY", "secret-token-api-2023");
define("AUTH_EXPIRATION_TOKEN", 1);

/**
 * MONGODB CONNECT
*/
define("MONGODB_CONNECT", [
    "database" => "thewisepad",
]);