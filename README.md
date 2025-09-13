# 📚 Virtual Library – Project Overview

-- Purpose
A web-based platform where users can:
Register & log in
Upload their own books (PDFs)
Browse/search books by title, author, or genre
Read books online using a PDF viewer
Download books
Maintain a Reading List (books they want to read later)
Save Bookmarks (specific places/pages inside books)


-- Core Features

🔐 Authentication
Register / Login / Logout using secure password hashing (password_hash, password_verify).
Session-based authentication.

📖 Book Management
Users can upload books (title, author, genre, PDF file).
Dashboard shows books uploaded by the logged-in user.
General catalog (index.php) lists all available books with search and category filters.

📚 Reading System
Book Viewer (book_viewer.php):
Displays book details (title, author).
Embeds PDF in an <iframe> or PDF.js viewer.
Allows adding to Reading List or Bookmarking.

📝 Reading List
User can add/remove books to/from their reading list.
Shown on the sidebar of dashboard.php.

🔖 Bookmarks
User can save bookmarks for a book (with page number).
Displayed in the sidebar of dashboard.php.
Clicking takes them back to the book and page.

🏠 Dashboard (dashboard.php)
Shows:
Uploaded books
Featured catalog of recent books
Reading List
Bookmarks

🔍 Search & Categories
Search by title, author, genre.
Sidebar quick links (Science, History, Technology, Fiction, etc.).


-- Database Structure (example tables)

users
| id | name | email | password | is_admin | created_at |
books
| id | title | author | genre | filename | uploaded_by | uploaded_at |
reading_list
| id | user_id | book_id |
bookmarks
| id | user_id | book_id | page | created_at |


-- Tech Stack
Frontend: HTML, CSS (custom stylesheet), basic PHP templates.
Backend: PHP (procedural, mysqli with prepared statements).
Database: MySQL / MariaDB.

File Storage: books/ folder for uploaded PDFs.
