<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>Chatbot Demo</title>
  <style>
    body { font-family: system-ui, -apple-system, Arial, sans-serif; margin: 0; display: flex; justify-content: center; align-items: center; height: 100vh; background-color: #f3f4f6; }
    .box { width: 100%; max-width: 600px; height: 90vh; background: #fff; border-radius: 12px; box-shadow: 0 10px 25px rgba(0,0,0,0.1); display: flex; flex-direction: column; overflow: hidden; }
    .header { background: #0b3d91; color: white; padding: 18px; text-align: center; font-weight: 600; font-size: 1.25rem; }
    .chat { flex: 1; overflow-y: auto; padding: 20px; display: flex; flex-direction: column; gap: 14px; background: #fafafa; scroll-behavior: smooth; }
    .msg { max-width: 80%; padding: 12px 18px; border-radius: 18px; font-size: 15px; line-height: 1.5; word-wrap: break-word; }
    .msg.bot { align-self: flex-start; background: #e9ecef; color: #212529; border-bottom-left-radius: 4px; }
    .msg.user { align-self: flex-end; background: #0b3d91; color: white; border-bottom-right-radius: 4px; }
    
    .typing-indicator { display: flex; align-items: center; gap: 4px; padding: 14px 18px; background: #e9ecef; border-radius: 18px; border-bottom-left-radius: 4px; align-self: flex-start; width: max-content; }
    .dot { width: 8px; height: 8px; background: #adb5bd; border-radius: 50%; animation: blink 1.4s infinite both; }
    .dot:nth-child(1) { animation-delay: 0.2s; }
    .dot:nth-child(2) { animation-delay: 0.4s; }
    .dot:nth-child(3) { animation-delay: 0.6s; }
    @keyframes blink { 0% { opacity: 0.2; } 20% { opacity: 1; } 100% { opacity: 0.2; } }

    .action-area { padding: 16px; background: white; border-top: 1px solid #eee; display: flex; flex-direction: column; gap: 12px; }
    .quick-replies { display: flex; flex-wrap: wrap; gap: 8px; }
    .quick-reply-btn { background: #eef2ff; color: #0b3d91; border: 1px solid #c7d2fe; padding: 10px 18px; border-radius: 20px; font-size: 14px; font-weight: 500; cursor: pointer; transition: all 0.2s ease; white-space: nowrap; flex-grow: 1; text-align: center; }
    .quick-reply-btn:hover { background: #0b3d91; color: white; border-color: #0b3d91; }
    .input-row { display: flex; gap: 10px; }
    input { flex: 1; padding: 14px 20px; border-radius: 26px; border: 1px solid #ddd; outline: none; font-size: 15px; background: #f8f9fa; transition: border 0.2s;}
    input:focus { border-color: #0b3d91; background: #fff; }
    button.send { background: #0b3d91; color: white; border: none; width: 48px; height: 48px; border-radius: 50%; cursor: pointer; display: flex; justify-content: center; align-items: center; transition: 0.2s; flex-shrink: 0;}
    button.send:hover { background: #082d6b; transform: scale(1.05); }
    button.send svg { width: 22px; height: 22px; fill: currentColor; transform: translateX(2px); }
  </style>
</head>
<body>
  <div class="box">
    <div class="header">Chatbot Demo</div>

    <div id="chat" class="chat">
      <div class="msg bot">{{ $welcome }}</div>
      <div class="msg bot">{{ $firstStep['question'] }}</div>
    </div>

    <div class="action-area">
      <div id="quick-replies" class="quick-replies"></div>
      <div class="input-row">
        <input id="input" placeholder="Antwort eingeben…" autocomplete="off">
        <button id="send" class="send">
          <svg viewBox="0 0 24 24"><path d="M2.01 21L23 12 2.01 3 2 10l15 2-15 2z"></path></svg>
        </button>
      </div>
    </div>
  </div>

<script>
const chat = document.getElementById('chat');
const input = document.getElementById('input');
const btn = document.getElementById('send');
const quickReplies = document.getElementById('quick-replies');
const csrf = document.querySelector('meta[name="csrf-token"]').content;

let currentOptions = @json($firstStep['options'] ?? null);
let currentType = @json($firstStep['type'] ?? null);

function renderOptions() {
  quickReplies.innerHTML = '';
  if ((currentType === 'select' || currentType === 'boolean') && currentOptions && currentOptions.length > 0) {
    currentOptions.forEach(opt => {
      const btn = document.createElement('button');
      btn.className = 'quick-reply-btn';
      btn.textContent = opt;
      btn.onclick = () => {
        quickReplies.innerHTML = ''; 
        input.value = opt;
        send();
      };
      quickReplies.appendChild(btn);
    });
  }
}

function scrollToBottom() {
  chat.scrollTop = chat.scrollHeight;
}

function addMsg(text, who) {
  const div = document.createElement('div');
  div.className = 'msg ' + who;
  
  // Format bold text
  let formattedText = text.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
  div.innerHTML = formattedText.replace(/\n/g, '<br>');
  
  chat.appendChild(div);
  scrollToBottom();
}

function showTyping() {
  const div = document.createElement('div');
  div.className = 'typing-indicator';
  div.id = 'typing';
  div.innerHTML = '<div class="dot"></div><div class="dot"></div><div class="dot"></div>';
  chat.appendChild(div);
  scrollToBottom();
}

function removeTyping() {
  const typing = document.getElementById('typing');
  if (typing) typing.remove();
}

async function send() {
  const msg = input.value.trim();
  if (!msg) return;
  input.value = '';
  quickReplies.innerHTML = ''; 
  
  addMsg(msg, 'user');
  showTyping();

  try {
    const res = await fetch("{{ route('chatbot.message') }}", {
      method: 'POST',
      headers: {
        'Content-Type': 'application/json',
        'X-CSRF-TOKEN': csrf,
        'Accept': 'application/json'
      },
      body: JSON.stringify({ message: msg })
    });

    const data = await res.json();
    
    setTimeout(() => {
        removeTyping();
        addMsg(data.bot || 'Fehler.', 'bot');
        
        currentOptions = data.options;
        currentType = data.type;
        if (!data.done) {
            renderOptions();
        }
        if (data.done && data.pdf_url) {
    const downloadBtn = document.createElement('a');
    downloadBtn.href = data.pdf_url;
    downloadBtn.className = 'quick-reply-btn'; // استخدم نفس الستايل الجميل
    downloadBtn.innerHTML = '📄 Kostenlose Kalkulation (PDF) herunterladen';
    downloadBtn.target = '_blank';
    chat.appendChild(downloadBtn);
    scrollToBottom();
}
    }, 600 + Math.random() * 400); // 600-1000ms delay for natural feeeling
    
  } catch(e) {
    removeTyping();
    addMsg('Netzwerkfehler.', 'bot');
  }
}

btn.addEventListener('click', send);
input.addEventListener('keydown', (e) => { if (e.key === 'Enter') send(); });

// Render initial options
renderOptions();
</script>
</body>
</html>