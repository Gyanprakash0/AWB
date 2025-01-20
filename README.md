AWB - Travel Platform for Solo Travelers
AWB is a feature-rich travel platform designed for solo travelers, offering a range of tools to enhance the travel experience. The platform includes features like Live Chat, Profile Matching, Meeting Panel, Integrated Trip Booking with a Payment Gateway, Cloud Gallery for photo sharing, Real-time Messaging, Group Creation, Collaborative Travel Planning, Personal Expense Management, SOS Alerts, and much more.

Features
Live Chat: Instant communication between users for sharing travel plans and information.
Profile Matching: Matches users with similar interests or travel preferences.
Meeting Panel: Organize meetings and meetups for solo travelers.
Integrated Trip Booking: Allows users to book trips, including accommodations and transportation.
Payment Gateway: Secure transactions for booking and purchases.
Email Alerts: Notifications regarding trip bookings, messages, and updates.
Cloud Gallery: A cloud-based gallery to share photos with fellow travelers.
Group Creation & Real-Time Messaging: Users can create groups and engage in real-time discussions.
Collaborative Travel Planning Tools: Plan trips together with fellow solo travelers.
Personal Expense Management: Track and manage personal travel expenses.
SOS Alerts: Alerts in case of emergencies to inform contacts and the platform.
User-Generated Travel Feeds and Reels: Users can share their travel experiences via posts and videos.
Benefits
For Solo Travelers: Provides a safe, collaborative space to find travel companions, organize meetups, and get personalized recommendations.
For Travel Groups: Enables seamless group creation and collaborative travel planning.
For Booking and Payments: Integrated booking systems and secure payment gateways make planning and payment hassle-free.
For Photo Sharing: Cloud gallery allows users to securely share photos with the community.
For Emergency Alerts: SOS feature ensures safety by allowing users to send emergency alerts to contacts.
For Managing Finances: Helps users manage their travel expenses and stick to a budget.
Technologies Used
Frontend: HTML, CSS, JavaScript, Bootstrap
Backend: PHP, MySQL
Real-Time Messaging: WebSockets
Payment Gateway: Stripe / PayPal (depending on integration)
Cloud Services: Amazon S3 or Firebase for cloud storage
Mobile App Development: React Native / Flutter (if applicable)
Version Control: Git
Installation Guide
Prerequisites
XAMPP (Apache, MySQL, PHP) to run the backend locally.
PHP installed with the necessary extensions enabled.
1. Install XAMPP
Download XAMPP from here.
Install XAMPP on your system.
Start Apache and MySQL from the XAMPP control panel.
2. Clone the Repository
Clone the repository to your local machine using Git:

bash
Copy
git clone https://github.com/yourusername/AWB.git
Alternatively, download the ZIP file from the GitHub repository and extract it.

3. Configure the Database
Open phpMyAdmin (accessible via http://localhost/phpmyadmin/).
Create a new database named awb_db.
Import the provided awb_db.sql file from the database folder (if included) into the awb_db database.
4. Configure the Backend
Go to the folder where you have cloned/extracted the project (AWB directory).
Open the config.php file and configure the database connection:
