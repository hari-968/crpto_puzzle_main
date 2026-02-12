# 🧩 Crypto Puzzle

A web-based interactive quiz application focused on cryptography concepts. Users can test their knowledge, track scores, and compete. The system includes an administrative panel for user management.

## ✨ Features

- **User Authentication**: Secure login system with role-based access control (Admin/User).
- **Interactive Quiz**:
  - Timer-based questions.
  - Immediate feedback on answers.
  - Difficulty levels (Easy, Medium, Hard).
- **Score Tracking**: Real-time score updates and persistence.
- **Admin Panel**: Manage registered users and view their progress.
- **Responsive Design**: Works on desktop and mobile devices.

## 🛠️ Tech Stack

- **Frontend**: HTML5, CSS3, JavaScript (Vanilla).
- **Backend**: PHP.
- **Database**: MySQL.
- **Server**: Apache (XAMPP/WAMP recommended).

## 🚀 Setup & Installation

### Prerequisites
- [XAMPP](https://www.apachefriends.org/) or any PHP/MySQL local server environment.
- Any code editor (VS Code recommended).

### Steps

1.  **Clone or Download**
    - Clone this repository or download the ZIP and extract it.
    - Move the project folder to your server's root directory (e.g., `C:\xampp\htdocs\crypto-puzzle`).

2.  **Database Configuration**
    - Open **phpMyAdmin** (`http://localhost/phpmyadmin`).
    - Create a new database named `crypto_puzzle_db`.
    - Import the provided `db.sql` file located in the project root.

3.  **Connect Application**
    - Open `config.php` in your editor.
## Setup
1. Import `db.sql` into MySQL.
2. Update DB credentials in `config.php`.
3. Run the app at `http://localhost/crypto-puzzle-main/index.html`.

## Usage
Simply register or login to start playing the crypto puzzle.
Default sample user:
- `user` / `user123`

## 📂 Project Structure

```
crypto-puzzle/
├── config.php            # Database connection
├── get_questions.php     # API to fetch questions
├── index.html            # Main landing/login page
├── index.js              # Frontend logic
├── quiz.php              # Quiz interface
├── db.sql                # Database schema
└── ...
```

## 📄 License
This project is open-source and available under the [MIT License](LICENSE).
