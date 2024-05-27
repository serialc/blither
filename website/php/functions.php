<?php
// Filename: php/func.php
// Purpose: Some miscellaneous functions

namespace frakturmedia\blither;

function printAlert($msg, $type='danger')
{
    echo '<div class="alert alert-' . $type . '" role="alert">' . $msg . '</div>';
}

function buildResponse($data, $status = 200): string
{
    header("HTTP/1.1 " . $status . " " . requestStatus($status));
    header('Content-Type: application/json; charset=utf-8');
    return json_encode($data, JSON_UNESCAPED_SLASHES);
}

function requestStatus($code): string
{
    $status = array(
        200 => 'OK',
        201 => 'Created',
        204 => 'No content',
        400 => 'Bad Request',
        401 => 'Unauthorized',
        403 => 'Forbidden',
        404 => 'Not Found',
        405 => 'Method Not Allowed',
        409 => 'Conflict',
        500 => 'Internal Server Error',
    );
    return ($status[$code]) ?: $status[500];
}

function getRandomCode($size)
{
    $random = new \PragmaRX\Random\Random();
    return $random->alpha()->size($size)->get();
}

// EOF
