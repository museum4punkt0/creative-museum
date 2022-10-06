<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/../../', '.env.defaults');
$dotenv->load();
if (file_exists(__DIR__ . '/../../.env')) {
    $dotenv = Dotenv\Dotenv::createMutable(__DIR__ . '/../../');
    $dotenv->load();
}

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
  $GLOBALS['TYPO3_CONF_VARS'],
  [
    'BE' => [
      'debug' => $_ENV['TYPO3_BE_DEBUG'],
      'installToolPassword' => $_ENV['TYPO3_BE_INSTALL_TOOL_PW']
    ],
    'DB' => [
      'Connections' => [
        'Default' => [
          'dbname' => $_ENV['TYPO3_DB_CONNECTIONS_DEFAULT_NAME'],
          'host' => $_ENV['TYPO3_DB_CONNECTIONS_DEFAULT_HOST'],
          'password' => $_ENV['TYPO3_DB_CONNECTIONS_DEFAULT_PASS'],
          'port' => $_ENV['TYPO3_DB_CONNECTIONS_DEFAULT_PORT'],
          'user' => $_ENV['TYPO3_DB_CONNECTIONS_DEFAULT_USER'],
        ],
      ],
    ],
    'MAIL' => [
      'transport' => $_ENV['TYPO3_MAIL_TRANSPORT'],
      'transport_smtp_server' => $_ENV['TYPO3_MAIL_TRANSPORT_SMTP_SERVER'],
      'transport_sendmail_command' => $_ENV['TYPO3_MAIL_TRANSPORT_SENDMAIL_COMMAND'],
    ],
    'SYS' => [
      'trustedHostsPattern' => $_ENV['TYPO3_SYS_TRUSTED_HOSTS_PATTERN'],
    ],
    'EXTENSIONS' => [
      'creativemuseum' => [
        'baseUrl' => $_ENV['TYPO3_EXTENSIONS_CREATIVEMUSEUM_BASE_URL'],
        'clientId' => $_ENV['TYPO3_EXTENSIONS_CREATIVEMUSEUM_CLIENT_ID'],
        'clientSecret' => $_ENV['TYPO3_EXTENSIONS_CREATIVEMUSEUM_CLIENT_SECRET'],
        'tokenUrl' => $_ENV['TYPO3_EXTENSIONS_CREATIVEMUSEUM_TOKEN_URL'],
      ],
    ]
  ]
);
