<?php
// Function to handle the reservation logic
function makeReservation($conn, $movieId, $selectedSeats, $customerName)
{
    // Initialize the response array
    $response = array('success' => false, 'message' => '');

    try {
        // Start a transaction
        $conn->begin_transaction();

        // Convert $selectedSeats to an array if it's a string
        $selectedSeats = is_array($selectedSeats) ? $selectedSeats : [$selectedSeats];

        // Insert reservation into Reservations table
        $reservationDate = date('Y-m-d'); // You may adjust the date format as needed
        $numTickets = count($selectedSeats);

        $insertReservationSQL = "INSERT INTO Reservations (movie_id, reservation_date, customer_name, num_tickets, payment_status)
                                 VALUES ('$movieId', '$reservationDate', '$customerName', '$numTickets', 'Pending')";
        $conn->query($insertReservationSQL);

        // Get the ID of the last inserted reservation
        $reservationId = $conn->insert_id;

        // Update seat availability in Seats table
        foreach ($selectedSeats as $seatId) {
            $updateSeatSQL = "UPDATE Seats SET availability = 0 WHERE seat_id = '$seatId'";
            $conn->query($updateSeatSQL);

            // Associate the seat with the reservation by updating the seat_id in the Reservations table
            $conn->query("UPDATE Reservations SET seat_id = '$seatId' WHERE id = '$reservationId'");
        }

        // Commit the transaction
        $conn->commit();

        $response['success'] = true;
        $response['message'] = 'Reservation made successfully!';
    } catch (Exception $e) {
        // Rollback the transaction on error
        $conn->rollback();

        $response['message'] = 'Error making reservation: ' . $e->getMessage();
    }

    return $response;
}


// Process form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Unset Post Button
    unset($_POST['SubmitButton']);

    $selectedMovieId = isset($_POST['selectedMovieId']) ? $_POST['selectedMovieId'] : null;
    $selectedSeats = isset($_POST['selectedSeats']) ? $_POST['selectedSeats'] : [];
    $customerName = isset($_POST['customerName']) ? $_POST['customerName'] : '';

    // echo "movie id: ".$selectedMovieId." ";
    // echo "selected seats: "; print_r($selectedSeats); echo " ";
    // echo "customer name: ".$customerName;

    // Create connection
    $conn = new mysqli($servername, $username, $password, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Validate input
    if ($selectedMovieId && !empty($selectedSeats) && $customerName) {
        $response = makeReservation($conn, $selectedMovieId, $selectedSeats, $customerName);

        // Return JSON response
        //echo json_encode($response);

        // Refresh page
        echo '
        <script type="text/javascript">
        window.location.replace("success.php");
        </script>
        ';

        // Close the connection
        $conn->close();
        exit;
    } else {
        $response = array('success' => false, 'message' => 'Invalid input. Selected movie id: '.$selectedMovieId.' and');
        echo json_encode($response);

        // Close the connection
        $conn->close();
        
        exit;
    }
}

?>