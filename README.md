 **GSA Stores Management System (gsa_sms)**:

---

```markdown
# GSA Stores Management System (gsa_sms)

The **GSA Stores Management System (gsa_sms)** is a web-based inventory and store management platform designed for the **Ghana Science Association (GSA)**. It efficiently manages store operations by tracking available items, received stock, and issued items. The system helps streamline inventory activities and ensures transparency and accountability in store transactions.

## 🔧 Key Features

- ✅ **Item Registration**: Add and manage store items with details like item name, category, unit, quantity, etc.
- 📥 **Store Receipts**: Log all received items with supplier details and receipt numbers.
- 📤 **Item Issues**: Record issued items with details of the requesting department or personnel.
- 📊 **Real-time Stock Monitoring**: Track current stock levels and view stock balances at any time.
- 🔔 **Notification System**: Real-time alerts for new item requests or low stock levels (AJAX-based).
- 🔐 **User Roles and Access**:
  - **Admin**: Full access to all modules.
  - **Storekeeper**: Handles item entries and updates.
  - **Viewer/Requester**: Submits item requisitions and views status.
- 🧾 **Reports and Printouts**: Generate printable store receipts and issue vouchers.

## 🗂️ Modules

1. **Item Management**
2. **Store Receipt Management**
3. **Item Issue Management**
4. **User Authentication & Roles**
5. **Notifications System**
6. **Reports and Export Functions**

## 🛠️ Technologies Used

- **Frontend**: HTML, CSS, JavaScript
- **Backend**: PHP (OOP)
- **Database**: phpMyAdmin
- **Admin Template**: Developed from scratch

## ⚙️ Installation & Setup

1. Clone the repository:

   ```bash
   git clone https://github.com/yourusername/gsa_sms.git
   ```

2. Import the `gsa_sms.sql` file into your MySQL database.

3. Configure database connection in `config/db.php`:

   ```php
   $host = 'localhost';
   $user = 'root'; // Change this if using a different DB user
   $password = ''; // Set your MySQL password if applicable
   $database = 'gsa_store_management';
   ```

4. Set file permissions (if needed) and start your local server (e.g., XAMPP, WAMP, or live server).

5. Login credentials (demo):

   - **Admin/Storekeeper**:  
     - Username: `dan`  
     - Password: `12345`

   - **Viewer**:  
     - Username: `dee`  
     - Password: `acbd`

## 📌 File Structure

```bash
gsa_sms/
│
├── config/            # Database and configuration files
├── includes/          # Shared PHP files (functions, sessions)
├── pages/             # Modules: store receipts, issues, reports, etc.
├── assets/            # CSS, JS, images
├── header.php         # Top navigation (includes notifications)
├── index.php          # Login screen
├── dashboard.php      # Admin and storekeeper dashboard
└── README.md          # Project overview
```

## 📩 Notifications System

The system uses AJAX to poll new notifications in real-time:
- Notifications appear in the header dropdown.
- Admin and Storekeeper see identical updates.
- Notifications can be dismissed.
- A separate `notifications` table logs all updates triggered by store activities.

## 🔐 User Roles

| Role         | Capabilities                                             |
|--------------|----------------------------------------------------------|
| Admin        | Full system control, user management, report generation |
| Storekeeper  | Item entry, stock tracking, issue/receipt processing     |
| Viewer       | Submit requisitions, track status                        |

## 🧪 Future Improvements

- PDF export for reports
- Email alerts for critical notifications
- Advanced analytics for usage trends
- Role-based dashboard enhancements

## 👨🏽‍💻 Author

**Daniel Kojo Gidi**  
*Digital, Media & Online Advertising Manager – Mica Ghana Ltd*  
*Developer & Systems Designer for GSA Projects*

## 📄 License

This project is open-source and free to use for educational and organizational purposes under the [MIT License](LICENSE).

---

> For questions, contributions, or improvements, feel free to open an issue or fork the repo. Let's improve store management together for scientific excellence!

```

---

Let me know if you’d like me to add the actual SQL dump (`gsa_store_management.sql`), a sample login page preview, or help upload this to GitHub.
