(function(window) {
    // Constants for pickup location
    var PICK_PROVINCE = 5; // Cần Thơ
    var PICK_DISTRICT = 82; // Quận Ninh Kiều

    // ShippingManager constructor
    function ShippingManager() {
        // Initialize elements
        this.form = document.getElementById('checkoutForm');
        this.shippingFeeDisplay = document.getElementById('shipping-fee');
        this.shippingFeeSummary = document.getElementById('shipping-fee-summary');
        this.shippingFeeInput = document.getElementById('shippingFee');
        this.totalWeightInput = document.getElementById('totalWeight');
        this.totalValueInput = document.getElementById('totalValue');
        this.totalAmountInput = document.getElementById('totalAmount');
        this.totalPaymentSpan = document.getElementById('total-payment');
        this.errorContainer = document.getElementById('error-message');
        this.shippingMethodInputs = document.querySelectorAll('input[name="shipping_method"]');
        this.provinceSelect = document.getElementById('province');
        this.districtSelect = document.getElementById('district');
        this.wardSelect = document.getElementById('ward');

        // Log found elements
        console.log('ShippingManager elements found:', {
            form: !!this.form,
            shippingFeeDisplay: !!this.shippingFeeDisplay,
            shippingFeeSummary: !!this.shippingFeeSummary,
            shippingFeeInput: !!this.shippingFeeInput,
            totalWeightInput: !!this.totalWeightInput,
            totalValueInput: !!this.totalValueInput,
            totalAmountInput: !!this.totalAmountInput,
            totalPaymentSpan: !!this.totalPaymentSpan,
            errorContainer: !!this.errorContainer,
            shippingMethodInputs: this.shippingMethodInputs.length,
            provinceSelect: !!this.provinceSelect,
            districtSelect: !!this.districtSelect,
            wardSelect: !!this.wardSelect
        });

        // Setup event listeners
        if (this.form) {
            this.form.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
        document.addEventListener('addressChanged', this.calculateShippingFee.bind(this));

        // Setup shipping method change listener
        this.shippingMethodInputs.forEach(input => {
            input.addEventListener('change', this.calculateShippingFee.bind(this));
        });

        console.log('ShippingManager initialized');
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
        // Lấy trọng lượng đơn hàng (gram)
        const weight = parseInt(this.totalWeightInput && this.totalWeightInput.value || '1000');
        let discountedFee = fee;
        // Giảm phí vận chuyển theo trọng lượng
        if (weight > 20000) { // > 20kg
            discountedFee = Math.round(fee * 0.75); // giảm 25%
        } else if (weight > 10000) { // > 10kg
            discountedFee = Math.round(fee * 0.85); // giảm 15%
        } else if (weight > 5000) { // > 5kg
            discountedFee = Math.round(fee * 0.95); // giảm 5%
        }
        // Format fee for display
        var formattedFee = new Intl.NumberFormat('vi-VN').format(discountedFee) + 'đ';

        // Update shipping fee display in shipping method section
        if (this.shippingFeeDisplay) {
            this.shippingFeeDisplay.textContent = formattedFee;
        }

        // Update hidden input
        if (this.shippingFeeInput) {
            this.shippingFeeInput.value = discountedFee;
        }

        // Lấy giá trị gốc và giảm giá
        const totalValue = parseInt(this.totalValueInput.value) || 0;
        const discountElement = document.getElementById('discount-amount');
        const discount = discountElement ?
            parseInt(discountElement.textContent.replace(/[^\d]/g, '')) || 0 : 0;

        console.log('Shipping fee update values:', {
            totalValue,
            discount,
            shippingFee: discountedFee
        });

        // Emit event for PromoManager to update total
        var event = new CustomEvent('shippingFeeUpdated', {
            detail: {
                shippingFee: discountedFee,
                originalAmount: totalValue,
                discount: discount
            }
        });
        document.dispatchEvent(event);

        // Log shipping fee updates
        console.log('ShippingManager - Updated shipping fee:', {
            fee: discountedFee,
            formattedFee: formattedFee,
            displayElement: this.shippingFeeDisplay ? this.shippingFeeDisplay.textContent : null,
            summaryElement: this.shippingFeeSummary ? this.shippingFeeSummary.textContent : null,
            inputValue: this.shippingFeeInput ? this.shippingFeeInput.value : null
        });
    };

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