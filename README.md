# Planify - Professional Quote Management System

<p align="center">
  <h2 align="center">Planify</h2>
  <p align="center">A modern web application for creating and managing professional quotes for web development projects.</p>
</p>

## Features

- **Quote Generation System**
  - Create detailed quotes with project specifications
  - Dynamic pricing based on website type and features
  - Custom feature addition with time estimation
  - Professional quote PDF generation

- **Website Types & Features**
  - Pre-defined website types (E-commerce, Corporate, Portfolio, etc.)
  - Customizable feature sets for each website type
  - Automatic time and cost calculation
  - Custom feature addition capability

- **Client Management**
  - Client information management
  - Quote history per client
  - Client communication tracking

- **Proposal System**
  - Convert quotes to detailed proposals
  - Professional proposal templates
  - Customizable proposal sections

- **Template Management**
  - Reusable quote templates
  - Custom template creation
  - Template categorization

## Tech Stack

- **Frontend**
  - Vue.js 3
  - Inertia.js
  - Tailwind CSS
  - Headless UI

- **Backend**
  - Laravel 10
  - MySQL
  - PHP 8.2+

## Prerequisites

- PHP 8.2 or higher
- Composer
- Node.js & NPM
- MySQL

## Installation

1. Clone the repository
```bash
git clone https://github.com/yourusername/planify.git
cd planify
```

2. Install PHP dependencies
```bash
composer install
```

3. Install NPM dependencies
```bash
npm install
```

4. Create environment file
```bash
cp .env.example .env
```

5. Generate application key
```bash
php artisan key:generate
```

6. Configure your database in `.env` file
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=planify
DB_USERNAME=root
DB_PASSWORD=
```

7. Run migrations and seeders
```bash
php artisan migrate --seed
```

8. Start the development server
```bash
php artisan serve
```

9. In another terminal, start the Vite development server
```bash
npm run dev
```

## Usage

1. Register a new account at `/register`
2. Log in to the dashboard
3. Start creating quotes by clicking "New Quote" in the quotes section
4. Manage your quotes, proposals, and clients through the intuitive interface

## Contributing

1. Fork the repository
2. Create your feature branch (`git checkout -b feature/AmazingFeature`)
3. Commit your changes (`git commit -m 'Add some AmazingFeature'`)
4. Push to the branch (`git push origin feature/AmazingFeature`)
5. Open a Pull Request

## License

This project is licensed under the MIT License - see the [LICENSE](LICENSE) file for details.

## Support

For support, please email support@planify.com or open an issue in the GitHub repository.

## Acknowledgments

- [Laravel](https://laravel.com)
- [Vue.js](https://vuejs.org)
- [Tailwind CSS](https://tailwindcss.com)
- [Inertia.js](https://inertiajs.com)
