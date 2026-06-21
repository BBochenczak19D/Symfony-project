# Symfony Project — Personal Finance Manager

A web application built with Symfony 7.4 that lets users manage wallets, financial operations, categories, and tags.

## Features

- User registration and login
- Create and manage wallets with a balance
- Add financial operations linked to a wallet
- Categorize operations and tag them
- Task list
- Admin panel for managing users
- Access control based on roles and voters (Wallet, Operation, Category)

## Requirements

- PHP >= 8.2
- Composer
- Docker and Docker Compose (for the PostgreSQL database and Mercure)
- Symfony CLI (optional, for the local dev server)

## Installation

1. Clone the repository:
   ```bash
   git clone https://github.com/BBochenczak19D/Symfony-project.git
   cd Symfony-project
   ```

2. Install PHP dependencies:
   ```bash
   composer install
   ```

3. Configure environment variables — copy `.env` to `.env.local` and set, among others, `APP_SECRET` and `DATABASE_URL`.

4. Start the containers (database, Mercure):
   ```bash
   docker compose up -d
   ```

5. Run database migrations and load test data:
   ```bash
   doctrine:migrations:migrate
   doctrine:fixtures:load
   ```


The application will be available at `http://localhost:8000`.


## Project structure

```
src/
├── Controller/    # Controllers (Wallet, Category, Operation, Security, ...)
├── Entity/        # Doctrine entities (Wallet, Operation, Category, Tag, Task, User)
├── Repository/    # Doctrine repositories
├── Form/          # Symfony forms
├── Security/Voter/# Voters responsible for authorization
├── Service/       # Business logic
└── DataFixtures/  # Test data
templates/         # Twig views
```

## Technologies

- Symfony 7.4
- Doctrine ORM
- PostgreSQL
- Symfony UX (Stimulus, Turbo)
- Mercure (real-time updates)
- PHPUnit, PHPStan, PHP CodeSniffer, PHP-CS-Fixer, Rector

## License

Educational project
