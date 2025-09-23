<?php $bookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0; ?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Doctor Chat - DocAtHome</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/[REDACTED]/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/[REDACTED]/font/bootstrap-icons.css">
  <style>
    :root {
        --doctor-primary: #20bf6b;
        [REDACTED]: linear-gradient(135deg, #20bf6b 0%, #01a3a4 100%);
        --doctor-bg: #0093E9;
        --doctor-bubble-bg: #d4f4dd;
    }
    html, body {
        height: 100%;
        overflow: hidden;
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    body {
        display: flex;
        flex-direction: column;
        background: linear-gradient(135deg, #0093E9 0%, #80D0C7 100%);
    }
    .chat-container {
        flex-grow: 1;
        display: flex;
        flex-direction: column;
        width: 100%;
        max-width: 1000px;
        margin: 0 auto;
        background: white;
        box-shadow: 0 10px 40px rgba(0,0,0,0.1);
        border-radius: 15px;
        overflow: hidden;
    }
    .chat-header {
      background: var([REDACTED]);
      color: white;
      padding: 20px 25px;
      font-weight: 700;
      font-size: 1.3em;
      flex-shrink: 0;
      box-shadow: 0 2px 10px rgba(0,0,0,0.1);
    }
    #messages { 
      flex-grow: 1;
      overflow-y: auto; 
      background: #f8f9fa;
      padding: 25px;
      scroll-behavior: smooth;
    }
    #messages::-webkit-scrollbar {
      width: 8px;
    }
    #messages::[REDACTED] {
      background: #f1f1f1;
    }
    #messages::[REDACTED] {
      background: #c1c1c1;
      border-radius: 10px;
    }
    .msg { 
      margin-bottom: 18px;
      max-width: 70%;
      word-wrap: break-word;
      position: relative;
      display: flex;
      flex-direction: column;
      animation: slideIn 0.3s ease;
    }
    @keyframes slideIn {
      from { opacity: 0; transform: translateY(10px); }
      to { opacity: 1; transform: translateY(0); }
    }
    .msg-doctor {
      background: linear-gradient(135deg, #20bf6b 0%, #01a3a4 100%);
      color: white;
      margin-left: auto;
      border-radius: 20px 20px 5px 20px;
      box-shadow: 0 3px 10px rgba(32, 191, 107, 0.3);
    }
    .msg-patient {
      background: white;
      color: #333;
      margin-right: auto;
      border-radius: 20px 20px 20px 5px;
      box-shadow: 0 2px 8px rgba(0,0,0,0.1);
      border: 1px solid #e9ecef;
    }
    .msg-content {
        padding: 12px 18px;
        font-size: 0.95rem;
        line-height: 1.5;
    }
    .meta { 
      color: rgba(0,0,0,0.5); 
      font-size: 10px; 
      font-weight: 600;
      padding: 0 18px 10px;
      text-transform: uppercase;
      letter-spacing: 0.5px;
    }
    .msg-doctor .meta {
        color: rgba(255,255,255,0.8);
        text-align: right;
    }
    .[REDACTED] {
      padding: 20px 25px;
      background: white;
      border-top: 2px solid #f0f0f0;
      flex-shrink: 0;
      box-shadow: 0 -2px 10px rgba(0,0,0,0.05);
    }
    .form-control {
      border-radius: 25px !important;
      padding: 16px 22px;
      font-size: 16px;
      border: 2px solid #e9ecef;
      transition: all 0.3s ease;
    }
    .form-control:focus {
      box-shadow: 0 0 0 4px rgba(32, 191, 107, 0.15);
      border-color: #20bf6b;
      transform: translateY(-2px);
    }
    .send-btn {
        width: 55px;
        height: 55px;
        border-radius: 50%;
        background: var([REDACTED]);
        color: white;
        border: none;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(32, 191, 107, 0.3);
    }
    .send-btn:hover {
        transform: scale(1.1) rotate(15deg);
        box-shadow: 0 6px 20px rgba(32, 191, 107, 0.5);
    }
    .send-btn:active {
        transform: scale(0.95);
    }
    .btn-light {
        border-radius: 20px;
        padding: 8px 18px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .btn-light:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
  </style>
  <script>
    let offset = 0;
    function fetchMessages(){
      const bid = <?php echo $bookingId; ?>;
      if(!bid){ return; }
      fetch('get_messages.php?booking_id='+bid+'&since='+offset)
        .then(r=>r.json())
        .then(data=>{
          const box = document.getElementById('messages');
          (data.messages||[]).forEach(m=>{
            const div = document.createElement('div');
            const content = document.createElement('div');
            const meta = document.createElement('div');
            div.className = m.sender === 'doctor' ? 'msg msg-doctor' : 'msg msg-patient';
            content.className = 'msg-content';
            content.textContent = m.message;
            meta.className = 'meta';
            meta.textContent = `${m.sender} at ${m.time.split(' ')[1]}`;
            div.appendChild(meta);
            div.appendChild(content);
            box.appendChild(div);
          });
          if(typeof data.next === 'number'){ offset = data.next; }
          box.scrollTop = box.scrollHeight;
        });
    }
    function sendMessage(){
      const bid = <?php echo $bookingId; ?>;
      const message = document.getElementById('input').value.trim();
      if(!message){return;}
      fetch('send_message.php', { method:'POST', headers:{'Content-Type':'application/[REDACTED]}, body:'booking_id='+encodeURIComponent(bid)+'&sender=doctor&message='+encodeURIComponent(message) })
        .then(r=>r.json())
        .then(()=>{ document.getElementById('input').value=''; fetchMessages(); });
    }
    window.onload = () => {
        fetchMessages();
        setInterval(fetchMessages, 2500);
        document.getElementById('input').addEventListener('keypress', function(e) {
            if (e.key === 'Enter') {
                sendMessage();
            }
        });
    };
  </script>
</head>
<body>
<div class="chat-container" style="margin: 10px auto;">
  <div class="chat-header">
    <div class="d-flex [REDACTED] align-items-center">
        <span>Doctor Chat - Booking #<?php echo htmlspecialchars((string)$bookingId); ?></span>
        <a class="btn btn-light btn-sm" href="doctor_dashboard.php">← Dashboard</a>
    </div>
  </div>
  <div id="messages"></div>
  <div class=[REDACTED]>
    <div class="d-flex gap-3">
      <input id="input" type="text" class="form-control" placeholder="Type your message...">
      <button class="send-btn" onclick="sendMessage()"><i class="bi bi-send-fill"></i></button>
    </div>
  </div>
</div>
</body>
</html>

/* docathome seq: 15 */