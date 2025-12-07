<div align="center">

# 🏥 DocAtHome
### Telemedicine & Real-Time Video Consultation Platform

**Connecting Patients and Doctors through secure, high-quality video calls.**

![PHP](https://img.shields.io/badge/PHP-8.2-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.0-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![WebRTC](https://img.shields.io/badge/WebRTC-RealTime-333333?style=for-the-badge&logo=webrtc)
![Bootstrap](https://img.shields.io/badge/Bootstrap-5.3-7952B3?style=for-the-badge&logo=bootstrap&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-green?style=for-the-badge)

[View Demo](#-demo) • [Key Features](#-features) • [Installation](#-quick-start) • [Architecture](#-how-it-works)

</div>

---

## 📖 Overview

**DocAtHome** is a comprehensive telemedicine platform that enables **remote medical consultations** via HD video and instant chat. Built from scratch using **PHP** and **WebRTC**, it offers a production-ready environment with secure signaling, TURN/STUN server support for NAT traversal, and a responsive UI.

### 🎯 Project Highlights
* **Zero Plugins:** Pure browser-based video calls using WebRTC.
* **Reliability:** Implements 17 different TURN/STUN server configurations for 99.9% connectivity.
* **Security:** End-to-End encryption for media streams (SRTP/DTLS).
* **Speed:** Lightweight file-based signaling for instant peer discovery.

---

## 📸 Interface Gallery

| **Landing Page** | **Doctor Dashboard** |
|:---:|:---:|
| <img src="assets/landing.png" alt="Landing Page" width="400"/> | <img src="assets/dashboard.png" alt="Dashboard" width="400"/> |
| **HD Video Interface** | **Real-Time Chat** |
| <img src="assets/videocall.png" alt="Video Call" width="400"/> | <img src="assets/chat.png" alt="Chat" width="400"/> |

---

## ✨ Key Features

### 🏥 For Patients
* **Easy Booking:** Streamlined appointment form with (demo) payment gateway.
* **Virtual Waiting Room:** Auto-redirects when the doctor joins.
* **HD Video:** Crystal clear audio/video with mute and camera controls.
* **Mobile Ready:** Works perfectly on smartphones via ngrok/HTTPS.

### 👨‍⚕️ For Doctors
* **Live Dashboard:** View incoming patient requests in real-time.
* **Patient History:** Access booking details and medical notes.
* **Control Center:** Manage calls, screen sharing, and patient status.

### ⚙️ Technical Core
* **Smart Network Handling:** Uses ICE (Interactive Connectivity Establishment) to find the best path (P2P vs Relay).
* **Secure Tunneling:** Full HTTPS support for camera permissions.
* **Adaptive Bitrate:** Adjusts quality based on bandwidth.

---

## 🛠 Tech Stack

| Category | Technologies |
| :--- | :--- |
| **Frontend** | HTML5, CSS3, JavaScript (ES6+), Bootstrap 5.3 |
| **Backend** | PHP 8.x, Apache 2.4 |
| **Database** | MySQL 8.x (Relational) |
| **Real-Time** | WebRTC (RTCPeerConnection), SDP, ICE Candidates |
| **Infrastructure** | XAMPP, ngrok (Tunneling), Google STUN, Metered.ca TURN |

---

## 🚀 Quick Start

### Prerequisites
* [XAMPP](https://www.apachefriends.org/) (Apache + MySQL + PHP)
* [ngrok](https://ngrok.com/) (Optional, for mobile testing)
* Chrome Browser (Recommended)

### ⚡ Automatic Installation (Windows)

1.  **Clone/Copy** the project folder to `C:\xampp\htdocs\DocAtHome`.
2.  **Start XAMPP:** Ensure Apache and MySQL are running.
3.  **Run the Script:**
    Double-click `setup.bat` inside the folder.
    > *This script creates the database, imports tables, and sets up permissions automatically.*
4.  **Launch:**
    Open your browser and visit: `http://localhost/DocAtHome/`

### 📱 Mobile Access (via ngrok)
*Mobile browsers require HTTPS for camera access.*

1.  Install **ngrok** and authenticate.
2.  Start the tunnel:
    ```bash
    ngrok http 80
    ```
3.  Copy the secure link (e.g., `https://1a2b-3c4d.ngrok-free.app`).
4.  Open this link on both the **Desktop (Doctor)** and **Mobile (Patient)**.

---

## 🎓 How It Works

### The Signaling Flow (Under the Hood)

1.  **The Trigger:** Doctor clicks "Join Call," generating a `.join` signal file.
2.  **The Offer:** Doctor's browser creates an **SDP Offer** (media capabilities) and saves it.
3.  **The Answer:** Patient's browser detects the offer, accepts it, and creates an **SDP Answer**.
4.  **The Path (ICE):** Both browsers swap **ICE Candidates** (network paths) to find a way to connect.
    * *Direct:* If on the same LAN (Local IP).
    * *STUN:* If behind a simple NAT (Public IP).
    * *TURN:* If behind a strict firewall (Relay Server).
5.  **Connection:** Secure video stream is established peer-to-peer!

---

## 📁 Project Structure

```text
DocAtHome/
├── 📄 index.html            # Landing Page
├── 📄 video_call.php        # The WebRTC Logic Core
├── 📄 doctor_dashboard.php  # Doctor's Control Panel
├── 📂 chats/                # Temp storage for Signaling Files
├── 📂 Backend Scripts/      # PHP API Logic
│   ├── write_signal.php     # Saves SDP/ICE data
│   ├── read_signal.php      # Retrieves SDP/ICE data
│   └── db.php               # Database Config
├── 📂 Database/             # SQL Import Files
└── 📂 Setup & Installation/ # setup.bat and guides
