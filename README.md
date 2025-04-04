# Country Currency App

<p align="center">
<img src="https://img.shields.io/badge/Laravel-12.0-red" alt="Laravel Version">
<img src="https://img.shields.io/badge/PHP-8.3-blue" alt="PHP Version">
<img src="https://img.shields.io/badge/Vue.js-3.0-green" alt="Vue.js Version">
<img src="https://img.shields.io/badge/Inertia.js-2.0-purple" alt="Inertia.js Version">
</p>

## About Country Currency App

Country Currency App is a comprehensive web application that provides detailed information about countries, cities, and currencies around the world. Built with Laravel and Vue.js, this application offers a modern, responsive interface for exploring global geographic and economic data.

## Features

- **Secure Authentication**
  - Two-factor verification via email for both signup and login
  - Session-based authentication with secure middleware

- **Countries Management**
  - View, create, edit, and delete country information
  - Search countries by name, code, capital, or region
  - Display geographic information including coordinates

- **Cities Management**
  - Browse cities with their associated countries
  - Search cities by name, country, or state
  - View geographic coordinates for each city

- **Currencies Management**
  - Track currency information including codes and exchange rates
  - Search currencies by name, code, or keywords
  - View currency details with USD exchange rates

## Technology Stack

- **Backend**
  - Laravel 12.0
  - PHP 8.3
  - MySQL Database

- **Frontend**
  - Vue.js 3.0
  - Inertia.js 2.0
  - Tailwind CSS

- **APIs**
  - RestCountries API for country data
  - GeoDB Cities API for city data
  - OpenExchangeRates API for currency exchange rates

## Installation

### Prerequisites

- PHP 8.3 or higher
- Composer
- Node.js and NPM
- MySQL
- RapidAPI key for GeoDB Cities API
- OpenExchangeRates API key
- Email service account (e.g., Mailtrap.io) for two-factor authentication

### Setup Steps

1. **Clone the repository**

   ```bash
   git clone https://github.com/yourusername/country-currency-app.git
   cd country-currency-app
   ```

2. **Install PHP dependencies**

   ```bash
   composer install
   ```

3. **Install JavaScript dependencies and build assets**

   ```bash
   npm install && npm run dev
   ```

4. **Configure environment variables**

   ```bash
   cp .env.example .env
   php artisan key:generate
   ```

   Then edit the `.env` file to set up your database, add your API keys, and configure email for two-factor authentication:

   ```
   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=country_currency_app
   DB_USERNAME=your_username
   DB_PASSWORD=your_password

   RAPID_API_KEY=your_rapidapi_key
   OPEN_EXCHANGE_RATES_API_KEY=your_openexchangerates_key

   # Email Configuration for Two-Factor Authentication
   MAIL_MAILER=smtp
   MAIL_HOST=smtp.mailtrap.io
   MAIL_PORT=2525
   MAIL_USERNAME=your_mailtrap_username
   MAIL_PASSWORD=your_mailtrap_password
   MAIL_ENCRYPTION=tls
   MAIL_FROM_ADDRESS="no-reply@example.com"
   MAIL_FROM_NAME="${APP_NAME}"
   ```

5. **Run migrations**

   ```bash
   php artisan migrate
   ```

6. **Fetch initial data**

   ```bash
   php artisan fetch:countries
   php artisan fetch:cities
   php artisan fetch:currencies
   ```

7. **Email Configuration for Two-Factor Authentication**

   This application uses email-based two-factor authentication for enhanced security. To set this up:

   - Create a free account at [Mailtrap.io](https://mailtrap.io) or use another SMTP service
   - Copy the SMTP credentials from your Mailtrap inbox settings
   - Update the email configuration in your `.env` file with these credentials
   - Test the email functionality by registering a new user

8. **Start the development server**

   ```bash
   php artisan serve
   ```

   The application will be available at http://localhost:8000

## Usage

1. **Authentication**
   - Register a new account or log in with existing credentials
   - Complete the two-factor verification process via email

2. **Dashboard**
   - Navigate to Countries, Cities, or Currencies sections
   - Use the search functionality to find specific items

3. **Managing Data**
   - Create new entries using the "Add" buttons
   - Edit or delete existing entries as needed
   - View detailed information by clicking on specific items

## Data Sources

This application uses the following APIs to fetch data:

- **RestCountries API** (https://restcountries.com/v3.1/all) - For country data
- **GeoDB Cities API** (https://wft-geo-db.p.rapidapi.com) - For city data
- **OpenExchangeRates API** (https://openexchangerates.org/api) - For currency exchange rates

## License

This application is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).

## Laravel Sponsors

We would like to extend our thanks to the following sponsors for funding Laravel development. If you are interested in becoming a sponsor, please visit the [Laravel Partners program](https://partners.laravel.com).

### Premium Partners

- **[Vehikl](https://vehikl.com/)**
- **[Tighten Co.](https://tighten.co)**
- **[WebReinvent](https://webreinvent.com/)**
- **[Kirschbaum Development Group](https://kirschbaumdevelopment.com)**
- **[64 Robots](https://64robots.com)**
- **[Curotec](https://www.curotec.com/services/technologies/laravel/)**
- **[Cyber-Duck](https://cyber-duck.co.uk)**
- **[DevSquad](https://devsquad.com/hire-laravel-developers)**
- **[Jump24](https://jump24.co.uk)**
- **[Redberry](https://redberry.international/laravel/)**
- **[Active Logic](https://activelogic.com)**
- **[byte5](https://byte5.de)**
- **[OP.GG](https://op.gg)**

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
