<?php

$finder = (new PhpCsFixer\Finder())
    ->in(__DIR__)
    ->exclude('var')
;

/*
 * Read composer.json
 */
$string = file_get_contents('composer.json');
$composer = json_decode($string, true);
if (is_array($composer)) {
    $name = $composer['name'];
} else {
    $name = dirname('.');
}

$header = <<<EOF
This file is part of the $name.

For the full copyright and license information, please read the
LICENSE file that was distributed with this source code.
EOF;

return (new PhpCsFixer\Config())
    ->setRules([
        '@Symfony' => true,
        'header_comment' => [
            'header' => $header,
        ],
    ])
    ->setFinder($finder)
;
