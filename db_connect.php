<?php
$app_env = "local"; //production / local
$seed_the_database = false;

if($app_env == "local"){
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);

    $servername = "localhost";
    $username = "root";
    $password = "";
    $dbname = "tickets_reservation_db";
    $_SESSION['user_auth_username'] = "mkulikowski";
    $_SESSION['user_auth_displayname'] = "Michał Kulikowski";
}


$initConn = new mysqli($servername, $username, $password);

if ($initConn->connect_error) {
    die("initConnection failed: " . $initConn->initConnect_error);
}

// Check if such database exists, if not create one
$createDatabaseQuery = "CREATE DATABASE IF NOT EXISTS $dbname";
if ($initConn->query($createDatabaseQuery) === TRUE) {
} else {
    die("Error creating database: " . $initConn->error);
}

// Close the initConnection
$initConn->close();


// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);
// Check connection
if ($conn->connect_error) {
  die("Connection failed: " . $conn->connect_error);
}


if($seed_the_database) 
{
    // Drop the database if it exists
    $sqlDropDatabase = "DROP DATABASE IF EXISTS $dbname";
    if ($conn->query($sqlDropDatabase) === TRUE) {
        echo "Database dropped successfully<br>";
    } else {
        echo "Error dropping database: " . $conn->error . "<br>";
    }

    // Create the database
    $sqlCreateDatabase = "CREATE DATABASE $dbname";
    if ($conn->query($sqlCreateDatabase) === TRUE) {
        echo "Database created successfully<br>";
    } else {
        echo "Error creating database: " . $conn->error . "<br>";
    }

    // Reinitialise the connection
    $conn->close();
    $conn = new mysqli($servername, $username, $password, $dbname);
    if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
    }

    // SQL to create Movies table
    $sqlMovies = "CREATE TABLE Movies (
        movie_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        title VARCHAR(255) NOT NULL,
        release_date DATE NOT NULL
    )";

    if ($conn->query($sqlMovies) === TRUE) {
        echo "Table Movies created successfully";
        
        // Insert sample movie data
        $sampleMoviesData = "INSERT INTO Movies (title, release_date)
                            VALUES 
                            ('Heat - 1995', '1995-12-06'),
                            ('Blade Runner - 1982', '1982-06-25')";

        if ($conn->query($sampleMoviesData) === TRUE) {
            echo "Sample movie data inserted successfully";
        } else {
            echo "Error inserting sample movie data: " . $conn->error;
        }
    } else {
        echo "Error creating Movies table: " . $conn->error;
    }

    // SQL to create Seats table
    $sqlSeats = "CREATE TABLE Seats (
        seat_id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        seat_number VARCHAR(10) NOT NULL,
        availability BOOLEAN NOT NULL DEFAULT 1,
        price DECIMAL(8,2) NOT NULL,
        movie_id INT(6) UNSIGNED,
        FOREIGN KEY (movie_id) REFERENCES Movies(movie_id)
    )";

    if ($conn->query($sqlSeats) === TRUE) {
        echo "Table Seats created successfully";
        
        // Insert sample seat data
        $sampleSeatsData = "INSERT INTO Seats (seat_number, availability, price, movie_id)
                            VALUES 
                            ('A1', 1, 10.00, 1),
                            ('A2', 1, 10.00, 1),
                            ('B1', 1, 8.00, 1),
                            ('B2', 1, 8.00, 1),
                            ('A1', 1, 10.00, 2),
                            ('A2', 1, 10.00, 2),
                            ('B1', 1, 8.00, 2),
                            ('B2', 1, 8.00, 2),
                            ('C1', 1, 8.00, 2)";

        if ($conn->query($sampleSeatsData) === TRUE) {
            echo "Sample seat data inserted successfully";
        } else {
            echo "Error inserting sample seat data: " . $conn->error;
        }
    } else {
        echo "Error creating Seats table: " . $conn->error;
    }

    // SQL to create Reservations table
    $sqlReservations = "CREATE TABLE Reservations (
        id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        movie_id INT(6) UNSIGNED,
        reservation_date DATE NOT NULL,
        customer_name VARCHAR(255) NOT NULL,
        num_tickets INT(3) NOT NULL,
        seat_id INT(6) UNSIGNED,
        payment_status ENUM('Pending', 'Paid') NOT NULL DEFAULT 'Pending',
        FOREIGN KEY (movie_id) REFERENCES Movies(movie_id),
        FOREIGN KEY (seat_id) REFERENCES Seats(seat_id)
    )";

    if ($conn->query($sqlReservations) === TRUE) {
        echo "Table Reservations created successfully";
    } else {
        echo "Error creating Reservations table: " . $conn->error;
    }
}


// Fetch movies from the database
$sql = "SELECT movie_id, title, release_date FROM Movies";
$result = $conn->query($sql);

$movies = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $movies[] = $row;
    }
}

// Fetch seats from the database
$sql = "SELECT * FROM Seats";
$result = $conn->query($sql);

$seats = [];

if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $seats[] = $row;
    }
}

// Close the connection
$conn->close();

?>