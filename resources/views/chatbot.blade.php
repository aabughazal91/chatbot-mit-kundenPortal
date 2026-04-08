<!doctype html>
<html lang="de">
<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <meta name="csrf-token" content="{{ csrf_token() }}">
  <title>agentur-77 Chatbot</title>
  <link rel="stylesheet" href="{{ asset('css/chatbot.css') }}">
</head>
<body>
  
  <div class="box">
    <div class="header">agentur-77 kostenrechner</div>

    <div id="chat" class="chat">
      <div class="msg bot">{{ $welcome }}</div>
      <div class="msg bot">
        {{ $firstStep['question'] }}
        @if(!empty($firstStep['description']))
          <br><span><i style="color: #6b6868a9; font-size: 0.9em;">{{ $firstStep['description'] }}</i></span>
        @endif
      </div>
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
    downloadBtn.className = 'quick-reply-btn'; 
    downloadBtn.innerHTML = '📄 Kostenlose Kalkulation (PDF) herunterladen';
    downloadBtn.target = '_blank';
    downloadBtn.style.animation = 'slideInUp 0.5s ease-out, pulse 2s infinite';
    downloadBtn.style.marginTop = '20px';
    downloadBtn.style.marginBottom = '10px';
    chat.appendChild(downloadBtn);
    scrollToBottom();
}
    }, 600 + Math.random() * 600); 
    
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