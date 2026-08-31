<?php
$conn = mysqli_connect("localhost", "root", "", "stylehub");

if (!$conn) {
    die("Connection Failed: " . mysqli_connect_error());
}
?>