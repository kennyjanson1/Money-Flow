# Money Management App

A comprehensive Laravel-based web application for personal finance management, built with Inertia.js for a modern single-page application experience and styled with Tailwind CSS.

## Features

### Dashboard
- **Financial Overview**: Total balance, income, and expenses summary
- **Cashflow Charts**: Visualize income and expenses over time (this week, this month, this year)
- **Spending Categories**: Breakdown of expenses by category with interactive charts
- **Recent Transactions**: Quick view of latest financial activities
- **Savings Progress**: Track progress on active savings goals
- **Month-over-Month Comparison**: Compare current month with previous month performance

### Transactions Management
- **CRUD Operations**: Create, read, update, and delete transactions
- **Bulk Import**: Add multiple transactions at once
- **Soft Delete**: Restore accidentally deleted transactions
- **Categorization**: Assign transactions to custom categories
- **Filtering**: Filter by type (income/expense), date ranges, categories

### Categories
- **Custom Categories**: Create and manage expense/income categories
- **Default Categories**: Pre-loaded common categories
- **Color Coding**: Visual distinction with category colors

### Cashflow Analysis
- **Trend Visualization**: Charts showing financial trends
- **Expense Breakdown**: Detailed analysis of spending patterns
- **Monthly Summary**: Comprehensive monthly financial reports

### Goals (Savings Plans)
- **Goal Setting**: Create savings goals with target amounts and deadlines
- **Progress Tracking**: Monitor savings progress with visual indicators
- **Savings Transactions**: Record contributions towards goals
- **Goal Management**: Complete, cancel, or modify goals

### Account Management
- **Profile Settings**: Update personal information
- **Password Change**: Secure password management
- **Account Deletion**: Option to delete account with data cleanup

### Get Help
- **FAQ Section**: Frequently asked questions
- **Contact Form**: Direct support communication
- **Video Tutorials**: Educational content for app usage
- **Quick Links**: Helpful resources and guides

## Technology Stack

- **Backend**: Laravel 12 (PHP 8.2+)
- **Frontend**: Inertia.js with Vue.js
- **Styling**: Tailwind CSS
- **Database**: MySQL (with migrations and seeders)
- **Authentication**: Laravel Sanctum
- **Build Tool**: Vite
- **Deployment**: IfinityFree

### Setup Steps

1. **Clone the repository**
   ```bash
   git clone <repository-url>
   cd money-management
   ```

2. **Install PHP dependencies**
   ```bash
   composer install
   ```

3. **Install Node.js dependencies**
   ```bash
   npm install
   ```

4. **Environment configuration**
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
   Configure your database and other settings in `.env`

5. **Database setup**
   ```bash
   php artisan migrate
   php artisan db:seed
   ```

6. **Build assets**
   ```bash
   npm run build
   ```

7. **Start the development server**
   ```bash
   php artisan serve
   ```

8. **For development with hot reload**
   ```bash
   npm run dev
   ```

## Usage

1. **Registration**: Create a new account or login with existing credentials
2. **Dashboard**: View your financial overview and recent activities
3. **Add Transactions**: Record income and expenses with categories
4. **Set Goals**: Create savings goals and track progress
5. **Analyze Cashflow**: Use charts and reports to understand spending patterns
6. **Manage Account**: Update profile and settings

## API Endpoints

The application provides several API endpoints for data retrieval:

- `/api/dashboard/spending-data`: Category spending data for charts
- `/api/cashflow/data`: Cashflow data for visualization
- Various CRUD endpoints for transactions, goals, and categories

## Database Schema

### Key Tables
- `users`: User accounts
- `transactions`: Financial transactions
- `categories`: Transaction categories
- `savings_plans`: Savings goals
- `savings_transactions`: Contributions to savings goals

## Deployment

### InfinityFree Hosting
The application is deployed and accessible at: [Your InfinityFree URL here]

## Contributing

1. Fork the repository
2. Create a feature branch
3. Make your changes
4. Add tests if applicable
5. Submit a pull request

## License

This project is licensed under the MIT License - see the LICENSE file for details.

