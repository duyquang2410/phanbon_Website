// Quản lý mã khuyến mãi
class PromoManager {
    constructor() {
        this.initializeElements();
        this.setupEventListeners();
    }

    initializeElements() {
        // Promo elements
        this.promoInput = document.getElementById('promoInput');
        this.promoType = document.getElementById('promoType');
        this.applyPromoButton = document.getElementById('applyPromo');
        this.promoModal = document.getElementById('promoModal');
        this.promoItems = document.querySelectorAll('.promo-item');
        this.promoError = document.querySelector('.promo-error');

        // Hidden inputs
        this.promoCodeInput = document.getElementById('promoCodeInput');
        this.promoCodeItemInputs = document.querySelectorAll('.promo-code-item-hidden');

        // Total elements
        this.totalAmountInput = document.querySelector('input[name="total_amount"]');
        this.totalPaymentSpan = document.getElementById('total-payment');
        this.shippingFeeInput = document.querySelector('input[name="shipping_fee"]');

        // Discount elements
        this.discountRow = document.getElementById('discount-row');
        this.discountAmount = document.getElementById('discount-amount');
        this.discountPercent = document.getElementById('discount-percent');
        this.discountPercentText = document.getElementById('discount-percent-text');

        // Initialize total payment display
        if (this.totalAmountInput && this.shippingFeeInput && this.totalPaymentSpan) {
            const totalAmount = parseFloat(this.totalAmountInput.value) || 0;
            const shippingFee = parseFloat(this.shippingFeeInput.value) || 0;
            this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN').format(totalAmount + shippingFee) + 'đ';
        }

        console.log('PromoManager elements initialized:', {
            promoInput: !!this.promoInput,
            promoType: !!this.promoType,
            applyPromoButton: !!this.applyPromoButton,
            promoModal: !!this.promoModal,
            promoError: !!this.promoError,
            promoCodeInput: !!this.promoCodeInput,
            totalAmountInput: !!this.totalAmountInput,
            totalPaymentSpan: !!this.totalPaymentSpan,
            shippingFeeInput: !!this.shippingFeeInput,
            discountRow: !!this.discountRow,
            discountAmount: !!this.discountAmount,
            discountPercent: !!this.discountPercent,
            discountPercentText: !!this.discountPercentText
        });
    }

    setupEventListeners() {
        // Apply promo button
        if (this.applyPromoButton) {
            this.applyPromoButton.addEventListener('click', () => this.applyPromo());
        }

        // Promo items in modal
        this.promoItems.forEach(item => {
            item.addEventListener('click', (e) => this.handlePromoItemClick(e));
        });

        // Listen for shipping fee changes
        document.addEventListener('shippingFeeChanged', (event) => {
            console.log('PromoManager - Received shipping fee change:', event.detail);
            const shippingFee = event.detail.shippingFee;
            const currentDiscount = this.getCurrentDiscount();
            const currentDiscountPercent = this.getCurrentDiscountPercent();

            this.updateTotalPayment(currentDiscount, currentDiscountPercent, shippingFee);
        });

        console.log('PromoManager event listeners set up');
    }

    getCurrentDiscount() {
        if (!this.discountAmount) return 0;
        const discountText = this.discountAmount.textContent.replace(/[^\d]/g, '');
        return parseFloat(discountText) || 0;
    }

    getCurrentDiscountPercent() {
        if (!this.discountPercent) return 0;
        return parseFloat(this.discountPercent.textContent) || 0;
    }

    updateTotalPayment(discount = 0, discountPercent = 0, shippingFee = null) {
        console.log('PromoManager - Updating total payment:', {
            discount,
            discountPercent,
            shippingFee
        });

        if (!this.totalAmountInput || !this.totalPaymentSpan) return;

        const totalAmount = parseFloat(this.totalAmountInput.value) || 0;
        const currentShippingFee = shippingFee !== null ? shippingFee :
            (parseFloat(this.shippingFeeInput ? this.shippingFeeInput.value : '0') || 0);

        // Ẩn/hiện phần giảm giá
        const serverDiscountSection = document.getElementById('server-discount-section');
        if (serverDiscountSection) {
            serverDiscountSection.style.display = discount > 0 ? 'none' : '';
        }

        // Hiển thị giảm giá động
        if (this.discountRow && this.discountAmount && this.discountPercent && this.discountPercentText) {
            if (discount > 0) {
                this.discountAmount.textContent = new Intl.NumberFormat('vi-VN').format(discount);
                if (discountPercent > 0) {
                    this.discountPercent.textContent = discountPercent;
                    this.discountPercentText.style.display = 'inline';
                } else {
                    this.discountPercentText.style.display = 'none';
                }
                this.discountRow.style.display = 'flex';
                this.discountRow.classList.add('discount-animation');
                setTimeout(() => {
                    this.discountRow.classList.remove('discount-animation');
                }, 500);
            } else {
                this.discountRow.style.display = 'none';
            }
        }

        // Tính tổng tiền và cập nhật hiển thị
        const total = totalAmount + currentShippingFee - discount;
        const formattedTotal = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
        this.totalPaymentSpan.textContent = formattedTotal;

        console.log('PromoManager - Updated total payment:', {
            totalAmount,
            currentShippingFee,
            discount,
            total,
            formattedTotal
        });
    }

