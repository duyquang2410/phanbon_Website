document.addEventListener('DOMContentLoaded', function() {
    // Thêm HTML cho chatbot vào body
    const chatbotHTML = `
        <div class="chatbot-toggle">
            <i class="fas fa-comments"></i>
            <span class="notification-badge hidden">1</span>
        </div>
        <div class="chatbot-container hidden">
            <div class="chatbot-header">
                <span>Tư vấn sản phẩm & bệnh cây trồng</span>
                <div class="header-actions">
                    <i class="fas fa-history" id="clear-chat" title="Xóa lịch sử chat"></i>
                    <i class="fas fa-times" id="close-chatbot"></i>
                </div>
            </div>
            <div class="chatbot-suggestions">
                <p>Câu hỏi gợi ý:</p>
                <div class="suggestion-chips">
                    <button class="chip">Cây lúa bị vàng lá phải làm sao?</button>
                    <button class="chip">Phân bón cho cây cam</button>
                    <button class="chip">Thuốc trị sâu cho rau</button>
                    <button class="chip">Cách phòng bệnh đạo ôn</button>
                </div>
            </div>
            <div class="chatbot-messages">
                <div class="message bot-message">
                    Xin chào! Tôi có thể giúp bạn tư vấn về:
                    <ul>
                        <li>Các loại phân bón và thuốc BVTV</li>
                        <li>Nhận diện và điều trị bệnh cây trồng</li>
                        <li>Hướng dẫn sử dụng sản phẩm</li>
                    </ul>
                    Bạn cần hỗ trợ vấn đề gì?
                </div>
                <div class="typing-indicator hidden">
                    <span></span>
                    <span></span>
                    <span></span>
                </div>
            </div>
            <div class="chatbot-input">
                <textarea placeholder="Nhập câu hỏi của bạn..." rows="1"></textarea>
                <button type="button">
                    <i class="fas fa-paper-plane"></i>
                </button>
            </div>
        </div>
    `;
    document.body.insertAdjacentHTML('beforeend', chatbotHTML);

    // Các elements
    const chatbotToggle = document.querySelector('.chatbot-toggle');
    const notificationBadge = document.querySelector('.notification-badge');
    const chatbotContainer = document.querySelector('.chatbot-container');
    const closeButton = document.querySelector('#close-chatbot');
    const clearButton = document.querySelector('#clear-chat');
    const messageInput = document.querySelector('.chatbot-input textarea');
    const sendButton = document.querySelector('.chatbot-input button');
    const messagesContainer = document.querySelector('.chatbot-messages');
    const typingIndicator = document.querySelector('.typing-indicator');
    const suggestionChips = document.querySelectorAll('.chip');
    const chatbotSuggestions = document.querySelector('.chatbot-suggestions');

    // Auto-resize textarea
    messageInput.addEventListener('input', function() {
        this.style.height = 'auto';
        this.style.height = (this.scrollHeight) + 'px';
    });

    // Toggle chatbot
    chatbotToggle.addEventListener('click', () => {
        chatbotContainer.classList.toggle('hidden');
        chatbotToggle.style.display = chatbotContainer.classList.contains('hidden') ? 'flex' : 'none';
        notificationBadge.classList.add('hidden');
    });

    // Đóng chatbot
    closeButton.addEventListener('click', () => {
        chatbotContainer.classList.add('hidden');
        chatbotToggle.style.display = 'flex';
    });

    // Xóa lịch sử chat
    clearButton.addEventListener('click', () => {
        const confirmClear = confirm('Bạn có chắc muốn xóa lịch sử chat?');
        if (confirmClear) {
            const messages = messagesContainer.querySelectorAll('.message');
            messages.forEach((msg, index) => {
                if (index !== 0) msg.remove(); // Giữ lại tin nhắn chào đầu tiên
            });
            // Hiển thị lại câu hỏi gợi ý khi xóa lịch sử
            chatbotSuggestions.classList.remove('hidden');
        }
    });

    // Xử lý suggestion chips
    suggestionChips.forEach(chip => {
        chip.addEventListener('click', () => {
            messageInput.value = chip.textContent;
            messageInput.dispatchEvent(new Event('input')); // Trigger auto-resize
            sendMessage();
        });
    });

    // Gửi tin nhắn
    function sendMessage() {
        const message = messageInput.value.trim();
        if (!message) return;

        // Ẩn câu hỏi gợi ý khi có tin nhắn
        chatbotSuggestions.classList.add('hidden');

        // Thêm tin nhắn của user
        appendMessage(message, 'user');
        messageInput.value = '';
        messageInput.style.height = 'auto'; // Reset height

        // Hiển thị typing indicator
        typingIndicator.classList.remove('hidden');
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Gọi API
        fetch('/web/phanbon_Website/chatbot.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                    'Accept': 'application/json'
                },
                body: JSON.stringify({ message: message })
            })
            .then(async response => {
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // Try to get the error message from response
                    const text = await response.text();
                    console.error('Non-JSON response:', text);
                    throw new Error('Server returned non-JSON response');
                }
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status);
                }
                return response.json();
            })
            .then(data => {
                typingIndicator.classList.add('hidden');
                if (!data.success) {
                    appendMessage('Xin lỗi, có lỗi xảy ra: ' + (data.error || 'Unknown error'), 'bot');
                } else {
                    if (data.products && Array.isArray(data.products) && data.products.length > 0) {
                        appendMessage(data.response, 'bot', data.products);
                    } else {
                        appendMessage(data.response, 'bot');
                    }
                }
            })
            .catch(error => {
                typingIndicator.classList.add('hidden');
                console.error('Error:', error);
                appendMessage('Xin lỗi, có lỗi xảy ra: ' + error.message, 'bot');
            });
    }

    // Thêm tin nhắn vào container
    function appendMessage(message, sender, products = null) {
        const messageDiv = document.createElement('div');
        messageDiv.classList.add('message', `${sender}-message`);

        // Xử lý markdown đơn giản
        let formattedMessage = message
            .replace(/\*\*(.*?)\*\*/g, '<strong>$1</strong>') // Bold
            .replace(/\*(.*?)\*/g, '<em>$1</em>') // Italic
            .replace(/- (.*?)(?:\n|$)/g, '• $1<br>') // List items
            .replace(/\n/g, '<br>'); // New lines

        // Nếu có sản phẩm, thêm grid sản phẩm
        if (products && products.length > 0) {
            messageDiv.classList.add('has-products');
            let productGrid = '<div class="product-grid">';

            products.forEach(product => {
                console.log('Processing product:', product); // Debug log

                // Sử dụng trực tiếp URL đầy đủ từ SP_HINHANH
                const imagePath = product.SP_HINHANH;
                console.log('Image path:', imagePath);

                productGrid += `
                    <div class="product-card chatbot-product-card">
                        <div class="product-image-container">
                            <img src="${imagePath}" 
                                 alt="${product.SP_TEN}" 
                                 class="product-image"
                                 onerror="this.onerror=null; this.src='img/default-product.jpg';">
                        </div>
                        <div class="product-info">
                            <div class="product-name">${product.SP_TEN}</div>
                            <div class="product-price">${formatPrice(product.SP_DONGIA)} <span class="vnd">VNĐ</span></div>
                            <div class="product-description">${product.SP_MOTA || ''}</div>
                            <div class="product-actions">
                                <a href="/web/phanbon_Website/detail.php?id=${product.SP_MA}" 
                                   class="view-details-btn chatbot-btn" target="_blank">
                                    <i class="fa-solid fa-circle-info"></i>
                                    Chi tiết
                                </a>
                            </div>
                        </div>
                    </div>
                `;
            });

            productGrid += '</div>';
            formattedMessage += productGrid;
        }

        messageDiv.innerHTML = formattedMessage;
        messagesContainer.insertBefore(messageDiv, typingIndicator);
        messagesContainer.scrollTop = messagesContainer.scrollHeight;

        // Hiển thị notification nếu chat đang ẩn
        if (chatbotContainer.classList.contains('hidden') && sender === 'bot') {
            notificationBadge.classList.remove('hidden');
        }

        // Thêm sự kiện click cho các nút
        messageDiv.querySelectorAll('.product-card').forEach(card => {
            card.addEventListener('click', function(e) {
                // Ngăn sự kiện click lan ra ngoài khi nhấn vào nút
                if (!e.target.closest('.product-actions')) {
                    const detailsBtn = this.querySelector('.view-details-btn');
                    if (detailsBtn) {
                        detailsBtn.click();
                    }
                }
            });
        });
    }

    // Format giá tiền
    function formatPrice(price) {
        return new Intl.NumberFormat('vi-VN').format(price);
    }

    // Cập nhật hàm xử lý response
    function handleBotResponse(data) {
        typingIndicator.classList.add('hidden');
        if (!data.success) {
            appendMessage('Xin lỗi, có lỗi xảy ra: ' + (data.error || 'Unknown error'), 'bot');
        } else {
            // Kiểm tra xem response có chứa sản phẩm không
            if (data.products && Array.isArray(data.products)) {
                appendMessage(data.response, 'bot', data.products);
            } else {
                appendMessage(data.response, 'bot');
            }
        }
    }

    // Sự kiện click nút gửi
    sendButton.addEventListener('click', sendMessage);

    // Sự kiện nhấn Enter (không có Shift)
    messageInput.addEventListener('keypress', (e) => {
        if (e.key === 'Enter' && !e.shiftKey) {
            e.preventDefault();
            sendMessage();
        }
    });
});