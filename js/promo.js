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

        console.log('PromoManager event listeners set up');
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
            const response = await fetch('check_promo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    promo_code: promoCode,
                    type: promoType,
                    total_amount: totalAmount,
                    product_id: promoType !== 'all' ? promoType : null
                })
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('Promo response:', data);

            if (data.success) {
                if (promoType === 'all') {
                    // Áp dụng cho toàn bộ đơn hàng
                    if (this.promoCodeInput) {
                        this.promoCodeInput.value = promoCode;
                    }
                    if (this.promoCodeItemInputs) {
                        this.promoCodeItemInputs.forEach(input => input.value = '');
                    }
                } else {
                    // Áp dụng cho sản phẩm cụ thể
                    if (this.promoCodeInput) {
                        this.promoCodeInput.value = '';
                    }
                    if (this.promoCodeItemInputs) {
                        this.promoCodeItemInputs.forEach(input => {
                            if (input.name.includes(`[${promoType}]`)) {
                                input.value = promoCode;
                            }
                        });
                    }
                }

                this.updateTotalPayment(data.discount, data.discount_percent);
                this.showSuccess(data.message);
            } else {
                this.showError(data.message);
                // Reset discount display when promo is invalid
                this.updateTotalPayment(0, 0);
            }
        } catch (error) {
            console.error('Error applying promo:', error);
            this.showError('Lỗi khi áp dụng mã khuyến mãi');
            // Reset discount display on error
            this.updateTotalPayment(0, 0);
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

    updateTotalPayment(discount, discountPercent) {
        console.log('Updating total payment:', {
            totalAmount: this.totalAmountInput ? this.totalAmountInput.value : 0,
            shippingFee: this.shippingFeeInput ? this.shippingFeeInput.value : 0,
            discount,
            discountPercent
        });

        if (this.totalAmountInput && this.shippingFeeInput && this.totalPaymentSpan) {
            const totalAmount = parseFloat(this.totalAmountInput.value) || 0;
            const shippingFee = parseFloat(this.shippingFeeInput.value) || 0;
            const discountValue = parseFloat(discount) || 0;

            // Ẩn/hiện phần giảm giá
            const serverDiscountSection = document.getElementById('server-discount-section');
            if (serverDiscountSection) {
                serverDiscountSection.style.display = discountValue > 0 ? 'none' : '';
            }

            // Hiển thị giảm giá động
            if (this.discountRow && this.discountAmount && this.discountPercent && this.discountPercentText) {
                if (discountValue > 0) {
                    this.discountAmount.textContent = new Intl.NumberFormat('vi-VN').format(discountValue);
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
            const total = totalAmount + shippingFee - discountValue;
            const formattedTotal = new Intl.NumberFormat('vi-VN').format(total) + 'đ';
            this.totalPaymentSpan.textContent = formattedTotal;

            // Dispatch event cho ShippingCalculator
            document.dispatchEvent(new CustomEvent('promoApplied', {
                detail: {
                    discount: discountValue,
                    total: total
                }
            }));

            console.log('Updated total payment:', {
                totalAmount,
                shippingFee,
                discount: discountValue,
                total,
                formattedTotal
            });
        }
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