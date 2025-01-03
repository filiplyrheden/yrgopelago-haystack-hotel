document.addEventListener('DOMContentLoaded', function() {
    const budgetCalendar = document.getElementById('budget-calendar');
    const standardCalendar = document.getElementById('standard-calendar');
    const luxuryCalendar = document.getElementById('luxury-calendar');

        // Fetch booked dates from the server for budget room
        fetch('/app/logic/budget_calendar.php')
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
                } else {
                    dateDiv.classList.add('available');
                }

                budgetCalendar.appendChild(dateDiv);
            }
    })
    .catch(error => console.error('Error fetching booked dates:', error));

    // Fetch booked dates for standard room
    fetch('/app/logic/standard_calendar.php')
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
            } else {
                dateDiv.classList.add('available');
            }

            standardCalendar.appendChild(dateDiv);
        }
})
.catch(error => console.error('Error fetching booked dates:', error));

// Fetch booked dates from the server for luxury room
fetch('/app/logic/luxury_calendar.php')
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
                } else {
                    dateDiv.classList.add('available');
                }

                luxuryCalendar.appendChild(dateDiv);
            }
        })
        .catch(error => console.error('Error fetching booked dates:', error));
});

function calculateTotalCost() {
    document.addEventListener('DOMContentLoaded', (event) => {
        const arrivalDateInput = document.getElementById('arrival_date');
        const departureDateInput = document.getElementById('departure_date');
        const roomType = document.getElementById('room_type');
        const totalCostElement = document.getElementById('total_cost');
        const discountText = document.getElementById('discount_text');
        
        function calculateDays() {
            const arrivalDate = new Date(arrivalDateInput.value);
            const departureDate = new Date(departureDateInput.value);
            
            if (arrivalDate && departureDate) {
                const timeDifference = departureDate - arrivalDate;
                const daysDifference = timeDifference / (1000 * 3600 * 24);
                
                // Get room cost
                const roomCost = parseInt(roomType.options[roomType.selectedIndex].getAttribute('data-cost'));
                let totalCost = daysDifference * roomCost;
                
                // Add feature costs
                const features = document.querySelectorAll('input[name="features[]"]:checked');
                features.forEach(feature => {
                    totalCost += parseInt(feature.getAttribute('data-cost'));
                });

                // Apply discount for three or more days
                if (daysDifference >= 3) {
                    totalCost *= 0.7; // 30% discount
                    discountText.textContent = "Enjoy 30% discount!";
                }
                
                // Update display
                if (totalCostElement) {
                    totalCostElement.textContent = isNaN(totalCost) ? '' : totalCost;
                }
            }
        }
        
        // Add event listeners
        arrivalDateInput.addEventListener('change', calculateDays);
        departureDateInput.addEventListener('change', calculateDays);
        roomType.addEventListener('change', calculateDays);
        
        // Add event listeners for features
        const features = document.querySelectorAll('input[name="features[]"]');
        features.forEach(feature => {
            feature.addEventListener('change', calculateDays);
        });
        
        // Initial calculation
        calculateDays();
    });
}

// Initialize the calculator
calculateTotalCost();