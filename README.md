# 🥗 Food Waste Management System

This is **my final year project**, built as a complete web-based solution to reduce food wastage and help feed the needy. It creates a platform that connects food donors, NGOs, and delivery personnel for efficient collection and distribution of excess food.

The system is developed using **PHP**, **HTML/CSS/JavaScript**, and **MySQL**, and includes features like donation tracking, user management, delivery coordination, and a chatbot for support.

---

## 🚀 Features

- 🥘 **Food Donation**: Donors can fill out forms to register excess food for donation.
- 🚚 **Delivery Assignment**: Admin can assign delivery personnel to collect and distribute food.
- 📊 **Analytics**: Admin dashboard displays donation analytics and trends.
- 💬 **Chatbot Integration**: Users can interact with a chatbot for quick assistance.
- 👤 **Role-based Access**:
  - **Donor**
  - **Delivery Personnel**
  - **Admin**
- 🛡️ **Authentication & Profiles**: Secure login/signup and profile management for all users.

---

## 🗂️ Folder Structure

```
food-waste-management-system-main/
│
├── admin/               → Admin dashboard, analytics, login, etc.
├── delivery/            → Delivery personnel module
├── chatbot/             → Chatbot UI and scripts
├── img/                 → Project images and illustrations
├── database/            → SQL file (demo.sql)
├── index.html           → Landing page
├── login.php            → Common login
├── signup.php           → Common signup
├── connection.php       → Database connection file
```

---

## 🛠️ Tech Stack

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP
- **Database**: MySQL
- **Styling**: Custom CSS
- **Bot Integration**: JavaScript-based chatbot

---

## 🧪 How to Run

1. 🖥️ Install [XAMPP](https://www.apachefriends.org/) or any PHP server environment.
2. 📁 Place the project folder in `htdocs` (e.g., `C:\xampp\htdocs\`).
3. 🧩 Import the `database/demo.sql` file in **phpMyAdmin** to create the required database.
4. 🔌 Start Apache and MySQL from the XAMPP control panel.
5. 🌐 Open your browser and go to:
   ```
   http://localhost/food-waste-management-system-main/index.html
   ```

---

## 📸 Screenshots

> *(Include relevant screenshots of UI like dashboard, chatbot, donation form, etc.)*

---

## 📄 Important Files

- `connection.php`: Database connection logic
- `admin/analytics.php`: Admin stats and charts
- `delivery/deliverylogin.php`: Login for delivery personnel
- `chatbot/chatbot.js`: Chatbot script
- `database/demo.sql`: SQL dump of the project

---

## ⚠️ Notes

- This project is built for academic purposes.
- Make sure Apache and MySQL are both running before use.
- Ensure your PHP version is compatible (7.x or above recommended).
- Tested on Chrome and Firefox.

---


> 🌟 *This project was developed as part of my final year in Computer Science . It aims to contribute a small step towards solving real-world issues of food wastage and hunger.*

