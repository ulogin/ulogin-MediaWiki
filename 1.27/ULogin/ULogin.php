<?php

if (function_exists('wfLoadExtension')) {
    wfLoadExtension('ULogin');
    // Keep i18n globals so mergeMessageFileList.php doesn't break
    $wgMessagesDirs['ULogin'] = __DIR__ . '/i18n';
    $wgExtensionMessagesFiles['ULogin'] = __DIR__ . '/ULogin.i18n.alias.php';
    wfWarn(
        'Deprecated PHP entry point used for uLogin extension. Please use wfLoadExtension ' .
        'instead, see https://www.mediawiki.org/wiki/Extension_registration for more details.'
    );
    return true;
} else {
    die('This version of the uLogin extension requires MediaWiki 1.27+');
}