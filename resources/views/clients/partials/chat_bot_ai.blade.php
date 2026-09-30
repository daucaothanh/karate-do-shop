<!-- ============================================== -->
<!-- SENSEI AI CHATBOT - TRỢ LÝ VÕ THUẬT KARATE-DO -->
<!-- ============================================== -->

<div id="sensei-ai-chatbot" class="sensei-ai-container">
    <!-- Floating Trigger Button -->
    <div id="sensei-chat-launcher" class="sensei-launcher" title="Trò chuyện cùng Trợ lý AI Sensei">
        <div class="sensei-pulse-ring"></div>
        <div class="sensei-launcher-icon">
            <i class="fa-solid fa-robot"></i>
        </div>
        <span class="sensei-badge-online" title="Sensei AI Trực tuyến"></span>
        <div class="sensei-launcher-tooltip">
            <span>🥋 <strong>Sensei AI</strong> sẵn sàng tư vấn võ phục & chọn size!</span>
        </div>
    </div>

    <!-- Chat Box Window -->
    <div id="sensei-chat-window" class="sensei-window d-none">
        <!-- Header -->
        <div class="sensei-header">
            <div class="sensei-header-info">
                <div class="sensei-avatar-wrapper">
                    <div class="sensei-avatar">
                        <i class="fa-solid fa-user-ninja"></i>
                    </div>
                    <span class="sensei-avatar-status"></span>
                </div>
                <div class="sensei-title-wrap">
                    <div class="sensei-title">
                        Sensei AI <span class="sensei-tag-ai">AI 2.0</span>
                    </div>
                    <div class="sensei-subtitle">
                        Trợ lý võ thuật ảo • Trực tuyến 24/7
                    </div>
                </div>
            </div>
            <div class="sensei-header-actions">
                <button type="button" id="sensei-btn-clear" class="sensei-btn-icon" title="Làm mới đoạn hội thoại">
                    <i class="fa-solid fa-rotate-right"></i>
                </button>
                <button type="button" id="sensei-btn-close" class="sensei-btn-icon" title="Thu nhỏ khung chat">
                    <i class="fa-solid fa-xmark"></i>
                </button>
            </div>
        </div>

        <!-- Body / Messages -->
        <div id="sensei-chat-body" class="sensei-body">
            <!-- Messages will be rendered here dynamically -->
        </div>

        <!-- Typing Indicator -->
        <div id="sensei-typing" class="sensei-typing-wrap d-none">
            <div class="sensei-avatar-mini">
                <i class="fa-solid fa-user-ninja"></i>
            </div>
            <div class="sensei-typing-bubble">
                <span class="sensei-dot"></span>
                <span class="sensei-dot"></span>
                <span class="sensei-dot"></span>
            </div>
        </div>

        <!-- Suggestions Chips Bar -->
        <div id="sensei-quick-suggestions" class="sensei-suggestions-bar">
            <!-- Rendered dynamically -->
        </div>

        <!-- Footer / Input Bar -->
        <div class="sensei-footer">
            <form id="sensei-chat-form" class="sensei-form" onsubmit="return false;">
                <input 
                    type="text" 
                    id="sensei-chat-input" 
                    class="sensei-input" 
                    placeholder="Hỏi Sensei về size, sản phẩm mới..." 
                    autocomplete="off"
                    maxlength="400"
                />
                <button type="submit" id="sensei-btn-send" class="sensei-btn-send" title="Gửi tin nhắn">
                    <i class="fa-solid fa-paper-plane"></i>
                </button>
            </form>
            <div class="sensei-footer-note">
                <span>Trợ lý AI Karate-Do • Hotline: <a href="tel:0967137200">0967.137.200</a></span>
            </div>
        </div>
    </div>
</div>

<style>
/* ========================================================
   SENSEI AI CHATBOT STYLES (COMPACT & MODERN)
   ======================================================== */
