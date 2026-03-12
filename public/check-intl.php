<?php
echo "PHP Version: " . phpversion() . "<br>";
echo "Intl Extension Loaded: " . (extension_loaded('intl') ? '✅ YES' : '❌ NO') . "<br>";
echo "Loaded Configuration File: " . php_ini_loaded_file() . "<br>";
phpinfo();
