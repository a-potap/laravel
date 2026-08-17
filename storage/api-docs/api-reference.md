# API Integration Reference

## Authentication
All authenticated requests require the following header:
`Authorization: Bearer <token>`

Tokens are obtained via the Login or Register endpoints.

## Base URL
`http://a-potap.local`

---

## Authentication Endpoints

### 1. Register User
- **Method/Route:** `POST /auth/register`
- **Auth Required:** No
- **Body:**
  ```json
  {
    "name": "string (required, max 255)",
    "email": "string (required, email, unique)",
    "password": "string (required, min 8, confirmed)",
    "password_confirmation": "string (required)"
  }
  ```
- **Response:** Returns user resource and token.

### 2. Login
- **Method/Route:** `POST /auth/login`
- **Auth Required:** No
- **Body:**
  ```json
  {
    "email": "string (required)",
    "password": "string (required)"
  }
  ```
- **Response:** Returns user resource and token.

### 3. Logout
- **Method/Route:** `POST /auth/logout`
- **Auth Required:** Yes (`auth:sanctum`)
- **Response:** Success message.

### 4. Get Current User
- **Method/Route:** `GET /auth/me`
- **Auth Required:** Yes (`auth:sanctum`)
- **Response:** Returns authenticated user resource.

---

## Chat Endpoints
*All chat endpoints require authentication (`auth:sanctum`).*

### 5. List Chat Rooms
- **Method/Route:** `GET /chat/rooms`
- **Query Params:** `page` (integer, optional)
- **Response:** Paginated collection of chat rooms.

### 6. Create Chat Room
- **Method/Route:** `POST /chat/rooms`
- **Body:**
  ```json
  {
    "name": "string (required)",
    "description": "string (optional, max 500)",
    "participant_ids": "array of user IDs (required)"
  }
  ```
- **Response:** Created chat room resource (201).

### 7. Get Chat Room
- **Method/Route:** `GET /chat/rooms/{id}`
- **Response:** Chat room resource with participants.

### 8. Get or Create Private Chat
- **Method/Route:** `POST /chat/rooms/private`
- **Body:**
  ```json
  {
    "recipient_id": "integer (required, must exist, cannot be own ID)"
  }
  ```
- **Response:** Private chat room resource.

### 9. Add Participants
- **Method/Route:** `POST /chat/rooms/{id}/participants`
- **Body:**
  ```json
  {
    "user_ids": "array of user IDs (required)"
  }
  ```
- **Response:** Success message.

### 10. Remove Participant
- **Method/Route:** `DELETE /chat/rooms/{id}/participants/{userId}`
- **Response:** Success message.

### 11. Mark Messages as Read
- **Method/Route:** `POST /chat/rooms/{id}/read`
- **Response:** Success message.

### 12. List Messages
- **Method/Route:** `GET /chat/rooms/{roomId}/messages`
- **Query Params:** `page` (integer, optional)
- **Response:** Paginated collection of messages (newest first).

### 13. Send Message
- **Method/Route:** `POST /chat/rooms/{roomId}/messages`
- **Body:**
  ```json
  {
    "message": "string (required, max 5000)"
  }
  ```
- **Response:** Created message resource (201). Throttled.

---

## News Endpoints

### 14. List News
- **Method/Route:** `GET /news`
- **Auth Required:** No
- **Query Params:** `page` (integer, optional)
- **Response:** Paginated collection of news.

### 15. Get News
- **Method/Route:** `GET /news/{id}`
- **Auth Required:** No
- **Response:** News resource. Returns 404 if not found.

---

## Blog Endpoints

### 16. List Blog Posts
- **Method/Route:** `GET /blog`
- **Auth Required:** No
- **Query Params:** `page` (integer, optional)
- **Response:** Paginated collection of blog posts.

### 17. Get Blog Post
- **Method/Route:** `GET /blog/{id}`
- **Auth Required:** No
- **Response:** Blog post resource with extra data. Returns 404 if not found.

---

## Photo Endpoints

### 18. List Photo Albums
- **Method/Route:** `GET /photos`
- **Auth Required:** No
- **Query Params:** `page` (integer, optional)
- **Response:** Paginated collection of photo albums.

### 19. Get Photo Album
- **Method/Route:** `GET /photos/{id}`
- **Auth Required:** No
- **Response:** Photo album resource with photos list. Returns 404 if not found.

---

## Comment Endpoints

### 20. List Blog Comments
- **Method/Route:** `GET /blog/{id}/comments`
- **Auth Required:** No
- **Query Params:** `page` (integer, optional)
- **Response:** Paginated collection of comments.

### 21. Post Comment
- **Method/Route:** `POST /blog/{blog}/comments`
- **Auth Required:** No
- **Body:**
  ```json
  {
    "iduser": "string (required, user name or nickname)",
    "text": "string (required, comment text)"
  }
  ```
- **Response:** Created comment resource (201). Throttled.

---

## Error Responses

- **400** - Bad Request (validation errors)
- **401** - Unauthenticated
- **403** - Forbidden
- **404** - Resource Not Found
- **429** - Too Many Requests (throttled endpoints)

