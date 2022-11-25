<?php

$servername = "localhost";
$username = "root";
$password = "";
$database = "BikeRentalWebsite";

$connection = new mysqli($servername, $username, $password, $database);

// Check connection
if ($connection->connect_errno) {
    echo "Failed to connect to MySQL: " . $connection->connect_error . "\n";
    exit();
} else {
    echo "connect successfully :)\n";
}


// ------ create tabel 1 ------
$query = "CREATE TABLE users (id int NOT NULL AUTO_INCREMENT,
fname varchar(100) NOT NULL, 
lname varchar(100) NOT NULL, 
gender varchar(50) NOT NULL, 
email varchar(255) NOT NULL, 
password varchar(255) NOT NULL, 
haveDisabilities varchar(10) NOT NULL, 
disabilities varchar(255), 
verified varchar(50),
preferableAccessories varchar(255), 
PRIMARY KEY (id));";

if ($connection->query($query) === TRUE) {
    echo "users table created successfully\n";
} else {
    echo "users table not created\n";
}

// ------ create tabel 2 ------
$query = "CREATE TABLE bikes (id int NOT NULL AUTO_INCREMENT,
bikeModel varchar(100) NOT NULL, 
rDate varchar(100) NOT NULL, 
rTime varchar(50) NOT NULL, 
price varchar(50) NOT NULL, 
age varchar(50) NOT NULL, 
location varchar(150) NOT NULL, 
type varchar(150), 
PRIMARY KEY (id));";


if ($connection->query($query) === TRUE) {
    echo "bikes table created successfully\n";
} else {
    echo "bikes table not created\n";
}

// ------ create tabel 3 ------
$query = "CREATE TABLE rentalBikes (id int NOT NULL AUTO_INCREMENT,
uid int NOT NULL,
bid int NOT NULL,
PRIMARY KEY (id),
FOREIGN KEY (uid) REFERENCES users(id),
FOREIGN KEY (bid) REFERENCES bikes(id));";


if ($connection->query($query) === TRUE) {
    echo "rentalBikes table created successfully\n";
} else {
    echo "rentalBikes table not created\n";
}

// ------ create tabel 4 ------
$query = "CREATE TABLE contacts (id int NOT NULL AUTO_INCREMENT,
name varchar(100) NOT NULL, 
phone varchar(11) NOT NULL, 
email varchar(50) NOT NULL, 
message varchar(255) NOT NULL, 
PRIMARY KEY (id));";

if ($connection->query($query) === TRUE) {
    echo "contacts table created successfully\n";
} else {
    echo "contacts table not created\n";
}

// ------ insert bikes info ------
$bikeModel = array("RoyalBaby", "Spartan", "Vego", "Mogoo");
$bikeType = array(
    "RoyalBaby" => "14 Inch Pink Stardust Bicycle",
    "Spartan" => "700c Black Platinum City Bicycle",
    "Vego" => "20 Inch Grey Royal Kids Bicycle",
    "Mogoo" => "20 Inch Blue Max Kids Bicycle 20-inch"
);
$bikePrice = array(
    "RoyalBaby" => "70 &dollar;",
    "Spartan" => "85 &dollar;",
    "Vego" => "75 &dollar;",
    "Mogoo" => "80 &dollar;"
);
$bikeAge = array(
    "RoyalBaby" => "4 Yaers &amp; Above",
    "Spartan" => "16 Yaers &amp; Above",
    "Vego" => "12 Yaers &amp; Above",
    "Mogoo" => "18 Yaers &amp; Above"
);
$bikeLocation = array(
    "RoyalBaby" => "Ash Shati District, Bicycle Time Store",
    "Spartan" => "Al Zahra District, Jeddah Cyclist Store",
    "Vego" => "Ash Shati District, Bicycle Time Store",
    "Mogoo" => "Al Zahra District, Jeddah Cyclist Store",
);

$query = "INSERT INTO bikes (bikeModel, type, price, age, location, rDate, rTime)
            VALUES ('John', 'Doe', 'john@example.com')";

for ($x = 0; $x < count($bikeModel); $x++) {
    for ($d = 7; $d < 11; $d++) {
        for ($t = 5; $t < 9; $t++) {
            $bike = $bikeModel[$x];

            $query = "INSERT INTO bikes (bikeModel, type, price, age, location, rDate, rTime)
            VALUES ('" . $bike . "', '" . $bikeType[$bike] . "', '" . $bikePrice[$bike] . "',
             '" . $bikeAge[$bike] . "', '" . $bikeLocation[$bike] . "', 
             '" . $d . "-11-2022', '" . $t . ":00 PM - " . ($t + 1) . ":00 PM' );";

            if ($connection->query($query) === TRUE) {
                echo "record for bike " . $x . " inserted successfully \n";
            } else {
                echo "record for bike " . $x . " not inserted successfully \n";
            }
        }
    }
}





// ------ close connection ------
$connection->close();
