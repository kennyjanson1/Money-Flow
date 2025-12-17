# Money Management App

A comprehensive Laravel-based web application for personal finance management, built with Inertia.js for a modern single-page application experience and styled with Tailwind CSS.

## 🌐 Live Deployments

- **Application**: [Deployed on InfinityFree](https://casholve.great-site.net/)

## 🚀 Features

- **Financial Dashboard**: Real-time overview of balance, income, and expenses
- **Transaction Management**: Full CRUD operations with categorization and filtering
- **Cashflow Analysis**: Interactive charts and trend visualization
- **Savings Goals**: Set and track progress on financial objectives
- **Category Management**: Custom and default categories with color coding
- **Account Management**: Profile settings, password change, and secure account deletion
- **Get Help Section**: FAQ, contact form, and video tutorials

## 🏗️ Project Structure

```
money-management/
├── app/                          # Laravel Application
│   ├── Http/Controllers/         # Controllers for handling requests
│   ├── Models/                   # Eloquent models
│   ├── Policies/                 # Authorization policies
│   └── Providers/                # Service providers
├── database/                     # Database migrations and seeders
│   ├── migrations/               # Database schema migrations
│   └── seeders/                  # Database seeders
├── public/                       # Public assets
├── resources/                    # Views, CSS, and JS
│   ├── css/                      # Stylesheets
│   ├── js/                       # JavaScript files
│   └── views/                    # Blade templates
├── routes/                       # Route definitions
├── storage/                      # File storage
├── tests/                        # Test files
├── bootstrap/                    # Laravel bootstrap files
├── config/                       # Configuration files
├── composer.json                 # PHP dependencies
├── package.json                  # Node.js dependencies
└── README.md                     # This file
```

## 🛠️ Technologies Used

### Backend
- **Laravel**: High-performance PHP framework
- **MySQL**: Relational database with migrations and seeders
- **Laravel Sanctum**: API authentication
- **Inertia.js**: Modern monolith architecture

### Frontend
- **Vue.js**: Reactive JavaScript framework
- **Tailwind CSS**: Utility-first CSS framework
- **Vite**: Fast build tool and development server

## 🚀 Installation & Setup

### 1. Clone the Repository

```bash
git clone <repository-url>
cd money-management
```

### 2. Backend Setup

```bash
# Install PHP dependencies
composer install

# Environment configuration
cp .env.example .env
php artisan key:generate
```

Configure your database and other settings in `.env`

### 3. Database Setup

```bash
# Run migrations
php artisan migrate

# Seed the database
php artisan db:seed
```

### 4. Frontend Setup

```bash
# Install Node.js dependencies
npm install

# Build assets
npm run build
```

## ▶️ Running the Application

### Local Development

#### Start the Development Server

```bash
# Start Laravel server
php artisan serve
```

The backend will start on `http://localhost:8000`

#### Start Frontend Development Server

```bash
# For development with hot reload
npm run dev
```

The frontend will start on `http://localhost:5173`

### Production Deployment

#### InfinityFree Hosting
1. Upload the project files to your InfinityFree account
2. Configure the database settings in `.env`
3. Run migrations and seeders
4. Build assets with `npm run build`
5. The app will be available at your InfinityFree URL

## 📖 Usage

1. **Register/Login**: Create a new account or sign in with existing credentials
2. **Dashboard Overview**: View your financial summary and recent activities
3. **Manage Transactions**: Add, edit, or delete income and expense records
4. **Set Savings Goals**: Create and track progress on financial objectives
5. **Analyze Spending**: Use charts to understand cashflow patterns
6. **Customize Categories**: Create and manage transaction categories
7. **Account Settings**: Update profile and manage account preferences

## 🎯 How It Works

### Financial Tracking Pipeline
1. **User Registration**: Secure account creation with Laravel Sanctum
2. **Transaction Input**: Record income/expenses with categorization
3. **Data Processing**: Automatic calculations and aggregations
4. **Visualization**: Real-time charts and analytics display
5. **Goal Tracking**: Progress monitoring with visual indicators
6. **Reporting**: Comprehensive financial reports and insights

### Data Management
- **CRUD Operations**: Full create, read, update, delete functionality
- **Soft Deletes**: Restore accidentally deleted transactions
- **Filtering**: Advanced filtering by type, date, and category
- **Bulk Operations**: Import multiple transactions efficiently

## 🔧 Configuration

### Environment Variables
Create `.env` file in the root directory:
```
APP_NAME="Money Management App"
APP_ENV=local
APP_KEY=your-generated-key
APP_DEBUG=true
APP_URL=http://localhost

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=your_database
DB_USERNAME=your_username
DB_PASSWORD=your_password
```

## 👨‍💻 Author

- Developed by Kenny Janson
- Project: Money Management App

