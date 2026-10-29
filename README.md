# MoraBlog 

A simple, responsive blog web application built with **HTML, CSS, JavaScript, PHP, and MySQL**. MoraBlog allows users to register, log in, and create and manage their own blog posts through an easy-to-use interface.

🌐 **Live Demo:** [MoraBlog](https://morablog.infinityfree.me)  
💻 **GitHub Repository:** [Shanipaba03/morablog](https://github.com/Shanipaba03/morablog)

## ✨ Features

- **User Registration** — Create an account to access the platform.
- **User Authentication** — Log in and access features available to authenticated users.
- **Create Blog Posts** — Publish posts with a title and content.
- **Edit Blog Posts** — Update your own published posts.
- **Delete Blog Posts** — Remove your own posts.
- **Post Ownership** — Restricts post editing and deletion based on ownership.
- **Responsive Interface** — Access the application through different screen sizes.
- **Database Integration** — Store and manage blog posts and user-related data using MySQL.

## 🛠️ Tech Stack

| Technology | Purpose |
|---|---|
| HTML5 | Page structure |
| CSS3 | Styling and responsive layouts |
| JavaScript | Client-side interactions |
| PHP | Server-side logic and application functionality |
| MySQL | Database management |
| Git & GitHub | Version control and source code management |

## 🚀 Getting Started

### Prerequisites

- [XAMPP](https://www.apachefriends.org/) or another compatible PHP and MySQL environment
- A web browser
- Git (optional, for cloning the repository)

### Installation

1. **Clone the repository**

   ```bash
   git clone https://github.com/Shanipaba03/morablog.git
   ```

2. **Move the project into your web server directory**

   For XAMPP, place the project folder inside:

   ```text
   C:\xampp\htdocs\
   ```

3. **Start the required services**

   Open the XAMPP Control Panel and start Apache and MySQL.

4. **Configure the database**

   Create and configure a MySQL database with the tables required by the application.

5. **Configure the application**

   Create your local `config.php` using `config.example.php` as a reference. Add your own database connection details to the local configuration file.

   **Important:** Never commit real database credentials to GitHub.

6. **Run the application**

   Open the following URL in your browser, replacing `morablog` if you used a different project folder name:

   ```text
   http://localhost/morablog/
   ```

## 📂 Project Structure

```text
morablog/
├── config.example.php  # Example configuration
├── db.php              # Database connection logic
├── index.php           # Main blog page
├── register.php        # User registration
├── login.php           # User login
├── logout.php          # User logout
├── create.php          # Create a blog post
├── view.php            # View a blog post
├── edit.php            # Edit a blog post
├── delete.php          # Delete a blog post
├── style.css           # Styles and layout
├── .gitignore          # Git ignore rules
└── README.md           # Project documentation
```

## 🎯 Project Objectives

- Develop a functional web application using PHP and MySQL.
- Implement user authentication and account-based post management.
- Apply CRUD (Create, Read, Update, Delete) operations.
- Practise database integration and server-side programming.
- Build experience in web application development and version control.


---

*Built as a web development project to practise full-stack web application fundamentals.*

