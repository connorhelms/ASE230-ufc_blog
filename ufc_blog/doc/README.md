# UFC Blog

A dynamic PHP-based blog system focused on UFC content, allowing users to share and discuss UFC events and fighters.

## Test User Credentials
- Username: helmscd
- Password: helmscd17
- Role: Member
- Features available:
  - Create posts
  - Like and comment
  - Edit own posts
  - View all content

## Admin Credentials
- Username: yamos17
- Password: Yamos#17
- Role: Administrator
- Features available:
  - All member features
  - Add/edit/delete fighters
  - Add/edit/delete events
  - Manage all posts
  - Manage users
  - Access admin dashboard

## Project Demo Video
[UFC Blog Demo Video] https://youtu.be/WaJFwFnlDU8 
- Duration: 10 min
- Demonstrates core functionality
- Shows admin and user features

## Features

- **User Authentication**
  - Member and Admin roles
  - Login/Register functionality
  - Role-based permissions

- **Posts System**
  - Create, edit, and delete posts
  - Image upload support
  - Like and comment functionality
  - Category system (Events/Fighters)

- **Fighters Section**
  - Fighter profiles with images
  - Weight class categorization
  - Record tracking
  - Fighter-related posts

- **Events Section**
  - Upcoming and past events
  - Event details and locations
  - Event-related posts
  - Event images

- **Admin Dashboard**
  - User management
  - Content moderation
  - Fighter/Event management
  - Post management

## Installation

1. **Requirements**
   - XAMPP (or similar) with PHP 7.4+ and MySQL
   - Web server with .htaccess support

2. **Database Setup**
   ```sql
   CREATE DATABASE ufc_blog;
   ```
   - Import the provided `ufc_blog.sql` file

3. **Project Setup**
   ```bash
   # Clone into xampp/htdocs
   cd C:/xampp/htdocs
   git clone [repository-url] ufc_blog
   
   # Set permissions for upload directories
   chmod 777 data/posts
   chmod 777 data/fighters
   chmod 777 data/events
   ```

4. **Configuration**
   - Update database credentials in `lib/db.php`
   - Ensure proper file permissions for upload directories

## Default Admin Account
- Username: admin
- Password: admin123

## Project Structure
```
ufc_blog/
├── admin/          # Admin management interfaces
├── auth/           # Authentication files
├── data/           # Uploaded files
├── events/         # Event pages
├── fighters/       # Fighter pages
├── lib/            # Core libraries
├── posts/          # Post management
└── theme/          # Templates and assets
```

## Core Features

### Posts
- Create/Edit/Delete posts
- Image uploads
- Likes and comments
- Category tagging

### Fighters
- Fighter profiles
- Weight class organization
- Record tracking
- Related posts

### Events
- Event scheduling
- Location details
- Event coverage
- Image galleries

### User Roles
- **Visitors:** Read-only access
- **Members:** Can create posts, comment, and like
- **Admins:** Full site management

## Security Features
- Password hashing
- SQL injection prevention
- XSS protection
- CSRF protection
- File upload validation

## License
[Your License Choice]

## Credits
Built by [Your Name/Team]

## Support
For support, please [contact details]