.sensei-ai-container {
    position: fixed;
    bottom: 20px;
    right: 20px;
    z-index: 999999;
    font-family: -apple-system, BlinkMacSystemFont, "Segoe UI", Roboto, "Helvetica Neue", Arial, sans-serif;
}

/* Ensure template scrollUp button sits nicely above chat if shown */
#scrollUp {
    bottom: 78px !important;
    right: 22px !important;
    z-index: 999990 !important;
}

/* Floating Launcher */
.sensei-launcher {
    position: relative;
    width: 48px;
    height: 48px;
    border-radius: 50%;
    background: linear-gradient(135deg, #d32f2f 0%, #991b1b 100%);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    cursor: pointer;
    box-shadow: 0 6px 18px rgba(211, 47, 47, 0.4);
    transition: all 0.3s cubic-bezier(0.34, 1.56, 0.64, 1);
    user-select: none;
}

.sensei-launcher:hover {
    transform: scale(1.06) translateY(-2px);
    box-shadow: 0 10px 22px rgba(211, 47, 47, 0.55);
}

.sensei-launcher-icon {
    font-size: 21px;
    animation: senseiFloat 3s ease-in-out infinite;
}

.sensei-pulse-ring {
    position: absolute;
    top: -3px;
    left: -3px;
    right: -3px;
    bottom: -3px;
    border-radius: 50%;
    border: 2px solid rgba(239, 68, 68, 0.6);
    animation: senseiPulse 2s cubic-bezier(0.24, 0, 0.38, 1) infinite;
}

.sensei-badge-online {
    position: absolute;
    top: 1px;
    right: 1px;
    width: 12px;
    height: 12px;
    background: #22c55e;
    border: 2px solid #ffffff;
    border-radius: 50%;
    box-shadow: 0 0 0 2px rgba(34, 197, 94, 0.2);
}

.sensei-launcher-tooltip {
    position: absolute;
    right: 62px;
    top: 50%;
    transform: translateY(-50%);
    background: #0f172a;
    color: #f8fafc;
    font-size: 12px;
    padding: 6px 12px;
    border-radius: 16px;
    white-space: nowrap;
    box-shadow: 0 6px 16px rgba(0, 0, 0, 0.22);
    opacity: 0;
    visibility: hidden;
    transition: all 0.25s ease;
    pointer-events: none;
    border: 1px solid #334155;
}

.sensei-launcher:hover .sensei-launcher-tooltip {
    opacity: 1;
    visibility: visible;
    right: 58px;
}

/* Chat Window */
.sensei-window {
    position: absolute;
    bottom: 58px;
    right: 0;
    width: 335px;
    height: 460px;
    max-width: calc(100vw - 28px);
    max-height: calc(100vh - 90px);
    background: #ffffff;
    border-radius: 16px;
    box-shadow: 0 12px 36px rgba(15, 23, 42, 0.2), 0 4px 12px rgba(0, 0, 0, 0.06);
    border: 1px solid #e2e8f0;
    display: flex;
    flex-direction: column;
    overflow: hidden;
    transform-origin: bottom right;
    animation: senseiSlideUp 0.28s cubic-bezier(0.16, 1, 0.3, 1);
}

/* Header */
.sensei-header {
    background: linear-gradient(135deg, #0f172a 0%, #1e293b 70%, #991b1b 100%);
    color: #ffffff;
    padding: 10px 12px;
    display: flex;
    align-items: center;
    justify-content: space-between;
    border-bottom: 1px solid rgba(255, 255, 255, 0.1);
    flex-shrink: 0;
}

.sensei-header-info {
    display: flex;
    align-items: center;
    gap: 9px;
}

.sensei-avatar-wrapper {
    position: relative;
}

.sensei-avatar {
    width: 32px;
    height: 32px;
    border-radius: 9px;
    background: linear-gradient(135deg, #d32f2f, #b91c1c);
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 15px;
    box-shadow: 0 3px 8px rgba(211, 47, 47, 0.35);
}

.sensei-avatar-status {
    position: absolute;
    bottom: -1px;
    right: -1px;
    width: 9px;
    height: 9px;
    border-radius: 50%;
    background: #22c55e;
    border: 1.5px solid #0f172a;
}

.sensei-title {
    font-size: 13.5px;
    font-weight: 700;
    color: #ffffff;
    display: flex;
    align-items: center;
    gap: 5px;
    line-height: 1.2;
}

.sensei-tag-ai {
    font-size: 8.5px;
    font-weight: 800;
    background: rgba(239, 68, 68, 0.3);
    color: #fca5a5;
    border: 1px solid rgba(248, 113, 113, 0.4);
    padding: 1px 5px;
    border-radius: 8px;
    letter-spacing: 0.4px;
}

.sensei-subtitle {
    font-size: 10.5px;
    color: #cbd5e1;
    margin-top: 1px;
    line-height: 1.2;
}

.sensei-header-actions {
    display: flex;
    gap: 4px;
}

.sensei-btn-icon {
    width: 26px;
    height: 26px;
    border-radius: 6px;
    background: rgba(255, 255, 255, 0.1);
    color: #e2e8f0;
    border: none;
    cursor: pointer;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 12px;
    transition: all 0.2s ease;
}

.sensei-btn-icon:hover {
    background: rgba(255, 255, 255, 0.22);
    color: #ffffff;
    transform: scale(1.05);
}

/* Chat Body */
.sensei-body {
    flex: 1;
    padding: 10px 10px;
    overflow-y: auto;
    background: #f8fafc;
    display: flex;
    flex-direction: column;
    gap: 9px;
    scroll-behavior: smooth;
}

.sensei-body::-webkit-scrollbar {
    width: 4px;
}
.sensei-body::-webkit-scrollbar-track {
    background: transparent;
}
.sensei-body::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 4px;
}

/* Message Rows */
.sensei-msg-row {
    display: flex;
    gap: 7px;
    align-items: flex-start;
    animation: senseiFadeIn 0.22s ease-out;
}

.sensei-msg-row.sensei-row-user {
    justify-content: flex-end;
}

.sensei-msg-avatar {
    width: 24px;
    height: 24px;
    border-radius: 6px;
    background: #d32f2f;
    color: #ffffff;
    display: flex;
    align-items: center;
    justify-content: center;
    font-size: 11px;
    flex-shrink: 0;
    margin-top: 2px;
}

.sensei-msg-bubble {
    max-width: 85%;
    padding: 8px 11px;
    border-radius: 13px;
    font-size: 12px;
    line-height: 1.45;
    word-break: break-word;
}

.sensei-row-bot .sensei-msg-bubble {
    background: #ffffff;
    color: #1e293b;
    border-top-left-radius: 3px;
    border: 1px solid #e2e8f0;
    box-shadow: 0 1.5px 4px rgba(0, 0, 0, 0.03);
}

.sensei-row-user .sensei-msg-bubble {
    background: linear-gradient(135deg, #d32f2f 0%, #b91c1c 100%);
    color: #ffffff;
    border-top-right-radius: 3px;
    box-shadow: 0 2px 8px rgba(211, 47, 47, 0.25);
}

.sensei-msg-time {
    font-size: 9.5px;
    margin-top: 3px;
    opacity: 0.65;
    text-align: right;
}

/* Product Cards Carousel inside Chat */
.sensei-products-wrap {
    display: flex;
    flex-direction: column;
    gap: 6px;
    margin-top: 7px;
    width: 100%;
}

.sensei-prod-card {
    display: flex;
    align-items: center;
    gap: 8px;
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 8px;
    padding: 6px 7px;
    transition: all 0.2s ease;
    text-decoration: none !important;
}

.sensei-prod-card:hover {
    border-color: #d32f2f;
    box-shadow: 0 3px 10px rgba(211, 47, 47, 0.12);
    transform: translateY(-1px);
}

.sensei-prod-img {
    width: 42px;
    height: 42px;
    border-radius: 5px;
    object-fit: contain;
    background: #f1f5f9;
    flex-shrink: 0;
}

.sensei-prod-info {
    flex: 1;
    min-width: 0;
}

.sensei-prod-cat {
    font-size: 9px;
    font-weight: 700;
    color: #64748b;
    text-transform: uppercase;
    letter-spacing: 0.4px;
}

.sensei-prod-name {
    font-size: 11.5px;
    font-weight: 700;
    color: #0f172a;
    white-space: nowrap;
    overflow: hidden;
    text-overflow: ellipsis;
    line-height: 1.25;
}

.sensei-prod-price {
    font-size: 11.5px;
    font-weight: 800;
    color: #d32f2f;
    margin-top: 1px;
}

.sensei-prod-action {
    color: #d32f2f;
    font-size: 11px;
    padding-right: 2px;
}

/* Typing Indicator */
.sensei-typing-wrap {
    display: flex;
    align-items: center;
    gap: 6px;
    padding: 0 10px 6px;
}

.sensei-avatar-mini {
    width: 20px;
    height: 20px;
    border-radius: 5px;
    background: #d32f2f;
    color: #ffffff;
    font-size: 10px;
    display: flex;
    align-items: center;
    justify-content: center;
}

.sensei-typing-bubble {
    background: #ffffff;
    border: 1px solid #e2e8f0;
    border-radius: 10px;
    padding: 5px 10px;
    display: flex;
    align-items: center;
    gap: 3px;
}

.sensei-dot {
    width: 5px;
    height: 5px;
    background: #94a3b8;
    border-radius: 50%;
    animation: senseiBlink 1.4s infinite ease-in-out both;
}

.sensei-dot:nth-child(1) { animation-delay: -0.32s; }
.sensei-dot:nth-child(2) { animation-delay: -0.16s; }

/* Suggestions Chips Bar */
.sensei-suggestions-bar {
    padding: 5px 8px 6px;
    background: #f8fafc;
    display: flex;
    gap: 5px;
    overflow-x: auto;
    white-space: nowrap;
    border-top: 1px dashed #e2e8f0;
    flex-shrink: 0;
}

.sensei-suggestions-bar::-webkit-scrollbar {
    height: 3px;
}
.sensei-suggestions-bar::-webkit-scrollbar-thumb {
    background: #cbd5e1;
    border-radius: 3px;
}

.sensei-chip {
    display: inline-block;
    background: #ffffff;
    color: #334155;
    border: 1px solid #cbd5e1;
    border-radius: 14px;
    padding: 3px 9px;
    font-size: 11px;
    font-weight: 500;
    cursor: pointer;
    transition: all 0.2s ease;
    flex-shrink: 0;
    line-height: 1.4;
}

.sensei-chip:hover {
    background: #fee2e2;
    color: #d32f2f;
    border-color: #fca5a5;
    transform: translateY(-1px);
}

/* Footer / Input Bar - Strong resets to defeat global style.css */
.sensei-footer {
    background: #ffffff;
    padding: 7px 10px 6px;
    border-top: 1px solid #e2e8f0;
    flex-shrink: 0;
}

#sensei-ai-chatbot .sensei-form {
    display: flex !important;
    align-items: center !important;
    gap: 6px !important;
    background: #f8fafc !important;
    border: 1.5px solid #e2e8f0 !important;
    border-radius: 20px !important;
    padding: 2px 3px 2px 10px !important;
    margin: 0 !important;
    height: 34px !important;
    min-height: 34px !important;
    max-height: 34px !important;
    box-sizing: border-box !important;
    transition: all 0.2s ease !important;
}

#sensei-ai-chatbot .sensei-form:focus-within {
    border-color: #d32f2f !important;
    background: #ffffff !important;
    box-shadow: 0 0 0 2px rgba(211, 47, 47, 0.12) !important;
}

#sensei-ai-chatbot input[type="text"].sensei-input,
.sensei-input {
    flex: 1 !important;
    width: 100% !important;
    height: 28px !important;
    min-height: 28px !important;
    max-height: 28px !important;
    line-height: 28px !important;
    margin: 0 !important;
    margin-bottom: 0 !important;
    padding: 0 4px !important;
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
    background: transparent !important;
    font-size: 12px !important;
    color: #0f172a !important;
    box-sizing: border-box !important;
}

#sensei-ai-chatbot input[type="text"].sensei-input:focus,
.sensei-input:focus {
    border: none !important;
    outline: none !important;
    box-shadow: none !important;
}

#sensei-ai-chatbot .sensei-input::placeholder {
    font-size: 11.5px !important;
    color: #94a3b8 !important;
}

