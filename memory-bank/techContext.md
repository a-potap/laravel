# Technical Context

## Technology Stack

### Backend Framework
- **Laravel 10** (PHP 8.1+) - Full-stack PHP framework with robust ecosystem
- **PHP 8.1+** - Modern PHP version with improved performance and features

### API Infrastructure
- **REST API** - Traditional HTTP API with Laravel Sanctum authentication
- **GraphQL API** - Modern querying interface powered by Lighthouse package
- **Swagger/OpenAPI Documentation** - Auto-generated API documentation via l5-swagger

### Asynchronous Processing
- **RabbitMQ** - Message broker for queue-based processing of comments and notifications
- **Laravel Queue** - Integration with RabbitMQ using vladimir-yuldashev/laravel-queue-rabbitmq package

### Frontend Technologies
- **React** - Modern JavaScript framework for component-based UI
- **Apollo Client** - GraphQL client for React applications
- **Webpack Mix** - Asset compilation and build tooling

### Security & Authentication
- **Laravel Sanctum** - API authentication system for stateless token-based authentication
- **Mews Captcha** - CAPTCHA protection for forms and comment submission

### Development Tools
- **PHPUnit** - Testing framework for unit and feature testing
- **Composer** - PHP dependency management
- **NPM/Yarn** - Frontend dependency management

### Database & Storage
- **Database** - Configurable database connections (MySQL/PostgreSQL)
- **File Storage** - Laravel filesystem integration

## Development Environment

### Local Development
- Development server running on http://a-potap.local
- PHP artisan commands for development tasks
- Webpack Mix for frontend asset compilation

### Testing Configuration
- Default queue connection in tests is `sync` for easy testing
- PHPUnit test suites for unit and feature testing

### Production Considerations
- Supervisor daemon management for queue workers
- Proper environment variable configuration
- Security best practices for production deployment