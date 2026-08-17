# System Patterns

## Architectural Patterns

### MVC Architecture
- Follows Laravel's standard Model-View-Controller pattern
- Clear separation of business logic, presentation, and data layers
- Controllers handle HTTP requests and coordinate between models and views

### Queue Processing Pipeline
The comment system implements a sophisticated multi-stage queue processing pattern:

1. **Comment Submission** → dispatches `ValidateComment` job to `validation` queue
2. **Validation Stage** (`potap.comments.validate` queue) → validates comment, creates record, dispatches `CommentNotify` to `notify` routing key
3. **Notification Routing** → `potap.comment.ex` (direct exchange) routes to `potap.notify.ex` (fanout exchange)
4. **Parallel Notifications** → fanout to both:
   - `potap.comments.notify_mail` queue → sends email
   - `potap.comments.notify_tel` queue → logs telegram notification

### API Design Patterns
- **REST API**: Standard HTTP verbs with proper status codes, Sanitization through request validation
- **GraphQL API**: Lighthouse directives (`@find`, `@paginate`, `@all`, `@hasMany`, `@belongsTo`) for flexible data fetching
- **Authentication**: Laravel Sanctum for token-based authentication

## Data Models & Relationships

### Core Models
- **Blog**: Has many Comments
- **Comment**: Belongs to Blog
- **News**: Standalone news items
- **Photo**: Photo gallery with media files
- **User**: Authentication and permissions
- **ChatRoom**: Chat rooms (private/groups)
- **ChatMessage**: Individual messages
- **ChatReadReceipt**: Read status tracking

### Repository Pattern Implementation
- Models are used as data containers with Eloquent relationships
- Business logic separated into Jobs, Services, and Repositories where appropriate

## Request Flow Patterns

### Web Routes
- Locale middleware for bilingual support
- `/` route serves welcome page
- REST routes under `/api/` for content endpoints
- GraphQL endpoint at `/graphql`
- Web routes for admin/user interfaces

### API Request Handling
- Validation middleware for REST endpoints
- Throttling middleware for comment submission (`throttle:comments`)
- Authentication via Laravel Sanctum tokens
- Error handling with proper HTTP status codes

### Queue Job Pattern
- Jobs dispatched asynchronously for background processing
- Jobs configured with queue names and retry logic
- Notification jobs dynamically determine behavior based on queue context