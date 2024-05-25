<!DOCTYPE html>
<html>

<head>
    <meta charset="UTF-8">
    <meta name="description" content="Reserve Movie Ticket Page">
    <meta name="author" content="Michał Kulikowski">
    <title>Reserve Movie Ticket</title>

    <link rel="icon" href="images/main_icon.png" type="image/png">
    <link href="styles/style.css" type="text/css" rel="stylesheet" />
    <link href="styles/tailwind.css" type="text/css" rel="stylesheet" />
    <script type="text/javascript" src="script.js" defer></script>
    <script src="https://unpkg.com/sweetalert/dist/sweetalert.min.js"></script>
</head>

<body class="antialiased wrapper" style="z-index: 3 !important;">

<?php
// Connect with Database
include 'db_connect.php';

// Process form submission
include 'post.php';

$selectedMovieId = null;
$selectedSeats = [];
?>

<form action="index.php" method="post" class="container" id="reservationForm">

    <!-- Hidden input field to store selected movie ID -->
    <input type="hidden" name="selectedMovieId" id="selectedMovieId">

    <input type="hidden" name="selectedSeats" id="selectedSeats">

    <div class="upper-text mb-5 pt-5">
        Select the movie
    </div>

    <div class="movie-container" id="movieContainer" name="movieContainer">
        <?php foreach ($movies as $movie): ?>
            <div class="movie" data-movie-id="<?php echo $movie['movie_id']; ?>">
                <img src="images/<?php echo $movie['title']; ?>.jpg" alt="<?php echo $movie['title']; ?>">
                <p><?php echo $movie['title']; ?></p>
            </div>
        <?php endforeach; ?>
    </div>

    <div class="hidden-input pt-2" id="hidden-input-id">
        <div class="upper-text">
            <p>Select the seat</p>
        </div>

        <div class="seat-container" id="seatContainer">
            <?php foreach ($seats as $seat): ?>
                <?php
                // Check if the seat is for the selected movie
                $seatAvailabilityClass = ($seat['availability'] == 0) ? 'occupied' : (in_array($seat['seat_id'], $selectedSeats) ? 'selected' : 'available');
                ?>

                <div class="seat <?php echo $seatAvailabilityClass; ?> movie-<?php echo $seat['movie_id']; ?>"
                    data-seat-id="<?php echo $seat['seat_id']; ?>" data-price="<?php echo $seat['price']; ?>" data-movie-id="<?php echo $seat['movie_id']; ?>">
                    <i class="fas fa-chair"></i>
                    <p><?php echo $seat['seat_number']; ?></p>
                </div>
            <?php endforeach; ?>
        </div>

        <div class="upper-text pt-2">
            <p>Additional informations</p>
        </div>

        <p class="medium-text">Name for your reservation </p>
        <input type="text" name="customerName" class="border border-gray-300 p-1 medium-input-text" 
            value="<?= $_SESSION['user_auth_displayname'] ?? '' ?>" style="opacity: 0.5; width:22%;" maxlength="60" required>

            <div class="submit-container">
                <div class="upper-text" id="totalPrice" name="totalPrice"></div>
                <div style="padding-left: 2%;"><button name="SubmitButton" class="window_button" id="myBtn" style="width: 210px;">Make reservation</button></div>
            </div>

    </div>

</form>

<div class="planet planet_1" id="planet_1"></div>
<div class="planet planet_2" id="planet_2"></div>

</body>
</html>
