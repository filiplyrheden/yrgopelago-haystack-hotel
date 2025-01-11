# 🌾 Haystack 

A full-stack hotel booking application built as part of the web developer education program at Yrgo.

This farm-themed hotel website allows users to browse rooms, check availability, and make bookings with a calendar interface.

The project features tacky AI-generated images, but also a nice admin page, which the fictional hotel manager can access with an API-key to dynamically change room prices, feature prices, discounts and how many stars the hotel has. 

## Tech Stack

- Backend: PHP
- Frontend: HTML, CSS, JavaScript
- Database: SQLite


## Required Packages

- [vlucas/phpdotenv](https://github.com/vlucas/phpdotenv) ^5.6 - For loading environment variables
- [guzzlehttp/guzzle](https://github.com/guzzle/guzzle) ^7.0 - HTTP client for API requests

## Installation

1. Clone this repository
```bash
git clone https://github.com/filiplyrheden/yrgopelago-haystack-hotel.git
```

2. Install dependencies using Composer
```bash
composer install
```

3. Create a `.env` file in the root directory and add your environment variables
```bash
cp .env.example .env
```

### Code review

First of all, I think it's a really nice looking site and from what I can tell it seems to work as intended. I also think that the code looks nice and I had to dig around to find a few minor things I could comment:

README.md - I'm missing information and download instructions for the packages that you've used. I see that you have the phpdotenv and guzzle etc - maybe it would be suitable to mention them in the readme-file?

booking.php, line 128 - the form input for transfercode is missing a label and uses an H4 instead.

booking.php, line 51-52 and style.css line 23 - The contrast between the text and the background is a bit on the low side.

booking.php, line 138-141 and style.css, line 216-226 - The contrast between the text and the background is a bit on the low side.

booking.php - I see that you have used h1-h6 in your file but there's no h5.

style.css - Maybe using variables for your colors would simplify swapping them out?

script.js - In the javascript there's still a few console.logs left. I suggest removing them or commenting them out.

// Josefin

