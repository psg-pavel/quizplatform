<?php
$dbuser = 'mysql'; // your login
$dbname = 'telegrambot'; // your db name
$host = 'localhost'; // hostname
$dbpass = '';  // your password
$conn = mysqli_connect($host, $dbuser, $dbpass, $dbname);
if (mysqli_connect_errno())
  {
  echo "Failed to connect to MySQL: " . mysqli_connect_error();
  exit();
  } 
mysqli_set_charset($conn, 'utf8');