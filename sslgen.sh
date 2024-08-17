#!/usr/bin/env php
<?php

var_dump([
        getcwd(),
        get_include_path(),
        realpath("./ssl-cert/ca.pem"),
        file_exists("./ssl-cert/ca.pem"),
]);

require 'phar://' . __DIR__ . DIRECTORY_SEPARATOR . 'sslgen.phar';
