(function(window) {
    // Constants for pickup location
    var PICK_PROVINCE = 5; // Cần Thơ
    var PICK_DISTRICT = 82; // Quận Ninh Kiều

    class ShippingManager {
        constructor() {
            try {
                this.initializeElements();
                if (this.validateElements()) {
                    this.setupEventListeners();
                } else {
                    console.error('Missing required elements for ShippingManager');
                }
            } catch (error) {
                console.error('Error initializing ShippingManager:', error);
            }
        }

        validateElements() {
            return (
                this.form &&
                this.provinceSelect &&
                this.districtSelect &&
                this.wardSelect &&
                this.shippingFeeDisplay &&
                this.shippingFeeSummary &&
                this.totalPaymentSpan &&
                this.shippingFeeInput &&
                this.totalAmountInput &&
                this.totalWeightInput &&
                this.totalValueInput &&
                this.errorContainer &&
                this.shippingMethodInputs.length > 0
            );
        }

        initializeElements() {
            // Lấy các phần tử cần thiết
            this.form = document.getElementById('checkoutForm');
            this.provinceSelect = document.getElementById('province');
            this.districtSelect = document.getElementById('district');
            this.wardSelect = document.getElementById('ward');
            this.shippingFeeDisplay = document.getElementById('shipping-fee');
            this.shippingFeeSummary = document.getElementById('shipping-fee-summary');
            this.totalPaymentSpan = document.getElementById('total-payment');
            this.shippingFeeInput = document.getElementById('shippingFee');
            this.totalAmountInput = document.getElementById('totalAmount');
            this.totalWeightInput = document.getElementById('totalWeight');
            this.totalValueInput = document.getElementById('totalValue');
            this.errorContainer = document.getElementById('address-error');
            this.shippingMethodInputs = document.querySelectorAll('input[name="shipping_method"]');

            // Log tình trạng các phần tử
            console.log('ShippingManager elements found:', {
                form: !!this.form,
                provinceSelect: !!this.provinceSelect,
                districtSelect: !!this.districtSelect,
                wardSelect: !!this.wardSelect,
                shippingFeeDisplay: !!this.shippingFeeDisplay,
                shippingFeeSummary: !!this.shippingFeeSummary,
                totalPaymentSpan: !!this.totalPaymentSpan,
                shippingFeeInput: !!this.shippingFeeInput,
                totalAmountInput: !!this.totalAmountInput,
                totalWeightInput: !!this.totalWeightInput,
                totalValueInput: !!this.totalValueInput,
                errorContainer: !!this.errorContainer,
                shippingMethodInputs: this.shippingMethodInputs.length
            });
        }

        setupEventListeners() {
            // Thêm debounce cho tính phí ship
            this.calculateShippingDebounced = this.debounce(() => {
                this.calculateShippingFee();
            }, 500);

            // Lắng nghe sự kiện thay đổi địa chỉ
            document.addEventListener('addressChanged', () => {
                this.calculateShippingDebounced();
            });

            // Lắng nghe sự kiện thay đổi phương thức vận chuyển
            this.shippingMethodInputs.forEach(input => {
                input.addEventListener('change', () => {
                    this.calculateShippingDebounced();
                });
            });
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func.apply(this, args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        async calculateShippingFee() {
            try {
                // Kiểm tra xem có đủ thông tin để tính phí không
                if (!this.provinceSelect.value || !this.districtSelect.value || !this.wardSelect.value) {
                    this.updateShippingFee(0);
                    return;
                }

                // Lấy thông tin cần thiết
                const weight = parseFloat(this.totalWeightInput.value) || 1000; // Mặc định 1kg
                const value = parseFloat(this.totalValueInput.value) || 0;
                const isExpress = document.getElementById('express').checked;

                // Gọi API tính phí vận chuyển
                const response = await fetch('viettelpost_api.php?endpoint=order/getPriceAll', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                    },
                    body: JSON.stringify({
                        SENDER_PROVINCE: "1", // Hà Nội
                        SENDER_DISTRICT: "1", // Ba Đình
                        RECEIVER_PROVINCE: this.provinceSelect.value,
                        RECEIVER_DISTRICT: this.districtSelect.value,
                        PRODUCT_TYPE: "HH",
                        PRODUCT_WEIGHT: weight,
                        PRODUCT_PRICE: value,
                        MONEY_COLLECTION: 0,
                        PRODUCT_LENGTH: 20,
                        PRODUCT_WIDTH: 20,
                        PRODUCT_HEIGHT: 20
                    })
                });

                if (!response.ok) {
                    throw new Error('Network response was not ok');
                }

                const data = await response.json();
                if (!Array.isArray(data) || data.length === 0) {
                    throw new Error('Invalid shipping fee data');
                }

                // Lấy phí vận chuyển thấp nhất
                let shippingFee = Math.min(...data.map(service => service.GIA_CUOC));

                // Tính phụ phí cho giao hàng nhanh
                if (isExpress) {
                    shippingFee = Math.round(shippingFee * 1.5);
                }

                // Áp dụng giảm giá phí ship
                const productTotal = parseFloat(this.totalAmountInput.value) || 0;
                if (productTotal >= 300000) {
                    shippingFee = Math.round(shippingFee * 0.5);
                }
                shippingFee = Math.max(0, shippingFee - 20000);

                // Cập nhật phí vận chuyển
                this.updateShippingFee(shippingFee);

            } catch (error) {
                console.error('Error calculating shipping fee:', error);
                this.updateShippingFee(0);
                if (this.errorContainer) {
                    this.errorContainer.textContent = 'Không thể tính phí vận chuyển. Vui lòng thử lại sau.';
                    this.errorContainer.classList.remove('d-none');
                }
            }
        }

        updateShippingFee(fee) {
            // Cập nhật hiển thị phí vận chuyển
            const formattedFee = new Intl.NumberFormat('vi-VN').format(fee);

            if (this.shippingFeeDisplay) {
                this.shippingFeeDisplay.textContent = `${formattedFee}đ`;
            }

            if (this.shippingFeeSummary) {
                this.shippingFeeSummary.textContent = `${formattedFee}đ`;
            }

            if (this.shippingFeeInput) {
                this.shippingFeeInput.value = fee;
            }

            // Cập nhật tổng thanh toán
            this.updateTotalPayment();
        }

        updateTotalPayment() {
            if (!this.totalPaymentSpan) return;

            const totalAmount = parseFloat(this.totalAmountInput.value) || 0;
            const shippingFee = parseFloat(this.shippingFeeInput.value) || 0;
            const totalDiscountInput = document.querySelector('input[name="total_discount"]');
            const totalDiscount = totalDiscountInput ? parseFloat(totalDiscountInput.value) || 0 : 0;

            const totalPayment = totalAmount + shippingFee - totalDiscount;
            this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN').format(totalPayment) + 'đ';
        }
    }

    // Update total payment
    ShippingManager.prototype.updateTotalPayment = function(shippingFee) {
        // Lấy tổng tiền hàng từ input hidden
        const totalValue = parseFloat(this.totalValueInput.value) || 0;

        // Cập nhật giá trị phí vận chuyển vào input hidden
        if (this.shippingFeeInput) {
            this.shippingFeeInput.value = shippingFee;
        }

        // Tính tổng tiền bao gồm phí vận chuyển
        const totalWithShipping = totalValue + shippingFee;
        console.log('Calculating total:', {
            totalValue,
            shippingFee,
            totalWithShipping
        });

        // Cập nhật tổng tiền vào input hidden
        if (this.totalAmountInput) {
            this.totalAmountInput.value = totalWithShipping;
        }

        // Nếu có PromoManager, để nó xử lý việc cập nhật tổng tiền
        if (window.promoManager) {
            window.promoManager.updateTotalPayment(totalWithShipping, 0);
        } else {
            // Nếu không có PromoManager, tự cập nhật tổng tiền
            if (this.totalPaymentSpan) {
                this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN').format(totalWithShipping) + 'đ';
            }
        }
    }

    // Update shipping fee display and total
    ShippingManager.prototype.updateShippingFee = function(fee) {
        // Chỉ cập nhật UI, không tính toán lại phí
        const formattedFee = new Intl.NumberFormat('vi-VN').format(fee) + 'đ';

        // Update shipping fee display in shipping method section
        if (this.shippingFeeDisplay) {
            this.shippingFeeDisplay.textContent = formattedFee;
        }

        // Update hidden input
        if (this.shippingFeeInput) {
            this.shippingFeeInput.value = fee;
        }

        // Emit event for PromoManager to update total
        const discountElement = document.getElementById('discount-amount');
        var event = new CustomEvent('shippingFeeUpdated', {
            detail: {
                shippingFee: fee,
                originalAmount: parseInt(this.totalValueInput.value) || 0,
                discount: parseInt(discountElement ? discountElement.textContent.replace(/[^\d]/g, '') : '0')
            }
        });
        document.dispatchEvent(event);
    }

    // Remove updateTotalPayment as it's now handled by PromoManager
    ShippingManager.prototype.updateTotalPayment = function() {
        // This function is now empty as total payment updates are handled by PromoManager
        return;
    };

    // Show error message
    ShippingManager.prototype.showError = function(message) {
        if (this.errorContainer) {
            this.errorContainer.textContent = message;
            this.errorContainer.style.display = 'block';
        }
    };

    // Hide error message
    ShippingManager.prototype.hideError = function() {
        if (this.errorContainer) {
            this.errorContainer.style.display = 'none';
        }
    };

    // Handle form submission
    ShippingManager.prototype.handleFormSubmit = function(event) {
        var addressFields = ['province', 'district', 'ward'];
        var hasAllAddressFields = addressFields.every(function(fieldId) {
            var element = document.getElementById(fieldId);
            return element && element.value;
        });

        if (!hasAllAddressFields) {
            event.preventDefault();
            this.showError('Vui lòng chọn đầy đủ địa chỉ giao hàng');
            return;
        }

        if (!(this.shippingFeeInput && this.shippingFeeInput.value) || this.shippingFeeInput.value === '0') {
            event.preventDefault();
            this.showError('Vui lòng đợi tính phí vận chuyển');
            return;
        }

        this.hideError();
    };

    // Export to window
    window.ShippingManager = ShippingManager;

})(window);