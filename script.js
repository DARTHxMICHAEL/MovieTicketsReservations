document.addEventListener('DOMContentLoaded', function () {
    var selectedMovieId = null;
    var selectedSeats = [];
    var totalPrice = 0;

    var movieContainer = document.getElementById('movieContainer');
    var selectedMovieIdInput = document.getElementById('selectedMovieId');
    var hiddenInput = document.getElementById("hidden-input-id");
    var selectedSeatsInput = document.getElementById('selectedSeats');
    var totalPriceOutput = document.getElementById('totalPrice');
    var submitButton = document.getElementById('myBtn');
    submitButton.style.visibility = "hidden";
    
    function updateSeatDisplay() {
        var movieId = selectedMovieId;
        var movieSeats = document.querySelectorAll('.seat');

        movieSeats.forEach(function (seat) {
            var seatMovieId = seat.getAttribute('data-movie-id');
            seat.style.display = seatMovieId === movieId ? 'block' : 'none';
        });
    }

    movieContainer.addEventListener('click', function (event) {
        var targetMovie = event.target.closest('.movie');
        if (targetMovie) {
            document.querySelectorAll('.movie').forEach(function (movie) {
                movie.classList.remove('selected');
            });

            targetMovie.classList.add('selected'); 
            var movieId = targetMovie.getAttribute('data-movie-id');
            selectedMovieId = movieId;
            updateSeatDisplay();

            // Pass the selectedSeats to the form input
            selectedMovieIdInput.value = movieId;

            // Make rest of the form visible
            hiddenInput.style.visibility = "visible";
        }
    });


    seatContainer.addEventListener('click', function (event) {
        var targetSeat = event.target.closest('.seat');
        if (targetSeat && !targetSeat.classList.contains('occupied')) {
            targetSeat.classList.toggle('selected');
            var seatId = targetSeat.getAttribute('data-seat-id');
            var seatPrice = parseFloat(targetSeat.getAttribute('data-price'));
    
            if (!selectedSeats.includes(seatId)) {
                selectedSeats.push(seatId);
                totalPrice += seatPrice;
            } else {
                // Remove the seatId if it already exists
                selectedSeats = selectedSeats.filter(id => id !== seatId);
                totalPrice -= seatPrice;
            }
    
            // Pass the selectedSeats to the form input
            selectedSeatsInput.value = selectedSeats;
    
            totalPriceOutput.textContent = 'Total Price: ' + totalPrice.toFixed(2);

            // Show submit button
            submitButton.style.visibility = "visible";
        }
    });
    
    
    // AJAX VERSION
    // var reservationForm = document.getElementById('reservationForm');

    // submitButton.addEventListener('click', function () {
    // // Use AJAX to submit the form data
    // var xhr = new XMLHttpRequest();
    // xhr.open('POST', 'post.php', true);
    // xhr.setRequestHeader('Content-Type', 'application/x-www-form-urlencoded');
    // xhr.onreadystatechange = function () {
    //     if (xhr.readyState == 4 && xhr.status == 200) {
    //         // Handle the response, e.g., display a success message
    //         var response = JSON.parse(xhr.responseText);
    //         if (response.success) {
    //             alert('Reservation made successfully!');
    //         } else {
    //             alert('Error making reservation: ' + response.message);
    //         }
    //     }
    // };

    // // Collect selected data
    // var formData = new FormData(reservationForm);
    // var selectedMovieId = selectedMovieIdInput.value;
    // var selectedSeats = selectedSeats.value;
    
    // // Add the selected data to the FormData
    // formData.append('selectedMovieId', selectedMovieId);
    // formData.append('selectedSeats', selectedSeats);

    // // Send the form data
    // xhr.send(formData);
    // });
});
