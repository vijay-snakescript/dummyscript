<?php

/**
 * Laravel Requirement Checker
 *
 * This standalone script will check if your server meets the requirements for running the
 * Laravel web application framework.
 *
 * @author  Gastón Heim
 * @author  Emerson Carvalho
 * @version 0.0.1
 */
$laravelVersion = '9.0';

$reqList = array(
    '9.0' => array(
        'php' => '8.0.2',
        'mcrypt' => false,
        'curl' => true,
        'openssl' => true,
        'pdo' => true,
        'fileinfo' => true,
        'exif' => true,
        'gd' => true,
        'mbstring' => true,
        'tokenizer' => true,
        'xml' => true,
        'ctype' => true,
        'json' => true,
        'bcmath' => true,
        'obs' => ''
    ),
);


$strOk = '<i class="fa fa-check icon-ok"></i>';
$strFail = '<i style="color: red" class="fa fa-times icon-remove"></i>';
$strUnknown = '<i class="fa fa-question"></i>';

$requirements = array();


// PHP Version
if (is_array($reqList[$laravelVersion]['php'])) {
    $requirements['php_version'] = true;
    foreach ($reqList[$laravelVersion]['php'] as $operator => $version) {
        if (! version_compare(PHP_VERSION, $version, $operator)) {
            $requirements['php_version'] = false;
            break;
        }
    }
} else {
    $requirements['php_version'] = version_compare(PHP_VERSION, $reqList[$laravelVersion]['php'], ">=");
}

// OpenSSL PHP Extension
$requirements['openssl_enabled'] = extension_loaded("openssl");

//cURL PHP extension
$requirements['curl_enabled'] = extension_loaded("curl");

// PDO PHP Extension
$requirements['pdo_enabled'] = defined('PDO::ATTR_DRIVER_NAME');

// Mbstring PHP Extension
$requirements['mbstring_enabled'] = extension_loaded("mbstring");

// Tokenizer PHP Extension
$requirements['tokenizer_enabled'] = extension_loaded("tokenizer");

// XML PHP Extension
$requirements['xml_enabled'] = extension_loaded("xml");

// CTYPE PHP Extension
$requirements['ctype_enabled'] = extension_loaded("ctype");

// JSON PHP Extension
$requirements['json_enabled'] = extension_loaded("json");

// Mcrypt
$requirements['mcrypt_enabled'] = extension_loaded("mcrypt_encrypt");

// BCMath
$requirements['bcmath_enabled'] = extension_loaded("bcmath");

// Fileinfo
$requirements['fileinfo_enabled'] = extension_loaded("fileinfo");

// EXIF
$requirements['exif_enabled'] = extension_loaded("exif");

// GD
$requirements['gd_enabled'] = extension_loaded("gd");

// mod_rewrite
$requirements['mod_rewrite_enabled'] = null;

if (function_exists('apache_get_modules')) {
    $requirements['mod_rewrite_enabled'] = in_array('mod_rewrite', apache_get_modules());
}
?>
<!doctype html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Server Requirements &dash; Laravel PHP Framework</title>
    <link href="//stackpath.bootstrapcdn.com/font-awesome/4.7.0/css/font-awesome.min.css" rel="stylesheet">
    <style>
        @import url(//fonts.googleapis.com/css?family=Lato:300,400,700);

        body {
            margin: 0;
            font-size: 16px;
            font-family: 'Lato', sans-serif;
            text-align: center;
            color: #999;
        }

        .wrapper {
            width: 300px;
            margin: 50px auto;
        }

        .logo{
            display: block;
            text-decoration: none;
            margin-bottom: 20px;
        }

        .logo img {
            margin-right: 1.25em;
        }

        p {
            margin: 0 0 5px;
        }

        p small {
            font-size: 13px;
            display: block;
            margin-bottom: 1em;
        }

        p.obs {
            margin-top: 20px;
            padding: 15px;
            margin-bottom: 20px;
            border: 1px solid transparent;
            border-radius: 4px;
            color: #31708f;
            background-color: #d9edf7;
            border-color: #bce8f1;
        }

        .icon-ok {
            color: #27ae60;
        }

        .icon-remove {
            color: #c0392b;
        }
    </style>
</head>
<body>
<div class="wrapper">
    <h1>Server Requirements.</h1>

    <p>
        PHP <?php
        if (is_array($reqList[$laravelVersion]['php'])) {
            $phpVersions = array();
            foreach ($reqList[$laravelVersion]['php'] as $operator => $version) {
                $phpVersions[] = "{$operator} {$version}";
            }
            echo implode(" && ", $phpVersions);
        } else {
            echo ">= " . $reqList[$laravelVersion]['php'];
        }

        echo " " . ($requirements['php_version'] ? $strOk : $strFail); ?>
        (<?php echo PHP_VERSION; ?>)
    </p>


    <?php if ($reqList[$laravelVersion]['openssl']) : ?>
        <p>OpenSSL PHP Extension <?php echo $requirements['openssl_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif; ?>

    <?php if ($reqList[$laravelVersion]['curl']) : ?>
        <p>cURL PHP Extension <?php echo $requirements['curl_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif; ?>

    <?php if ($reqList[$laravelVersion]['pdo']) : ?>
        <p>PDO PHP Extension <?php echo $requirements['pdo_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['mbstring']) : ?>
        <p>Mbstring PHP Extension <?php echo $requirements['mbstring_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['tokenizer']) : ?>
        <p>Tokenizer PHP Extension <?php echo $requirements['tokenizer_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['exif']) : ?>
        <p>EXIF PHP Extension <?php echo $requirements['exif_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['fileinfo']) : ?>
        <p>Fileinfo PHP Extension <?php echo $requirements['fileinfo_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['gd']) : ?>
        <p>GD PHP Extension <?php echo $requirements['gd_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['xml']) : ?>
        <p>XML PHP Extension <?php echo $requirements['xml_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['ctype']) : ?>
        <p>CTYPE PHP Extension <?php echo $requirements['ctype_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['json']) : ?>
        <p>JSON PHP Extension <?php echo $requirements['json_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if ($reqList[$laravelVersion]['mcrypt']) : ?>
        <p>Mcrypt PHP Extension <?php echo $requirements['mcrypt_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if (isset($reqList[$laravelVersion]['bcmath']) && $reqList[$laravelVersion]['bcmath']) : ?>
        <p>BCmath PHP Extension <?php echo $requirements['bcmath_enabled'] ? $strOk : $strFail; ?></p>
    <?php endif ?>

    <?php if (!empty($reqList[$laravelVersion]['obs'])) : ?>
        <p class="obs"><?php echo $reqList[$laravelVersion]['obs'] ?></p>
    <?php endif; ?>


    <p>magic_quotes_gpc: <?php echo !ini_get('magic_quotes_gpc') ? $strOk : $strFail; ?> (value: <?php echo ini_get('magic_quotes_gpc') ?>)</p>
    <p>register_globals: <?php echo !ini_get('register_globals') ? $strOk : $strFail; ?> (value: <?php echo ini_get('register_globals') ?>)</p>
    <p>session.auto_start: <?php echo !ini_get('session.auto_start') ? $strOk : $strFail; ?> (value: <?php echo ini_get('session.auto_start') ?>)</p>
    <p>mbstring.func_overload: <?php echo !ini_get('mbstring.func_overload') ? $strOk : $strFail; ?> (value: <?php echo ini_get('mbstring.func_overload') ?>)</p>

</div>
</body>
</html>
