<?php 
// Prevent caching to ensure fresh WebRTC signaling
header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
header('Pragma: no-cache');
header('Expires: 0');

$bookingId = isset($_GET['booking_id']) ? intval($_GET['booking_id']) : 0;
$role = isset($_GET['role']) ? htmlspecialchars($_GET['role']) : 'patient'; // 'doctor' or 'patient'

if ($bookingId <= 0) {
    header('Location: index.html');
    exit;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Video Call - DocAtHome</title>
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/[REDACTED]/dist/css/bootstrap.min.css">
  <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/[REDACTED]/font/bootstrap-icons.css">
  <style>
    body {
        background: linear-gradient(135deg, #f5f7fa 0%, #c3cfe2 100%);
        font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
    }
    .video-main-area { 
        position: relative; 
        width: 100%; 
        background: linear-gradient(135deg, #1a1a1a 0%, #000000 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 20px;
        overflow: hidden;
        aspect-ratio: 16 / 9;
        box-shadow: 0 15px 50px rgba(0,0,0,0.3);
    }
    #remoteVideo { 
        width: 100%; 
        height: 100%; 
        object-fit: cover;
    }
    #localVideoWrapper {
        position: absolute;
        bottom: 90px;
        right: 20px;
        width: clamp(120px, 20vw, 220px);
        aspect-ratio: 4/3;
        background: #111;
        border-radius: 15px;
        overflow: hidden;
        border: 3px solid rgba(255,255,255,0.3);
        box-shadow: 0 8px 25px rgba(0,0,0,0.5);
        cursor: move;
        resize: both;
        z-index: 10;
        transition: all 0.3s ease;
    }
    #localVideoWrapper:hover {
        border-color: rgba(255,255,255,0.5);
        box-shadow: 0 10px 30px rgba(0,0,0,0.6);
    }
    #localVideo { width: 100%; height: 100%; object-fit: cover; transform: scaleX(-1); }
    .video-label { 
        position: absolute; 
        bottom: 5px; 
        left: 10px; 
        background: rgba(0,0,0,0.6); 
        color: white; 
        padding: 3px 8px; 
        border-radius: 5px; 
        font-size: 0.8rem; 
    }
    .controls-bar {
        position: absolute;
        bottom: 0;
        left: 0;
        right: 0;
        z-index: 20;
        display: flex;
        justify-content: center;
        padding: 20px;
        background: linear-gradient(to top, rgba(0,0,0,0.7), transparent);
        transition: opacity 0.3s ease;
    }
    .controls-bar.hidden {
        opacity: 0;
        pointer-events: none;
    }
    .controls { 
        display: flex; 
        justify-content: center; 
        gap: 15px; 
        padding: 10px; 
        background: rgba(255,255,255,0.1);
        backdrop-filter: blur(10px);
        border-radius: 50px; 
        box-shadow: 0 4px 15px rgba(0,0,0,0.2); 
    }
    .control-btn { 
        width: 65px; 
        height: 65px;
        border-radius: 50%;
        font-size: 1.6rem;
        display: flex;
        align-items: center;
        justify-content: center;
        border: none;
        transition: all 0.3s ease;
        box-shadow: 0 4px 12px rgba(0,0,0,0.3);
    }
    .control-btn.active { 
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        color: white;
    }
    .control-btn.inactive { 
        background: linear-gradient(135deg, #434343 0%, #000000 100%);
        color: white;
    }
    .control-btn:hover {
        transform: scale(1.1);
        box-shadow: 0 6px 20px rgba(0,0,0,0.4);
    }
    .control-btn:active {
        transform: scale(0.95);
    }
    .end-call { 
        background: linear-gradient(135deg, #f093fb 0%, #f5576c 100%) !important;
        color: white;
    }
    .end-call:hover { 
        background: linear-gradient(135deg, #e082ea 0%, #e4465b 100%) !important;
    }

    .status-overlay {
        position: absolute;
        top: 20px;
        left: 50%;
        transform: translateX(-50%);
        z-index: 10;
        background: rgba(0,0,0,0.7);
        backdrop-filter: blur(10px);
        padding: 8px 20px;
        border-radius: 25px;
        display: flex;
        align-items: center;
        gap: 12px;
        box-shadow: 0 4px 15px rgba(0,0,0,0.3);
    }
    .[REDACTED] {
        border-radius: 20px;
        padding: 10px 20px;
        font-weight: 600;
        transition: all 0.3s ease;
    }
    .[REDACTED]:hover {
        transform: scale(1.05);
        box-shadow: 0 4px 12px rgba(0,0,0,0.15);
    }
  </style>
</head>
<body>
<div class="container py-5">
    <div class="d-flex [REDACTED] align-items-center mb-4">
        <h2 class="mb-0">
            <i class="bi [REDACTED] text-primary"></i>
            Video Call <span class="text-muted">#<?php echo $bookingId; ?></span>
        </h2>
        <a href="<?php echo $role === 'doctor' ? 'doctor_dashboard.php' : 'index.html'; ?>" class="btn [REDACTED]>
            <i class="bi bi-box-arrow-left"></i> Exit
        </a>
    </div>

    <div id=[REDACTED] class="alert alert-danger text-center" style="display: none;">
        <h4 class="alert-heading"><i class="bi bi-shield-lock-fill"></i> Secure Connection Required</h4>
        <p>To access the camera and microphone on a mobile device, your browser requires a secure (HTTPS) connection.</p>
        <hr>
        <p class="mb-0">Please use an HTTPS URL to join the call. For local development, we recommend using a tool like <strong>ngrok</strong>.</p>
        <a href="https_setup_guide.html" class="btn btn-warning mt-3">View Setup Guide</a>
    </div>
    <div id="videoCallUI" class="video-main-area">
        <div class="status-overlay">
            <div id="status" class="d-flex align-items-center">
                <div class="spinner-border spinner-border-sm" role="status"></div>
                <span class="ms-2">Initializing...</span>
            </div>
            <span id="callTimer" class="badge bg-info" style="display: none;">00:00</span>
            <span id="ping" class="badge bg-secondary" style="display: none;">- ms</span>
        </div>

        <video id="remoteVideo" autoplay playsinline></video>
        <div class="video-label"><?php echo $role === 'doctor' ? 'Patient' : 'Doctor'; ?></div>
        
        <div id="localVideoWrapper">
            <video id="localVideo" autoplay playsinline muted></video>
            <div class="video-label">You</div>
        </div>
        <div class="controls-bar" id="controlsBar">
            <div class="controls">
                <button id="micBtn" class="control-btn active"><i class="bi bi-mic-fill"></i></button>
                <button id="camBtn" class="control-btn active"><i class="bi [REDACTED]></i></button>
                <button id="screenShareBtn" class="control-btn inactive" title="Share Screen"><i class="bi bi-display"></i></button>
                <button id="endCallBtn" class="control-btn end-call"><i class="bi bi-telephone-x-fill"></i></button>
            </div>
        </div>
    </div>

    <?php if ($role === 'doctor'): ?>
    <div class="alert alert-info mt-3">
      <i class="bi bi-info-circle"></i> The patient will be redirected from the waiting room automatically. If they have issues, share this link:
      <input type="text" readonly class="form-control mt-2" id="patientLink" value="">
      <button class="btn btn-sm [REDACTED] mt-2" id="copyLinkBtn">Copy Link</button>
    </div>
    <?php endif; ?>
    </div>
</div>

<script>
const bookingId = <?php echo $bookingId; ?>;
const role = '<?php echo $role; ?>';
let pc, localStream;
let [REDACTED] = 0; // To avoid reprocessing signals
let micEnabled = true;
let camEnabled = true;
let isCallActive = true;
let timerInterval;
let pingInterval;
let isScreenSharing = false;
let cameraVideoTrack = null;
let candidateQueue = []; // Queue for ICE candidates that arrive early
let controlsHideTimeout;

let reconnectTimeout;
let reconnectAttempts = 0;
const [REDACTED] = 5;

function sleep(ms){ return new Promise(r=>setTimeout(r, ms)); }

function updateStatus(message, isConnected = false) {
    const statusEl = document.getElementById('status');
    const icon = isConnected ? '<i class="bi [REDACTED] text-success"></i>' : '<div class="spinner-border spinner-border-sm text-muted" role="status"></div>';
    const textClass = isConnected ? 'text-success' : '';
    statusEl.innerHTML = `${icon} <span class="ms-2 ${textClass}">${message}</span>`.trim();
}

async function readSignals(){
  try{
    const res = await fetch(`read_signal.php?booking_id=${bookingId}&t=${Date.now()}`, {cache: "no-store"});
    if(!res.ok) {
        console.error(`Failed to read signals: ${res.status} ${res.statusText}`);
        return [];
    }
    const text = await res.text();
    if (text.trim()) {
        console.log('Raw signals received:', text.substring(0, 200));
    }
    return text.trim().split('\n').filter(Boolean).map(l => {
      try { return JSON.parse(l); } catch(e) { 
        console.error('Failed to parse signal:', l, e);
        return null; 
      }
    }).filter(Boolean);
  }catch(e){ 
      console.error("Error in readSignals:", e);
      return []; 
  }
}

async function sendSignal(obj){
  console.log('Sending signal:', obj.type, 'from', obj.role);
  const body = `booking_id=${encodeURIComponent(bookingId)}&data=${encodeURIComponent(JSON.stringify(obj))}`;
  try {
    const res = await fetch('write_signal.php', {method:'POST', headers:{'Content-Type':'application/[REDACTED]}, body});
    if (!res.ok) {
        console.error(`Failed to send signal: ${res.status} ${res.statusText}`);
        const text = await res.text();
        console.error('Response:', text);
    } else {
        const result = await res.json();
        console.log('Signal sent successfully:', result);
    }
  } catch(e) {
    console.error('Error sending signal:', e);
  }
}

async function pollSignals(){
  let pollInterval = 300; // Start with a very aggressive poll interval for a quick handshake.

  while(isCallActive){
    const allSignals = await readSignals();
    const newSignals = allSignals.slice([REDACTED]);

    for(const s of newSignals){
      // Ignore own signals or invalid signals
      if (!s || s.role === role) continue;
      console.log('Processing signal:', s);

      // Doctor: When patient signals they are ready, create and send the offer.
      if (s.type === 'patient-ready' && role === 'doctor') {
          console.log('✅ Patient is ready! Creating offer...');
          updateStatus('Patient ready, creating offer...');
          try {
            const offer = await pc.createOffer();
            await pc.setLocalDescription(offer);
            console.log('✅ Offer created and set as local description');
            await sendSignal({role: 'doctor', type:'offer', sdp:pc.localDescription});
            console.log('✅ Offer sent to patient');
            updateStatus('Offer sent, waiting for answer...');
          } catch(e) {
            console.error('❌ Failed to create/send offer:', e);
            updateStatus('Failed to create offer', false);
          }
      }
      else if (s.type === 'bye') {
          console.log('Received bye signal.');
          endCall(true); // Pass true to indicate remote initiated
          return; // Stop polling
      }
      // The patient receives an offer from the doctor
      else if (s.type === 'offer' && role === 'patient') {
          try {
              console.log('✅ Received offer from doctor');
              updateStatus('Received offer, creating answer...');
              await pc.[REDACTED](new [REDACTED](s.sdp));
              console.log('✅ Remote description set');
              // Process any candidates that were received before the offer
              if (candidateQueue.length > 0) {
                  console.log(`Processing ${candidateQueue.length} queued candidates.`);
                  for (const candidate of candidateQueue) { await pc.addIceCandidate(candidate); }
                  candidateQueue = []; // Clear the queue
              }
              const answer = await pc.createAnswer();
              await pc.setLocalDescription(answer);
              console.log('✅ Answer created and set as local description');
              updateStatus('Sending answer...');
              await sendSignal({ role: 'patient', type: 'answer', sdp: pc.localDescription });
              console.log('✅ Answer sent to doctor');
              updateStatus('Exchanging connection details...');
          } catch (e) { 
            console.error("❌ Error processing offer:", e);
            updateStatus('Failed to process offer', false);
          }
      } 
      // The doctor receives an answer from the patient
      else if (s.type === 'answer' && role === 'doctor' && !pc.remoteDescription) {
          try {
              console.log('Received answer.');
              await pc.[REDACTED](new [REDACTED](s.sdp));
              updateStatus('Exchanging connection details...');
              // Process any candidates that were received before the answer
              if (candidateQueue.length > 0) {
                  console.log(`Processing ${candidateQueue.length} queued candidates.`);
                  for (const candidate of candidateQueue) { await pc.addIceCandidate(candidate); }
                  candidateQueue = []; // Clear the queue
              }
          } catch (e) { console.error("Error processing answer:", e); }
      } 
      // Both peers can receive candidates at any time
      else if (s.type === 'candidate') {
          try {
              const candidate = new RTCIceCandidate(s.candidate);
              // If the remote description isn't set yet, queue the candidate.
              // This prevents the "InvalidStateError" race condition.
              if (pc.remoteDescription) {
                  await pc.addIceCandidate(candidate);
              } else {
                  console.log('Queuing candidate because remote description is not set.');
                  candidateQueue.push(candidate);
              }
          } catch (e) {
              if (pc.signalingState !== 'closed') {
                  console.error('Error adding received ICE candidate', e);
              }
          }
      }
    }
    [REDACTED] = allSignals.length;

    // Once connected, we can slow down polling significantly as we're mostly just listening for a 'bye' signal.
    if (pc && (pc.connectionState === 'connected' || pc.connectionState === 'completed')) {
        pollInterval = 2000; // Slow down to 2 seconds
    } else if (pc && pc.connectionState === 'connecting') {
        pollInterval = 500; // Medium speed while actively connecting
    } else {
        pollInterval = 300; // Keep it fast during initial setup
    }
    await sleep(pollInterval);
  }
}

async function init() {
    console.log(`Starting video call for booking ${bookingId} as ${role}`);
    updateStatus('Requesting permissions...');
    try {
        localStream = await navigator.mediaDevices.getUserMedia({video:true, audio:true});
        document.getElementById('localVideo').srcObject = localStream;
    } catch (e) {
        console.error("Error getting media stream", e);
        const statusEl = document.getElementById('status');
        statusEl.innerHTML = `<i class="bi [REDACTED] text-danger"></i> <span class="ms-2 text-danger">Permission Denied</span>`;
        alert('Could not access camera/microphone. Please check permissions and refresh.');
        return;
    }

    // Last resort: Try every possible TURN configuration
    const iceServersList = [
        // Google STUN
        { urls: 'stun:stun.l.google.com:19302' },
        { urls: 'stun:stun1.l.google.com:19302' },
        { urls: 'stun:stun2.l.google.com:19302' },
        { urls: 'stun:stun3.l.google.com:19302' },
        { urls: 'stun:stun4.l.google.com:19302' },
        
        // Try EVERY port combination for openrelay
        { urls: 'turn:openrelay.metered.ca:80', username: 'openrelayproject', credential: 'openrelayproject' },
        { urls: 'turn:openrelay.metered.ca:443', username: 'openrelayproject', credential: 'openrelayproject' },
        { urls: 'turn:openrelay.metered.ca:80?transport=tcp', username: 'openrelayproject', credential: 'openrelayproject' },
        { urls: 'turn:openrelay.metered.ca:443?transport=tcp', username: 'openrelayproject', credential: 'openrelayproject' },
        { urls: 'turns:openrelay.metered.ca:443', username: 'openrelayproject', credential: 'openrelayproject' },
        
        // Backup metered servers
        { urls: 'turn:a.relay.metered.ca:80', username: [REDACTED], credential: 'Qqv/JNZrqvWYGNFb' },
        { urls: 'turn:a.relay.metered.ca:443', username: [REDACTED], credential: 'Qqv/JNZrqvWYGNFb' },
        { urls: 'turn:a.relay.metered.ca:80?transport=tcp', username: [REDACTED], credential: 'Qqv/JNZrqvWYGNFb' },
        { urls: 'turns:a.relay.metered.ca:443?transport=tcp', username: [REDACTED], credential: 'Qqv/JNZrqvWYGNFb' },
        
        // Numb
        { urls: 'turn:numb.viagenie.ca', username: '[REDACTED]', credential: 'muazkh' },
        { urls: 'turn:numb.viagenie.ca:3478', username: '[REDACTED]', credential: 'muazkh' },
        { urls: 'turn:numb.viagenie.ca:3478?transport=tcp', username: '[REDACTED]', credential: 'muazkh' }
    ];
    
    const [REDACTED] = {
        iceServers: iceServersList,
        [REDACTED]: 20,
        iceTransportPolicy: 'all',
        bundlePolicy: 'max-bundle',
        rtcpMuxPolicy: 'require'
    };
    
    console.log('⚙️ Using AGGRESSIVE ICE config with', iceServersList.length, 'servers');
    console.log('🔥 CRITICAL: Watching for relay candidates...');

    pc = new RTCPeerConnection([REDACTED]);
    updateStatus('Connecting...');

    localStream.getTracks().forEach(track => pc.addTrack(track, localStream));
    cameraVideoTrack = localStream.getVideoTracks()[0];

    // Setup all PC event handlers BEFORE setting up signaling
    pc.ontrack = ev => {
        console.log('Remote track received');
        document.getElementById('remoteVideo').srcObject = ev.streams[0];
    };

    pc.[REDACTED] = e => {
        console.log(`ICE gathering state changed to: ${pc.iceGatheringState}`);
        if (pc.iceGatheringState === 'complete') {
            console.log('✅ ICE gathering complete - all candidates collected');
        }
    };

    pc.[REDACTED] = e => {
        console.log(`Signaling state changed to: ${pc.signalingState}`);
    };

    pc.onicecandidate = async (e) => {
        if(e.candidate){
            console.log('🔵 New ICE candidate:', {
                type: e.candidate.type,
                protocol: e.candidate.protocol,
                address: e.candidate.address || 'hidden',
                port: e.candidate.port,
                candidate: e.candidate.candidate.substring(0, 50) + '...'
            });
            await sendSignal({role: role, type:'candidate', candidate:e.candidate});
        } else {
            console.log('✅ ICE candidate gathering complete');
        }
    };

    pc.[REDACTED] = async e => {
        console.log('🔵 ICE connection state:', pc.iceConnectionState);
        
        if (pc.iceConnectionState === 'checking') {
            console.log('🔍 Checking ICE candidates...');
            // Log which candidates are being tested
            const stats = await pc.getStats();
            stats.forEach(report => {
                if (report.type === 'candidate-pair' && report.state === 'in-progress') {
                    console.log('Testing candidate pair:', report);
                }
            });
        }
        
        if (pc.iceConnectionState === 'connected' || pc.iceConnectionState === 'completed') {
            console.log('✅ ICE connection established!');
            // Log the winning candidate pair
            const stats = await pc.getStats();
            stats.forEach(report => {
                if (report.type === 'candidate-pair' && report.state === 'succeeded') {
                    console.log('✅ Connected using:', report);
                }
            });
        }
        
        if (pc.iceConnectionState === 'failed') {
            console.error('❌ ICE connection failed!');
            // Log why it failed
            const stats = await pc.getStats();
            let foundRelay = false;
            stats.forEach(report => {
                if (report.type === 'local-candidate' && report.candidateType === 'relay') {
                    foundRelay = true;
                }
            });
            console.error('TURN relay candidates available:', foundRelay);
            updateStatus('Connection failed. Please check your network.', false);
        }
    };

    pc.[REDACTED] = e => {
        const state = pc.connectionState;
        console.log('Connection state:', state);

        if (state === 'connected') {
            updateStatus('Connected', true);
            startTimer();
            // Start monitoring ping and reset reconnect attempts
            if (!pingInterval) {
                pingInterval = setInterval(monitorPing, 3000);
                document.getElementById('ping').style.display = 'inline-block';
            }
            if (reconnectTimeout) clearTimeout(reconnectTimeout);
            reconnectAttempts = 0;
        } else if (state === 'disconnected') {
            // Connection temporarily lost, wait a bit before reconnecting
            updateStatus('Connection lost, reconnecting...');
            clearInterval(pingInterval);
            pingInterval = null;
            document.getElementById('ping').style.display = 'none';
            
            // Give it some time to recover naturally before forcing reconnect
            setTimeout(() => {
                if (pc.connectionState === 'disconnected' && role === 'doctor') {
                    handleReconnect();
                }
            }, 3000);
        } else if (state === 'failed') {
            // Connection definitively failed
            updateStatus('Connection failed. Please refresh and try again.');
            clearInterval(pingInterval);
            pingInterval = null;
            document.getElementById('ping').style.display = 'none';
            
            // Show user-friendly error
            alert('Connection failed. This may be due to network restrictions. Please refresh the page and try again.');
        } else if (state === 'closed') {
            // The call has been definitively closed.
            endCall(true);
        } else {
            // Handle 'new', 'checking', 'connecting'
            updateStatus(state.charAt(0).toUpperCase() + state.slice(1) + '...');
        }
    };

    // Now that the peer connection is fully configured, set up the signaling channel.
    if (role === 'doctor') {
        console.log('Acting as caller (doctor), waiting for patient to signal readiness.');
        updateStatus('Waiting for patient...');
        // Clean up old signal files and mark doctor as joined
        await fetch(`mark_ready.php?booking_id=${bookingId}`);
        // Create join file to signal patient in waiting room
        await fetch(`mark_joined.php?booking_id=${bookingId}`);
        const patientLink = `${location.origin}${location.pathname.replace('video_call.php', 'waiting.php')}?booking_id=${bookingId}&role=patient`;
        document.getElementById('patientLink').value = patientLink;
        document.getElementById('copyLinkBtn').addEventListener('click', () => {
            navigator.clipboard.writeText(patientLink).then(() => {
                document.getElementById('copyLinkBtn').textContent = 'Copied!';
                setTimeout(() => { document.getElementById('copyLinkBtn').textContent = 'Copy Link'; }, 2000);
            });
        });
    } else {
        console.log('Acting as callee (patient), signaling readiness to the doctor.');
        updateStatus('Signaling ready...');
        // Small delay to ensure everything is set up before signaling
        await sleep(500);
        await sendSignal({role: 'patient', type: 'patient-ready'});
        updateStatus('Waiting for offer from doctor...');
    }

    // Start polling for signals
    pollSignals();
}

function handleReconnect() {
    if (reconnectAttempts >= [REDACTED]) {
        console.error('Max reconnect attempts reached. Ending call.');
        alert('Could not re-establish the connection. The call will now end.');
        endCall(false);
        return;
    }

    // Wait a moment before trying to reconnect, in case it's a short blip
    clearTimeout(reconnectTimeout);
    reconnectTimeout = setTimeout(async () => {
        if (pc.connectionState === 'connected') {
            console.log('Connection restored before reconnect attempt was made.');
            return;
        }

        reconnectAttempts++;
        console.log(`Attempting to reconnect... (Attempt ${reconnectAttempts}/${[REDACTED]})`);
        updateStatus(`Reconnecting... (Attempt ${reconnectAttempts})`);

        // Use ICE restart to negotiate a new connection path without tearing down the whole call
        try {
            const offer = await pc.createOffer({ iceRestart: true });
            await pc.setLocalDescription(offer);
            await sendSignal({ role: 'doctor', type: 'offer', sdp: pc.localDescription });
        } catch (e) {
            console.error('ICE restart failed:', e);
            endCall(false);
        }
    }, 5000); // Wait 5 seconds before the first attempt
}

async function monitorPing() {
    if (!pc || pc.connectionState !== 'connected') return;

    const stats = await pc.getStats();
    let roundTripTime = -1;

    stats.forEach(report => {
        if (report.type === 'candidate-pair' && report.state === 'succeeded') {
            roundTripTime = Math.round(report.[REDACTED] * 1000); // in ms
        }
    });

    const pingEl = document.getElementById('ping');
    pingEl.textContent = roundTripTime !== -1 ? `${roundTripTime} ms` : '... ms';
    if (roundTripTime < 150) pingEl.className = 'badge bg-success ms-2';
    else if (roundTripTime < 300) pingEl.className = 'badge bg-warning ms-2';
    else pingEl.className = 'badge bg-danger ms-2';
}

function startTimer() {
    if (timerInterval) return;
    const timerEl = document.getElementById('callTimer');
    timerEl.style.display = 'inline-block';
    let startTime = Date.now();
    timerInterval = setInterval(() => {
        if (!isCallActive) return;
        const elapsed = Math.floor((Date.now() - startTime) / 1000);
        const minutes = Math.floor(elapsed / 60).toString().padStart(2, '0');
        const seconds = (elapsed % 60).toString().padStart(2, '0');
        timerEl.textContent = `${minutes}:${seconds}`;
    }, 1000);
}

document.getElementById('micBtn').addEventListener('click', () => {
    micEnabled = !micEnabled;
    localStream.getAudioTracks()[0].enabled = micEnabled;
    document.getElementById('micBtn').innerHTML = micEnabled ? '<i class="bi bi-mic-fill"></i>' : '<i class="bi bi-mic-mute-fill"></i>';
    document.getElementById('micBtn').classList.toggle('active', micEnabled);
    document.getElementById('micBtn').classList.toggle('inactive', !micEnabled);
});

document.getElementById('camBtn').addEventListener('click', () => {
    camEnabled = !camEnabled;
    localStream.getVideoTracks()[0].enabled = camEnabled;
    document.getElementById('camBtn').innerHTML = camEnabled ? '<i class="bi [REDACTED]></i>' : '<i class="bi [REDACTED]></i>';
    document.getElementById('camBtn').classList.toggle('active', camEnabled);
    document.getElementById('camBtn').classList.toggle('inactive', !camEnabled);
});

document.getElementById('endCallBtn').addEventListener('click', async () => {
    if (isCallActive && confirm('Are you sure you want to end the call?')) {
        await sendSignal({ type: 'bye', role: role });
        endCall(false); // Pass false to indicate local initiated
    }
});

document.getElementById('screenShareBtn').addEventListener('click', async () => {
    if (isScreenSharing) {
        await stopScreenSharing();
    } else {
        await startScreenSharing();
    }
});

async function startScreenSharing() {
    try {
        const screenStream = await navigator.mediaDevices.getDisplayMedia({ video: true });
        const screenTrack = screenStream.getVideoTracks()[0];

        const sender = pc.getSenders().find(s => s.track && s.track.kind === 'video');
        if (sender) {
            await sender.replaceTrack(screenTrack);
            document.getElementById('localVideo').srcObject = new MediaStream([screenTrack]); // Show screen share locally
            isScreenSharing = true;
            document.getElementById('screenShareBtn').classList.replace('inactive', 'active');
            document.getElementById('screenShareBtn').innerHTML = '<i class="bi [REDACTED]></i>';
            
            screenTrack.onended = () => { stopScreenSharing(); };
        }
    } catch (e) {
        console.error('Error starting screen share:', e);
    }
}

async function stopScreenSharing() {
    if (!cameraVideoTrack) return;
    const sender = pc.getSenders().find(s => s.track && s.track.kind === 'video');
    if (sender) {
        await sender.replaceTrack(cameraVideoTrack);
        document.getElementById('localVideo').srcObject = localStream; // Revert to camera view
        isScreenSharing = false;
        document.getElementById('screenShareBtn').classList.replace('active', 'inactive');
        document.getElementById('screenShareBtn').innerHTML = '<i class="bi bi-display"></i>';
    }
}

function endCall(isRemoteInitiated = false) {
    if (!isCallActive) return;
    isCallActive = false;
    if (timerInterval) clearInterval(timerInterval);
    if (pingInterval) clearInterval(pingInterval);
    if (reconnectTimeout) clearTimeout(reconnectTimeout);

    if (pc) pc.close();
    if (localStream) localStream.getTracks().forEach(track => track.stop());
    
    const endMessage = isRemoteInitiated
        ? 'The other party has ended the call.' 
        : 'You have ended the call.';

    updateStatus('Call Ended', false);
    document.getElementById('videoCallUI').innerHTML = `
        <div class="text-center p-5">
            <h3 class="text-muted">${endMessage}</h3>
            <p>You will be redirected shortly.</p>
        </div>
    `;
    setTimeout(() => {
        window.location.href = role === 'doctor' ? 'doctor_dashboard.php' : 'index.html';
    }, 3000);
}

function onPageLoad() {
    const isSecure = window.location.protocol === 'https:';
    const isLocalhost = location.hostname === 'localhost' || location.hostname === '127.0.0.1';

    // Browser security rule: Camera/Mic access requires a secure context (HTTPS) for any website that isn't localhost.
    // This check makes the error visible instead of silent.
    if (!isSecure && !isLocalhost) {
        if (document.getElementById('videoCallUI')) document.getElementById('videoCallUI').style.display = 'none';
        document.getElementById([REDACTED]).style.display = 'block';
        const statusEl = document.getElementById('status');
        statusEl.innerHTML = `<i class="bi [REDACTED] text-danger"></i> <span class="ms-2">Insecure Connection</span>`;
    } else {
        init();
    }
}

window.addEventListener('load', onPageLoad);

// Auto-hide controls logic
function showControls() {
    const controlsBar = document.getElementById('controlsBar');
    if (controlsBar) controlsBar.classList.remove('hidden');
    clearTimeout(controlsHideTimeout);
    controlsHideTimeout = setTimeout(() => {
        if (controlsBar) controlsBar.classList.add('hidden');
    }, 4000); // Hide after 4 seconds of inactivity
}

document.body.addEventListener('mousemove', showControls);
document.body.addEventListener('touchstart', showControls);
showControls(); // Show controls on load


// Make local video draggable
(function makeDraggable() {
    const el = document.getElementById('localVideoWrapper');
    let isDown = false;
    let offset = [0, 0];

    el.addEventListener('mousedown', function(e) {
        isDown = true;
        offset = [
            el.offsetLeft - e.clientX,
            el.offsetTop - e.clientY
        ];
    }, true);

    document.addEventListener('mouseup', function() {
        isDown = false;
    }, true);

    document.addEventListener('mousemove', function(e) {
        e.preventDefault();
        if (isDown) {
            el.style.left = (e.clientX + offset[0]) + 'px';
            el.style.top  = (e.clientY + offset[1]) + 'px';
        }
    }, true);
})();

</script>
</body>
</html>

/* docathome seq: 30 */