# 🏥 DocAtHome - Telemedicine Platform

<div align="center">

![DocAtHome](https://img.shields.io/badge/DocAtHome-Telemedicine-blue?style=for-the-badge)
![WebRTC](https://img.shields.io/badge/WebRTC-Video_Calls-green?style=for-the-badge)
![PHP](https://img.shields.io/badge/PHP-8.x-777BB4?style=for-the-badge&logo=php&logoColor=white)
![MySQL](https://img.shields.io/badge/MySQL-8.x-4479A1?style=for-the-badge&logo=mysql&logoColor=white)
![License](https://img.shields.io/badge/License-MIT-yellow?style=for-the-badge)

**Bringing Healthcare to Your Fingertips** 🩺

[Features](#-features) • [Demo](#-demo) • [Installation](#-quick-start) • [Tech Stack](#️-tech-stack) • [Documentation](#-documentation)

</div>

---

## 📖 About

**DocAtHome** is a comprehensive telemedicine platform enabling patients to consult with doctors remotely through **real-time video calls** and **instant messaging**. Built with WebRTC technology, it provides secure, high-quality video consultations without requiring any third-party plugins.

### 🎯 Project Highlights

- 🎥 **Real-Time Video Calls** - Peer-to-peer WebRTC with TURN/STUN support
- 💬 **Live Chat System** - Instant messaging between doctors and patients
- 📱 **Mobile Responsive** - Works seamlessly on phones, tablets, and desktops
- 🔒 **Secure HTTPS** - ngrok integration for mobile camera access
- 🎨 **Modern UI/UX** - Beautiful gradients and smooth animations

---

## ✨ Features

### For Patients
- 🏠 **Easy Booking** - Simple form to book video or chat consultations
- ⏰ **Virtual Waiting Room** - Professional waiting experience with auto-redirect
- 📹 **HD Video Calls** - Crystal clear video and audio quality
- 💬 **Real-Time Chat** - Instant messaging with doctors
- 💳 **Payment Integration** - Secure payment gateway (demo)
- 📱 **Mobile Friendly** - Full camera/microphone support on mobile

### For Doctors
- 📊 **Dashboard** - View all patient bookings in real-time
- 👥 **Patient Management** - Track consultations and history
- 🎥 **Video Consultation** - Professional video call interface
- 💬 **Chat Interface** - Respond to patient messages instantly
- 📝 **Booking Details** - Access patient information and notes

### Technical Features
- 🌐 **WebRTC Technology** - Same tech as Google Meet & Zoom
- 🔄 **NAT Traversal** - Works across different networks using ICE
- 🛡️ **TURN/STUN Servers** - 17 different server configurations for 99.9% reliability
- 🔐 **End-to-End Encryption** - Secure SRTP/DTLS video and audio streams
- 📡 **File-Based Signaling** - Simple and effective peer discovery
- ⚡ **Adaptive Bitrate** - Automatic quality adjustment based on network

---

## 🎬 Demo

### Homepage
Beautiful landing page featuring 6 qualified doctors with their credentials:
- Dr. Aarav Mehta - MBBS, MD (General Medicine)
- Dr. Nisha Reddy - MBBS, DNB (Emergency Medicine)
- Dr. Karan Bhattacharya - MBBS, MD (Internal Medicine)
- Dr. Sneha Iyer - MBBS, MD (General Medicine)
- Dr. Rohan Pillai - MBBS, MD (Emergency Medicine)
- Dr. Priya Sharma - MBBS, MD (General Medicine)

### Video Call Interface
- Picture-in-picture local video preview
- Full-screen remote video display
- Control buttons: mic, camera, screen share, end call
- Real-time connection status with green indicator
- Live call timer and ping/latency monitor
- Professional gradient UI with smooth animations

### Chat System
- WhatsApp-like clean interface
- Real-time message delivery (1-second polling)
- Separate interfaces for doctors and patients
- Message timestamps and sender identification
- Works alongside video calls

---

## 🚀 Quick Start

### Prerequisites
- **XAMPP** (Apache + MySQL + PHP) - [Download](https://www.apachefriends.org/)
- **Web Browser** (Chrome recommended)
- **ngrok** (optional, for mobile HTTPS) - [Download](https://ngrok.com/download)

### ⚡ Automatic Installation (Recommended)

1. **Install XAMPP**
   ```bash
   Download from: https://www.apachefriends.org/
   Install to: C:\xampp (default)
   ```

2. **Start Services**
   - Open XAMPP Control Panel
   - Start **Apache**
   - Start **MySQL**

3. **Copy Project**
   ```bash
   Copy "Nigiri" folder to: C:\xampp\htdocs\
   ```

4. **Run Setup Script**
   ```bash
   Navigate to: C:\xampp\htdocs\Nigiri\
   Double-click: setup.bat
   Wait for: "SETUP COMPLETED SUCCESSFULLY!"
   ```

5. **Access Application**
   ```
   Open browser: http://localhost/Nigiri/
   ```

**That's it! 🎉** The setup script automatically:
- Creates the database
- Imports all tables
- Adds missing columns
- Creates necessary folders
- Tests the connection

### 📱 Mobile Setup (HTTPS via ngrok)

Mobile browsers require HTTPS for camera access:

1. **Download & Extract ngrok**
   ```bash
   https://ngrok.com/download
   ```

2. **Run ngrok**
   ```bash
   ngrok http 80
   ```

3. **Copy HTTPS URL**
   ```
   Example: https://abc123.ngrok-free.app
   ```

4. **Access from Mobile**
   ```
   https://abc123.ngrok-free.app/Nigiri/
   ```

---

## 🛠️ Tech Stack

### Frontend
- **HTML5** - Semantic markup and structure
- **CSS3** - Modern styling with gradients, flexbox, animations
- **JavaScript (ES6+)** - Client-side logic and WebRTC
- **Bootstrap 5.3** - Responsive UI framework
- **Bootstrap Icons** - Professional icon library

### Backend
- **PHP 8.x** - Server-side scripting and logic
- **MySQL 8.x** - Relational database management
- **Apache 2.4** - Web server (via XAMPP)
- **File System** - Signaling file storage

### Real-Time Communication
- **WebRTC** - Peer-to-peer video/audio streaming
- **STUN Servers** - Google STUN for NAT traversal
- **TURN Servers** - Multiple relay servers for reliability:
  - `openrelay.metered.ca` (ports 80, 443, UDP/TCP/TLS)
  - `a.relay.metered.ca` (ports 80, 443, UDP/TCP/TLS)
  - `numb.viagenie.ca` (port 3478, UDP/TCP)

### Development Tools
- **XAMPP** - Local development environment
- **ngrok** - Secure HTTPS tunneling
- **Git** - Version control
- **Chrome DevTools** - Debugging and testing

---

## 📁 Project Structure

```
Nigiri/
├── 📄 index.html                    # Homepage with doctor listings
├── 📄 booking_form.php              # Patient booking form
├── 📄 payment.php                   # Payment gateway (demo)
├── 📄 waiting.php                   # Virtual waiting room
├── 📄 video_call.php                # WebRTC video call interface
├── 📄 chat.php                      # Patient chat interface
├── 📄 doctor_chat.php               # Doctor chat interface
├── 📄 doctor_dashboard.php          # Doctor management panel
│
├── 🔧 Backend Scripts/
│   ├── db.php                       # Database connection config
│   ├── booking_handler.php          # Process booking submissions
│   ├── mark_ready.php               # Doctor ready signal
│   ├── mark_joined.php              # Doctor joined signal
│   ├── write_signal.php             # WebRTC signaling (write)
│   ├── read_signal.php              # WebRTC signaling (read)
│   ├── send_message.php             # Send chat messages
│   ├── get_messages.php             # Retrieve chat messages
│   └── mark_complete.php            # Mark consultation complete
│
├── 💾 Database/
│   ├── docathome.sql                # Original database schema
│   └── docathome_complete.sql       # Complete schema with all columns
│
├── 🚀 Setup & Installation/
│   ├── setup.bat                    # Automatic setup script (Windows)
│   ├── INSTALLATION_GUIDE.txt       # Detailed installation instructions
│   └── .gitignore                   # Git ignore rules
│
├── 📚 Documentation/
│   ├── README.md                    # This file
│   ├── PRESENTATION_STORY.txt       # Real-life use case scenarios
│   ├── TECHNICAL_DOCUMENTATION.txt  # Complete technical details
│   └── TECHNICAL_STORY.txt          # Technical explanation (story format)
│
└── 📁 chats/                        # Runtime signaling & chat files
    └── .gitkeep                     # Preserves folder in git
```

---

## 🎓 How It Works

### The Complete Journey

**1. Patient Books Consultation**
- Visits homepage and browses available doctors
- Clicks "Book Now" and fills the booking form
- Selects consultation type (Video Call or Chat)
- Completes payment (demo gateway)
- Enters virtual waiting room

**2. Doctor Receives Notification**
- Opens doctor dashboard
- Sees new patient booking with details
- Reviews patient information and notes
- Clicks "Join Call" to start consultation

**3. Automatic Connection Setup**
- Doctor joining creates `.join` signal file
- Patient's waiting room polls for this file
- Patient automatically redirects to video call page
- Both sides request camera/microphone permissions

**4. WebRTC Magic Happens**
- Both browsers create `RTCPeerConnection` objects
- Doctor creates SDP Offer (session description)
- Patient receives offer and creates SDP Answer
- Both exchange ICE candidates via file signaling
- Browsers test all possible connection paths
- Best path selected (direct or via TURN relay)
- Peer-to-peer connection established!

**5. Video Call Active**
- Encrypted video/audio streams flow at 30 FPS
- Users can mute/unmute microphone
- Users can turn camera on/off
- Screen sharing available
- Real-time connection monitoring
- Call timer and ping indicator

**6. Parallel Chat System**
- Messages stored in MySQL database
- Polling every 1 second for new messages
- Works alongside video call
- Message history preserved

### Why TURN Servers Are Essential

When using ngrok, both devices appear on different networks even if they're on the same WiFi. This is because:

```
Doctor's View: Public IP via ngrok tunnel
Patient's View: Public IP via ngrok tunnel
Result: Browsers think they're on different networks!
```

**Solution:** TURN servers relay the traffic

```
Doctor ←→ TURN Server ←→ Patient
```

We use **17 different TURN configurations** across multiple providers, ports (80, 443, 3478), and protocols (UDP, TCP, TLS) to ensure maximum reliability!

---

## 🔒 Security Features

- **HTTPS Encryption** - All web traffic encrypted via ngrok TLS 1.2+
- **WebRTC Encryption** - Mandatory SRTP/DTLS for media streams
- **End-to-End Security** - Even TURN servers can't decrypt video/audio
- **Perfect Forward Secrecy** - Past sessions remain secure even if keys compromised
- **Input Validation** - SQL injection prevention with prepared statements
- **XSS Protection** - Output sanitization with `htmlspecialchars()`
- **File Permissions** - Proper access controls on signaling files

---

## 🐛 Troubleshooting

<details>
<summary><b>Database Connection Error</b></summary>

**Error:** `Unknown database 'docathome'`

**Solutions:**
- Ensure MySQL is running in XAMPP Control Panel
- Run `setup.bat` to create database automatically
- Manually create database in phpMyAdmin
- Check `db.php` for correct credentials (default: root, no password)

</details>

<details>
<summary><b>Video Call Connection Failed</b></summary>

**Error:** Connection stuck on "Connecting..." or "Connection failed"

**Solutions:**
- Both devices must use the **same ngrok URL**
- Check internet connection on both devices
- Hard refresh browsers (Ctrl+F5 or Cmd+Shift+R)
- Check browser console (F12) for detailed errors
- Verify TURN servers are reachable
- Temporarily disable firewall to test
- Look for relay candidates in console: `🔵 New ICE candidate: {type: 'relay'...}`

</details>

<details>
<summary><b>Camera/Microphone Permission Denied</b></summary>

**Error:** Browser blocks camera/microphone access

**Solutions:**
- **Must use HTTPS** - Use ngrok for mobile devices
- Check browser permissions (click lock icon in address bar)
- Try different browser (Chrome recommended)
- Ensure camera/mic aren't being used by another app
- On mobile, allow permissions when prompted

</details>

<details>
<summary><b>Chat Messages Not Appearing</b></summary>

**Error:** Messages not showing or updating

**Solutions:**
- Verify MySQL is running
- Check `messages` table exists in database
- Open browser console (F12) and check for JavaScript errors
- Verify `booking_id` parameter in URL
- Check network tab for failed AJAX requests

</details>

<details>
<summary><b>Waiting Room Not Redirecting</b></summary>

**Error:** Patient stuck in waiting room

**Solutions:**
- Ensure doctor clicked "Join Call"
- Check `chats/` folder has write permissions
- Verify `mark_joined.php` is accessible
- Clear browser cache (Ctrl+F5)
- Check browser console for errors

</details>

---

## 📚 Documentation

### Available Documentation Files

- **INSTALLATION_GUIDE.txt** - Complete installation instructions with manual steps
- **PRESENTATION_STORY.txt** - Real-life scenarios demonstrating platform value
- **TECHNICAL_DOCUMENTATION.txt** - Deep dive into architecture, protocols, and implementation
- **TECHNICAL_STORY.txt** - Technical concepts explained in story format for easy understanding

### Key Concepts Explained

**WebRTC (Web Real-Time Communication)**
- Industry-standard technology for peer-to-peer communication
- Used by Google Meet, Zoom, Microsoft Teams, Discord
- Enables direct browser-to-browser video/audio without plugins

**STUN (Session Traversal Utilities for NAT)**
- Discovers public IP address behind NAT/firewall
- Enables direct peer-to-peer connections when possible
- RFC 5389 standard protocol

**TURN (Traversal Using Relays around NAT)**
- Relays media when direct connection impossible
- Acts as intermediary server
- RFC 5766 standard protocol
- Essential for restrictive networks

**ICE (Interactive Connectivity Establishment)**
- Framework for finding best connection path
- Tests multiple routes simultaneously
- Combines STUN and TURN
- RFC 8445 standard protocol

**SDP (Session Description Protocol)**
- Describes media capabilities
- Exchanged during offer/answer negotiation
- Contains codecs, formats, network info

---

## 🤝 Contributing

Contributions are welcome! Here's how you can help:

1. **Fork the repository**
2. **Create a feature branch** (`git checkout -b feature/AmazingFeature`)
3. **Commit your changes** (`git commit -m 'Add some AmazingFeature'`)
4. **Push to the branch** (`git push origin feature/AmazingFeature`)
5. **Open a Pull Request**

### Ideas for Contributions
- Add user authentication system
- Implement real WebSocket for chat (replace polling)
- Add prescription generation feature
- Create mobile app (React Native)
- Add appointment scheduling calendar
- Implement medical record storage
- Add multi-language support
- Create admin panel for system management

---

## 📄 License

This project is licensed under the MIT License - see below for details:

```
MIT License

Copyright (c) 2025 DocAtHome

Permission is hereby granted, free of charge, to any person obtaining a copy
of this software and associated documentation files (the "Software"), to deal
in the Software without restriction, including without limitation the rights
to use, copy, modify, merge, publish, distribute, sublicense, and/or sell
copies of the Software, and to permit persons to whom the Software is
furnished to do so, subject to the following conditions:

The above copyright notice and this permission notice shall be included in all
copies or substantial portions of the Software.

THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY,
FITNESS FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE
AUTHORS OR COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER
LIABILITY, WHETHER IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM,
OUT OF OR IN CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE
SOFTWARE.
```

---

## 🙏 Acknowledgments

- **WebRTC Community** - For excellent documentation and examples
- **Google** - For free STUN servers
- **Open Relay Project** - For free TURN servers
- **ngrok** - For easy HTTPS tunneling
- **Bootstrap Team** - For beautiful UI components
- **XAMPP Team** - For easy local development environment

---

## 📞 Support

Having issues? Here's how to get help:

1. **Check Documentation** - Read the guides in the Documentation folder
2. **Browser Console** - Press F12 and check for error messages
3. **WebRTC Internals** - Visit `chrome://webrtc-internals` for detailed connection info
4. **ngrok Inspector** - Visit `http://localhost:4040` to inspect HTTP traffic
5. **GitHub Issues** - Open an issue on this repository

---

## 🎯 Future Enhancements

Planned features for future versions:

- [ ] User authentication and authorization
- [ ] Real-time WebSocket chat (replace polling)
- [ ] Prescription generation and PDF export
- [ ] Medical record management
- [ ] Appointment scheduling calendar
- [ ] Email/SMS notifications
- [ ] Payment gateway integration (real)
- [ ] Multi-language support
- [ ] Mobile app (React Native)
- [ ] Admin dashboard with analytics
- [ ] Video call recording
- [ ] Screen annotation tools
- [ ] File sharing during consultation
- [ ] Integration with pharmacy systems

---

<div align="center">

### ⭐ Star this repo if you found it helpful!

**Made with ❤️ for healthcare accessibility**

[⬆ Back to Top](#-docathome---telemedicine-platform)

</div>