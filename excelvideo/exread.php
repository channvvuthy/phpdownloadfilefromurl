<?php
// i will tell you get data from excel with php

//first of all you add this files your project.
// And i show my excel file and add  some data

//start now :)

require("reader.php"); // php excel reader
$file="sample.xls";
$connection=new Spreadsheet_Excel_Reader(); // our main object
$connection->read($file);
$startrow=2;
$endrow=4;
$col1=3;

for($i=$startrow;$i<$endrow;$i++){ // we read row to row

echo $connection->sheets[0]["cells"][$i][$col1]."<br>"; // so we get [2][3] and [3][3]



}


// End this video.thank you for watch :) 
// Soppy


?>