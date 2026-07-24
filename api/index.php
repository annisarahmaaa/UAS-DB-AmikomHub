<?php

// Vercel mengarahkan trafik ke api/index.php
// Memperbaiki SCRIPT_NAME agar routing Laravel tidak menghapus '/api/'
$_SERVER['SCRIPT_NAME'] = '/index.php';

require __DIR__ . '/../public/index.php';
