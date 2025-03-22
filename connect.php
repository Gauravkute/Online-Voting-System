<?php

$connect = mysqli_connect("localhost", "root", "", "voting") or die("Connection Failed!!!");


if ($connect) {
    echo "Connected!!\n";
} else {
    echo "Not Connected!!!\n";
}