    async applyPromo() {
        const promoCode = this.promoInput.value.trim();
        const promoType = this.promoType.value;
        const totalAmount = this.totalAmountInput ? this.totalAmountInput.value : 0;

        console.log('Applying promo code:', {
            promoCode,
            promoType,
            totalAmount
        });

        if (!promoCode) {
            this.showError('Vui lòng nhập mã khuyến mãi');
            return;
        }

        try {
            // Lấy giá trị sản phẩm nếu đang áp dụng cho sản phẩm cụ thể
            let productAmount = 0;
            if (promoType !== 'all') {
                const productPrice = document.querySelector(`input[name="product_price[${promoType}]"]`);
                const productQuantity = document.querySelector(`input[name="product_quantity[${promoType}]"]`);
                if (productPrice && productQuantity) {
                    productAmount = parseFloat(productPrice.value) * parseInt(productQuantity.value);
                }
            }

            const response = await fetch('check_promo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    promo_code: promoCode,
                    type: promoType,
                    total_amount: promoType === 'all' ? totalAmount : productAmount,
                    product_id: promoType !== 'all' ? promoType : null
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Promo response:', data);

            if (data.success) {
                const checkoutForm = document.getElementById('checkoutForm');
                if (!checkoutForm) {
                    throw new Error('Checkout form not found');
                }

                if (promoType === 'all') {
                    // Áp dụng cho toàn bộ đơn hàng
                    if (this.promoCodeInput) {
                        this.promoCodeInput.value = promoCode;
                        console.log('Updated promo code input:', this.promoCodeInput.value);
                    }
                } else {
                    // Áp dụng cho sản phẩm cụ thể
                    let productPromoInput = document.querySelector(`input[name="promo_code_item[${promoType}]"]`);
                    if (!productPromoInput) {
                        productPromoInput = document.createElement('input');
                        productPromoInput.type = 'hidden';
                        productPromoInput.name = `promo_code_item[${promoType}]`;
                        checkoutForm.appendChild(productPromoInput);
                    }
                    productPromoInput.value = promoCode;
                    console.log('Updated product promo input:', productPromoInput.value);
                }

                // Hiển thị thông báo thành công
                this.showSuccess(data.message);

                // Cập nhật hiển thị giảm giá
                if (promoType === 'all') {
                    this.updateTotalDiscount(data.discount, data.discount_percent);
                } else {
                    this.updateProductDiscount(promoType, data.discount, data.discount_percent);
                }
            } else {
                this.showError(data.message);
            }
        } catch (error) {
            console.error('Error applying promo:', error);
            this.showError('Có lỗi xảy ra khi áp dụng mã khuyến mãi');
        }
    }

    handlePromoItemClick(event) {
        event.preventDefault();
        const promoCode = event.currentTarget.dataset.code;
        const promoDiscount = event.currentTarget.dataset.discount;

        console.log('Promo item clicked:', {
            promoCode,
            promoDiscount
        });

        if (this.promoInput) {
            this.promoInput.value = promoCode;
        }
        if (this.promoModal) {
            const bootstrapModal = bootstrap.Modal.getInstance(this.promoModal);
            if (bootstrapModal) {
                bootstrapModal.hide();
            }
        }
        this.applyPromo();
    }

    showError(message) {
        console.log('Showing error:', message);
        if (this.promoError) {
            this.promoError.textContent = message;
            this.promoError.classList.remove('text-success', 'show');
            this.promoError.classList.add('text-danger', 'show');
        }
    }

    showSuccess(message) {
        console.log('Showing success:', message);
        if (this.promoError) {
            this.promoError.textContent = message;
            this.promoError.classList.remove('text-danger', 'show');
            this.promoError.classList.add('text-success', 'show');
        }
    }

    updateTotalDiscount(discount, discountPercent) {
        if (!this.totalAmountInput) return;

        const totalAmount = parseFloat(this.totalAmountInput.value) || 0;
        const shippingFee = parseFloat(this.shippingFeeInput ? this.shippingFeeInput.value : '0') || 0;

        // Update total payment with new discount
        this.updateTotalPayment(discount, discountPercent, shippingFee);

        // Update hidden discount input if it exists
        const discountInput = document.querySelector('input[name="total_discount"]');
        if (discountInput) {
            discountInput.value = discount;
        }
    }

    updateProductDiscount(productId, discount, discountPercent) {
        // Update product-specific discount display if needed
        const productDiscountElement = document.querySelector(`#product-discount-${productId}`);
        if (productDiscountElement) {
            productDiscountElement.textContent = new Intl.NumberFormat('vi-VN').format(discount);
            productDiscountElement.parentElement.style.display = discount > 0 ? 'block' : 'none';
        }

        // Update total payment
        this.updateTotalPayment();
    }
}

// Khởi tạo PromoManager khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing PromoManager...');
    try {
        window.promoManager = new PromoManager();
    } catch (error) {
        console.error('Error initializing PromoManager:', error);
    }
});