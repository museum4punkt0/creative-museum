<?php

/*
 * This file is part of the jwied/creative-museum.
 *
 * For the full copyright and license information, please read the
 * LICENSE file that was distributed with this source code.
 */

$GLOBALS['TYPO3_CONF_VARS'] = array_replace_recursive(
  $GLOBALS['TYPO3_CONF_VARS'],
  [
    'BE' => [
      'debug' => getenv('TYPO3_BE_DEBUG'),
    ],
    'DB' => [
      'Connections' => [
        'Default' => [
          'dbname' => getenv('TYPO3_DB_CONNECTIONS_DEFAULT_NAME'),
          'host' => getenv('TYPO3_DB_CONNECTIONS_DEFAULT_HOST'),
          'password' => getenv('TYPO3_DB_CONNECTIONS_DEFAULT_PASS'),
          'port' => getenv('TYPO3_DB_CONNECTIONS_DEFAULT_PORT'),
          'user' => getenv('TYPO3_DB_CONNECTIONS_DEFAULT_USER'),
        ],
      ],
    ],
    'MAIL' => [
      'transport' => getenv('TYPO3_MAIL_TRANSPORT'),
      'transport_smtp_server' => getenv('TYPO3_MAIL_TRANSPORT_SMTP_SERVER'),
      'transport_sendmail_command' => getenv('TYPO3_MAIL_TRANSPORT_SENDMAIL_COMMAND'),
    ],
    'SYS' => [
      'trustedHostsPattern' => getenv('TYPO3_SYS_TRUSTED_HOSTS_PATTERN'),
      'devIPmask' => getenv('TYPO3_SYS_DEV_IPMASK'),
      'displayErrors' => getenv('TYPO3_SYS_DISPLAY_ERRORS'),
      'exceptionalErrors' => getenv('TYPO3_SYS_EXCEPTIONAL_ERRORS'),
    ],
  ]
);