#sensei-ai-chatbot .sensei-btn-send {
    width: 28px !important;
    height: 28px !important;
    min-width: 28px !important;
    max-width: 28px !important;
    border-radius: 50% !important;
    background: linear-gradient(135deg, #d32f2f, #b91c1c) !important;
    color: #ffffff !important;
    border: none !important;
    outline: none !important;
    cursor: pointer !important;
    display: flex !important;
    align-items: center !important;
    justify-content: center !important;
    font-size: 11px !important;
    transition: all 0.2s ease !important;
    flex-shrink: 0 !important;
    margin: 0 !important;
    padding: 0 !important;
}

#sensei-ai-chatbot .sensei-btn-send:hover {
    transform: scale(1.08) !important;
    box-shadow: 0 2px 8px rgba(211, 47, 47, 0.4) !important;
}

.sensei-footer-note {
    font-size: 9.5px;
    color: #94a3b8;
    text-align: center;
    margin-top: 4px;
    line-height: 1.2;
}

.sensei-footer-note a {
    color: #d32f2f;
    font-weight: 600;
    text-decoration: none;
}

/* Animations */
@keyframes senseiFloat {
    0%, 100% { transform: translateY(0); }
    50% { transform: translateY(-3px); }
}

@keyframes senseiPulse {
    0% { transform: scale(1); opacity: 0.8; }
    100% { transform: scale(1.35); opacity: 0; }
}

