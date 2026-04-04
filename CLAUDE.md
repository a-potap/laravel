# CLAUDE.md

This file provides guidance to Claude Code (claude.ai/code) when working with code in this repository.

## Project Overview

Laravel 10 application with GraphQL API (Lighthouse), REST API, and RabbitMQ queue integration. The app manages blogs, news, photos, and comments with asynchronous comment processing.

## Development Commands

### Testing
```bash
# Run all tests
php artisan test

# Run specific test suite
php artisan test --testsuite=Unit
php artisan test --testsuite=Feature

# Run single test file
php artisan test tests/Unit/ExampleTest.php
```

### Dependencies
```bash
# Install PHP dependencies
composer install

# Install frontend dependencies
npm install

# Build frontend assets
npm run dev          # Development build
npm run watch        # Watch for changes
npm run production   # Production build
```

### Database
```bash
# Run migrations
php artisan migrate

# Rollback migrations
php artisan migrate:rollback

# Fresh migration with seeding
php artisan migrate:fresh --seed
```

### RabbitMQ Setup
```bash
# Create MQ exchange scheme (one-time command)
php artisan app:one-time-create-m-q-exchange-scheme

# Run queue workers (should be managed by supervisor in production)
php artisan queue:work --queue=potap.comments.validate
php artisan queue:work --queue=potap.comments.notify_mail
php artisan queue:work --queue=potap.comments.notify_tel
```

### Development Server

is running locally on http://a-potap.local 

## Architecture

### Queue System Architecture

The application uses RabbitMQ for asynchronous comment processing with a multi-stage pipeline:

1. **Comment Submission** → dispatches `ValidateComment` job to `validation` queue
2. **Validation** (`potap.comments.validate` queue) → validates comment, creates record, dispatches `CommentNotify` to `notify` routing key
3. **Notification Routing** → `potap.comment.ex` (direct exchange) routes to `potap.notify.ex` (fanout exchange)
4. **Parallel Notifications** → fanout to both:
   - `potap.comments.notify_mail` queue → sends email
   - `potap.comments.notify_tel` queue → logs telegram notification

**Important:** The `CommentNotify` job determines behavior based on `$this->job->getQueue()` at runtime, not the queue name set in constructor.

### GraphQL API (Lighthouse)

- Schema: `graphql/schema.graphql`
- Endpoint: `/graphql`
- Resources: Blog, Comment, News, Photo
- Uses Lighthouse directives (`@find`, `@paginate`, `@all`, `@hasMany`, `@belongsTo`)

### REST API

Routes in `routes/api.php`:
- `GET /api/news` - list news
- `GET /api/blog` - list blogs  
- `GET /api/photos` - list photos
- `GET /api/blog/{id}/comments` - get blog comments
- `POST /api/blog/{blog}/comments` - create comment (throttled with `throttle:comments` middleware)

### Models

- `Blog` - has many Comments
- `Comment` - belongs to Blog
- `News` - standalone news items
- `Photo` - photo gallery with files

### Queue Configuration

Environment variables for RabbitMQ (see `.env.example`):
- `QUEUE_CONNECTION=rabbitmq` (set after running MQ setup command)
- `MQ_HOST`, `MQ_PORT`, `MQ_USER`, `MQ_PASS`, `MQ_VHOST`
- `MQ_EXCHANGE=potap.comment.ex`
- `MQ_EXCHANGE_TYPE=direct`

Default queue connection in tests is `sync` (see `phpunit.xml`).

## Technology Stack

- Laravel 10 (PHP 8.1+)
- GraphQL via Lighthouse (`nuwave/lighthouse`)
- REST API with Swagger docs (`darkaonline/l5-swagger`)
- RabbitMQ queues (`vladimir-yuldashev/laravel-queue-rabbitmq`)
- Laravel Sanctum (API authentication)
- React frontend with Apollo Client
- PHPUnit for testing
