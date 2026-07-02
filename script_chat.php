
<!-- Floating Chatbot UI -->
<div id="chatbot" style="position: fixed; bottom: 20px; right: 20px; z-index: 1000;">
  <!-- Floating Button -->
  <button onclick="toggleBot()" style="background:#002b5c;color:white;padding:12px 16px;border:none;border-radius:50%;font-size:20px;">💬</button>
  
  <!-- Chat Window -->
  <div id="chatWindow" style="display:none;padding:10px;width:320px;height:400px;background:#fff;border:1px solid #ccc;border-radius:10px;box-shadow:0 0 20px rgba(0,0,0,0.2);font-family:sans-serif;flex-direction:column;position:absolute;bottom:60px;right:0;">
    
    <!-- Header -->
    <div style="background:#002b5c;color:white;padding:10px;border-radius:10px 10px 0 0;display:flex;justify-content:space-between;align-items:center;">
      <strong>💬 Smart Support</strong>
      <span class="float-end" onclick="toggleBot()" style="cursor:pointer;">❌</span>
    </div>

    <!-- Chat Messages -->
    <div id="messages" style="height:290px;overflow-y:auto;padding:10px;background:#f9f9f9;"></div>

    <!-- Input Area -->
    <div style="display:flex;">
<input id="userInput" type="text" placeholder="Type your question..." style="flex:1;padding:8px;border:none;" onkeydown="if(event.key === 'Enter') processInput();">

      <button onclick="processInput()" style="background:#002b5c;color:white;border:none;padding:8px;">Send</button>
    </div>
  </div>
</div>

<script>
  let faqList = [];


  fetch('chat.json')
    .then(response => response.json())
    .then(data => faqList = data)
    .catch(err => console.error("Failed to load FAQ:", err));

  function toggleBot() {
    const win = document.getElementById("chatWindow");
    win.style.display = win.style.display === "none" ? "flex" : "none";
  }

  function processInput() {
    const input = document.getElementById("userInput");
    const query = input.value.toLowerCase().trim();
    appendMessage("You", query);
    input.value = "";

    let found = false;
    for (let item of faqList) {
      for (let keyword of item.keywords) {
        if (query.includes(keyword.toLowerCase())) {
          appendMessage("CFI", item.answer);
          found = true;
          break;
        }
      }
      if (found) break;
    }

    if (!found) {
      appendMessage("CFI", "Sorry, I couldn't understand that. Try asking about courses, admissions, or hostel.");
    }
  }

  function appendMessage(sender, text) {
    const box = document.getElementById("messages");
    const msg = document.createElement("div");
    msg.innerHTML = `<strong>${sender}:</strong> ${text}`;
    msg.style.margin = "10px 0";
    box.appendChild(msg);
    box.scrollTop = box.scrollHeight;
  }
</script>