@keyframes senseiSlideUp {
    from { opacity: 0; transform: translateY(16px) scale(0.96); }
    to { opacity: 1; transform: translateY(0) scale(1); }
}

@keyframes senseiFadeIn {
    from { opacity: 0; transform: translateY(6px); }
    to { opacity: 1; transform: translateY(0); }
}

@keyframes senseiBlink {
    0%, 80%, 100% { transform: scale(0); }
    40% { transform: scale(1); }
}

@media (max-width: 480px) {
    .sensei-ai-container {
        bottom: 14px;
        right: 12px;
    }
    .sensei-window {
        width: calc(100vw - 24px);
        height: calc(100vh - 76px);
        bottom: 54px;
        right: 0;
        border-radius: 14px;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function () {
    const launcher = document.getElementById('sensei-chat-launcher');
    const chatWindow = document.getElementById('sensei-chat-window');
    const btnClose = document.getElementById('sensei-btn-close');
    const btnClear = document.getElementById('sensei-btn-clear');
    const chatBody = document.getElementById('sensei-chat-body');
    const chatForm = document.getElementById('sensei-chat-form');
    const chatInput = document.getElementById('sensei-chat-input');
    const suggestionsBar = document.getElementById('sensei-quick-suggestions');
    const typingIndicator = document.getElementById('sensei-typing');

    const STORAGE_KEY = 'karate_sensei_chat_history_v1';

    const defaultSuggestions = [
        '🥋 Tư vấn size võ phục',
        '✨ Xem sản phẩm mới nhất',
        '🥊 Đồ bảo hộ thi đấu',
        '💰 Bảng giá võ phục & đai',
        '🚚 Phí & thời gian giao hàng',
        '🔄 Chính sách đổi trả size',
        '📞 Hotline liên hệ tư vấn'
    ];

    // Toggle Chat Window
    launcher.addEventListener('click', function () {
        if (chatWindow.classList.contains('d-none')) {
            chatWindow.classList.remove('d-none');
            chatInput.focus();
            scrollToBottom();
        } else {
            chatWindow.classList.add('d-none');
        }
    });

    btnClose.addEventListener('click', function () {
        chatWindow.classList.add('d-none');
    });

    // Clear Chat History
    btnClear.addEventListener('click', function () {
        if (confirm('Bạn có muốn làm mới cuộc trò chuyện với Sensei AI không?')) {
            sessionStorage.removeItem(STORAGE_KEY);
            chatBody.innerHTML = '';
            initWelcomeMessage();
            renderSuggestions(defaultSuggestions);
        }
    });

    // Initialize Welcome Message
    function initWelcomeMessage() {
        const welcomeText = "Oss! 🙏 Chào bạn! Tôi là **Sensei AI** - Trợ lý võ thuật ảo của Karate-Do Shop.\n\nTôi có thể giúp bạn giải đáp:\n• 🥋 **Chọn size võ phục chuẩn** theo chiều cao & cân nặng\n• ✨ **Cập nhật sản phẩm mới nhất** vừa cập bến\n• 🥊 **Tư vấn đồ bảo hộ & đai thi đấu** chuẩn WKF\n• 🚚 **Chính sách giao hàng & đổi size miễn phí 7 ngày**\n\nBạn đang tìm kiếm thông tin gì hôm nay?";
        appendMessage('bot', welcomeText, [], getCurrentTime(), false);
    }

    // Render quick suggestion pills
    function renderSuggestions(list) {
        suggestionsBar.innerHTML = '';
        if (!list || list.length === 0) {
            list = defaultSuggestions;
        }
        list.forEach(function (text) {
            const chip = document.createElement('button');
            chip.type = 'button';
            chip.className = 'sensei-chip';
            chip.innerText = text;
            chip.addEventListener('click', function () {
                sendMessage(text);
            });
            suggestionsBar.appendChild(chip);
        });
    }

    // Get current time formatted HH:MM
    function getCurrentTime() {
        const now = new Date();
        return now.getHours().toString().padStart(2, '0') + ':' + now.getMinutes().toString().padStart(2, '0');
    }

    // Parse Markdown bold and linebreaks
    function formatMarkdown(text) {
        if (!text) return '';
        let escaped = text
            .replace(/&/g, '&amp;')
            .replace(/</g, '&lt;')
            .replace(/>/g, '&gt;');

        // bold **text**
        escaped = escaped.replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>');
        // bullet point lines
        escaped = escaped.replace(/^• (.*$)/gim, '<div style="padding-left: 6px; margin: 2px 0;">• $1</div>');
        escaped = escaped.replace(/^- (.*$)/gim, '<div style="padding-left: 6px; margin: 2px 0;">- $1</div>');
        // newline
        escaped = escaped.replace(/\n/g, '<br>');
        return escaped;
    }

    // Append Message to UI
    function appendMessage(sender, text, products = [], time = '', save = true) {
        const row = document.createElement('div');
        row.className = 'sensei-msg-row ' + (sender === 'user' ? 'sensei-row-user' : 'sensei-row-bot');

        let avatarHtml = '';
        if (sender === 'bot') {
            avatarHtml = '<div class="sensei-msg-avatar"><i class="fa-solid fa-user-ninja"></i></div>';
        }

        let productsHtml = '';
        if (products && products.length > 0) {
            productsHtml = '<div class="sensei-products-wrap">';
            products.forEach(function (p) {
                productsHtml += `
                    <a href="${p.url}" class="sensei-prod-card" target="_blank" title="Xem chi tiết ${p.name}">
                        <img src="${p.image}" alt="${p.name}" class="sensei-prod-img" onerror="this.src='{{ asset('assets/clients/img/karate/vo-phuc-rikaido.png') }}'">
                        <div class="sensei-prod-info">
                            <div class="sensei-prod-cat">${p.category}</div>
                            <div class="sensei-prod-name">${p.name}</div>
                            <div class="sensei-prod-price">${p.price}</div>
                        </div>
                        <div class="sensei-prod-action">
                            <i class="fa-solid fa-chevron-right"></i>
                        </div>
                    </a>
                `;
            });
            productsHtml += '</div>';
        }

        row.innerHTML = `
            ${avatarHtml}
            <div class="sensei-msg-bubble">
                <div class="sensei-msg-text">${formatMarkdown(text)}</div>
                ${productsHtml}
                <div class="sensei-msg-time">${time || getCurrentTime()}</div>
            </div>
        `;

        chatBody.appendChild(row);
        scrollToBottom();

        if (save) {
            saveHistory({ sender, text, products, time: time || getCurrentTime() });
        }
    }

    function scrollToBottom() {
        chatBody.scrollTop = chatBody.scrollHeight;
    }

    // Save chat history to sessionStorage
    function saveHistory(msgObj) {
        let history = [];
        try {
            const raw = sessionStorage.getItem(STORAGE_KEY);
            if (raw) history = JSON.parse(raw);
        } catch (e) {}
        history.push(msgObj);
        // keep at most 30 messages
        if (history.length > 30) history = history.slice(-30);
        sessionStorage.setItem(STORAGE_KEY, JSON.stringify(history));
    }

    // Load history
    function loadHistory() {
        try {
            const raw = sessionStorage.getItem(STORAGE_KEY);
            if (raw) {
                const history = JSON.parse(raw);
                if (history && history.length > 0) {
                    history.forEach(function (item) {
                        appendMessage(item.sender, item.text, item.products, item.time, false);
                    });
                    renderSuggestions(defaultSuggestions);
                    return;
                }
            }
        } catch (e) {}
        initWelcomeMessage();
        renderSuggestions(defaultSuggestions);
    }

    // Send Message function
    function sendMessage(customText) {
        const text = (customText !== undefined ? customText : chatInput.value).trim();
        if (!text) return;

        // Render user message
        appendMessage('user', text);
        if (customText === undefined) {
            chatInput.value = '';
        }

        // Show typing
        typingIndicator.classList.remove('d-none');
        scrollToBottom();

        // AJAX POST to /chatbot/message
        fetch('{{ route('chatbot.message') }}', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': '{{ csrf_token() }}',
                'Accept': 'application/json'
            },
            body: JSON.stringify({ message: text })
        })
        .then(function (res) {
            if (!res.ok) throw new Error('HTTP error ' + res.status);
            return res.json();
        })
        .then(function (data) {
            typingIndicator.classList.add('d-none');
            if (data.success) {
                appendMessage('bot', data.reply, data.products || []);
                if (data.suggestions && data.suggestions.length > 0) {
                    renderSuggestions(data.suggestions);
                }
            } else {
                appendMessage('bot', data.reply || 'Oss! Có lỗi xảy ra, bạn vui lòng thử lại sau nhé.');
            }
        })
        .catch(function (err) {
            typingIndicator.classList.add('d-none');
            appendMessage('bot', 'Oss! 🙏 Tạm thời không thể kết nối đến máy chủ AI. Bạn vui lòng liên hệ trực tiếp Hotline **0967.137.200** để được hỗ trợ tức thì nhé!');
        });
    }

    // Form Submit
    chatForm.addEventListener('submit', function (e) {
        e.preventDefault();
        sendMessage();
    });

    // Start
    loadHistory();
});
</script>
<!-- ============================================== -->
<!-- END SENSEI AI CHATBOT                          -->
<!-- ============================================== -->
