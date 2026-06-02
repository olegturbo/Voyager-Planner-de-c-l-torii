<?php
session_start();
require_once 'php/auth.php';
logoutUser();
header('Location: index.php?msg=logout');
exit;
