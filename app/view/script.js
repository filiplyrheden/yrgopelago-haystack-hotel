document.addEventListener('DOMContentLoaded', function() {
    const calendar = document.getElementById('calendar');

    // Fetch booked dates from the server
    fetch('/app/logic/calendar.php')
        .then(response => response.json())
        .then(bookedDates => {
            // Debugging: Log the booked dates and its type
            console.log('Booked Dates:', bookedDates);
            console.log('Type of Booked Dates:', typeof bookedDates);
            console.log('Is Array:', Array.isArray(bookedDates));

            // Ensure bookedDates is an array
            if (!Array.isArray(bookedDates)) {
                console.error('Booked dates is not an array');
                return;
            }

            // Generate the days of January
            for (let day = 1; day <= 31; day++) {
                const dateDiv = document.createElement('div');
                dateDiv.textContent = day;

                // Check if the date is booked
                if (bookedDates.includes(day)) {
                    dateDiv.classList.add('booked');
                }

                calendar.appendChild(dateDiv);
            }
        })
        .catch(error => console.error('Error fetching booked dates:', error));
});