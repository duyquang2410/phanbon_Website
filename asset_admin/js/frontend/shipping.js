(function(window) {
    // Constants for pickup location
    const PICK_PROVINCE = "92"; // Cần Thơ
    const PICK_DISTRICT = "919"; // Quận Ninh Kiều
    const PICK_WARD = "31153"; // Phường Cái Khế

    // ShippingManager constructor
    function ShippingManager() {
        // Initialize elements
        this.form = document.getElementById('checkoutForm');
        this.shippingFeeDisplay = document.getElementById('standard-fee');
        this.shippingFeeSummary = document.getElementById('shipping-fee-summary');
        this.shippingFeeInput = document.querySelector('input[name="shipping_fee"]');
        this.totalWeightInput = document.querySelector('input[name="total_weight"]');
        this.totalValueInput = document.querySelector('input[name="total_value"]');
        this.totalAmountInput = document.querySelector('input[name="total_amount"]');
        this.totalPaymentSpan = document.getElementById('total-payment');
        this.errorContainer = document.querySelector('.shipping-error');
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

        // Listen for address changes
        if (this.provinceSelect) {
            this.provinceSelect.addEventListener('change', this.calculateShippingFee.bind(this));
        }
        if (this.districtSelect) {
            this.districtSelect.addEventListener('change', this.calculateShippingFee.bind(this));
        }

        // Setup shipping method change listener
        this.shippingMethodInputs.forEach(input => {
            input.addEventListener('change', () => {
                this.handleShippingMethodChange();
                this.calculateShippingFee();
            });
        });

        // Initial calculation
        this.calculateShippingFee();
    }

    // Calculate shipping fee using ViettelPost API
    ShippingManager.prototype.calculateShippingFee = async function() {
        try {
            if (!this.validateAddressSelection()) {
                console.log('Invalid address selection');
                return;
            }

            // Show loading state
            this.shippingFeeDisplay.textContent = 'Đang tính...';
            if (this.shippingFeeSummary) {
                this.shippingFeeSummary.textContent = 'Đang tính...';
            }

            // Get selected province and district
            const provinceId = this.provinceSelect ? this.provinceSelect.value : '';
            const districtId = this.districtSelect ? this.districtSelect.value : '';

            if (!provinceId || !districtId) {
                console.log('Missing province or district:', { provinceId, districtId });
                this.updateShippingFee(0);
                return;
            }

            // Get weight and value
            const weight = parseInt(this.totalWeightInput && this.totalWeightInput.value || '1000');
            const value = parseInt(this.totalValueInput && this.totalValueInput.value || '0');

            // Get selected shipping method
            const selectedMethod = Array.from(this.shippingMethodInputs).find(input => input.checked);
            const serviceType = selectedMethod && selectedMethod.value === 'express' ? 1 : 2; // 1: Express, 2: Standard

            console.log('Calculating shipping fee with params:', {
                weight,
                value,
                provinceId,
                districtId,
                serviceType
            });

            // Prepare API request
            const requestData = {
                SENDER_PROVINCE: PICK_PROVINCE,
                SENDER_DISTRICT: PICK_DISTRICT,
                RECEIVER_PROVINCE: parseInt(provinceId),
                RECEIVER_DISTRICT: parseInt(districtId),
                PRODUCT_TYPE: "HH",
                PRODUCT_WEIGHT: Math.max(0.1, Math.min(weight / 1000, 20)), // Convert grams to kg and limit between 0.1kg and 20kg
                PRODUCT_PRICE: value,
                MONEY_COLLECTION: 0,
                PRODUCT_LENGTH: 10,
                PRODUCT_WIDTH: 10,
                PRODUCT_HEIGHT: 10
            };

            // Call ViettelPost API
            const response = await fetch('viettelpost_api.php?endpoint=order/getPriceAll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(requestData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            console.log('API Response:', result);

            if (!result.success || !result.data || !Array.isArray(result.data)) {
                throw new Error('Invalid response format from ViettelPost API');
            }

            const data = result.data;
            if (data.length === 0) {
                throw new Error('No shipping services available');
            }

            // Calculate shipping fee
            let shippingFee = 0;
            if (serviceType === 1) { // Express
                // Find express services (VCN, SCN)
                const expressServices = data.filter(service => ['VCN', 'SCN'].includes(service.MA_DV_CHINH));
                if (expressServices.length > 0) {
                    // Use the cheapest express service
                    const cheapestExpress = expressServices.reduce((min, service) =>
                        service.GIA_CUOC < min.GIA_CUOC ? service : min, expressServices[0]);
                    shippingFee = cheapestExpress.GIA_CUOC;
                } else {
                    // If no express service, use the fastest available
                    shippingFee = data[0].GIA_CUOC;
                }
            } else { // Standard
                // Find standard services (VTK)
                const standardServices = data.filter(service => ['VTK'].includes(service.MA_DV_CHINH));
                if (standardServices.length > 0) {
                    // Use the cheapest standard service
                    const cheapestStandard = standardServices.reduce((min, service) =>
                        service.GIA_CUOC < min.GIA_CUOC ? service : min, standardServices[0]);
                    shippingFee = cheapestStandard.GIA_CUOC;
                } else {
                    // If no standard service, use the cheapest available
                    const sortedServices = [...data].sort((a, b) => a.GIA_CUOC - b.GIA_CUOC);
                    shippingFee = sortedServices[0].GIA_CUOC;
                }
            }

            // Apply discounts based on order value
            if (value >= 2000000) { // > 2M VND
                shippingFee *= 0.5; // 50% discount
            } else if (value >= 1000000) { // > 1M VND
                shippingFee *= 0.7; // 30% discount
            }

            console.log('Final shipping fee:', shippingFee);
            this.updateShippingFee(shippingFee);
            this.hideError();

        } catch (error) {
            console.error('Error calculating shipping fee:', error);
            this.showError('Lỗi tính phí vận chuyển: ' + error.message);
            this.updateShippingFee(0);
        }
    };

    // Validate address selection
    ShippingManager.prototype.validateAddressSelection = function() {
        return this.provinceSelect &&
            this.districtSelect &&
            this.provinceSelect.value &&
            this.districtSelect.value;
    };

    // Update shipping fee display and input
    ShippingManager.prototype.updateShippingFee = function(fee) {
        const formattedFee = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND',
            minimumFractionDigits: 0,
            maximumFractionDigits: 0
        }).format(fee).replace('₫', 'đ');

        // Update shipping fee display
        if (this.shippingFeeDisplay) {
            this.shippingFeeDisplay.textContent = formattedFee;
        }

        // Update shipping fee summary
        if (this.shippingFeeSummary) {
            this.shippingFeeSummary.textContent = formattedFee;
        }

        // Update hidden input
        if (this.shippingFeeInput) {
            this.shippingFeeInput.value = fee;
        }

        // Update total payment
        this.updateTotalPayment();

        // Log updates
        console.log('Updated shipping fee:', {
            fee: fee,
            formatted: formattedFee,
            display: this.shippingFeeDisplay ? this.shippingFeeDisplay.textContent : null,
            summary: this.shippingFeeSummary ? this.shippingFeeSummary.textContent : null,
            input: this.shippingFeeInput ? this.shippingFeeInput.value : null
        });
    };

    // Handle shipping method change
    ShippingManager.prototype.handleShippingMethodChange = function() {
        this.calculateShippingFee();
    };

    // Update total payment
    ShippingManager.prototype.updateTotalPayment = function() {
        const subtotal = parseFloat(this.totalValueInput ? this.totalValueInput.value : 0) || 0;
        const shippingFee = parseFloat(this.shippingFeeInput ? this.shippingFeeInput.value : 0) || 0;
        const total = subtotal + shippingFee;

        if (this.totalPaymentSpan) {
            this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN', {
                style: 'currency',
                currency: 'VND',
                minimumFractionDigits: 0,
                maximumFractionDigits: 0
            }).format(total).replace('₫', 'đ');
        }

        console.log('Updated total payment:', {
            subtotal: subtotal,
            shippingFee: shippingFee,
            total: total
        });
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
        if (!this.validateAddressSelection()) {
            event.preventDefault();
            this.showError('Vui lòng chọn đầy đủ địa chỉ giao hàng');
            return;
        }

        if (!this.shippingFeeInput || !this.shippingFeeInput.value || this.shippingFeeInput.value === '0') {
            event.preventDefault();
            this.showError('Vui lòng đợi tính phí vận chuyển');
            return;
        }

        this.hideError();
    };

    // Initialize after DOM is loaded
    document.addEventListener('DOMContentLoaded', function() {
        try {
            window.shippingManager = new ShippingManager();
            console.log('ShippingManager initialized successfully');
        } catch (error) {
            console.error('Failed to initialize ShippingManager:', error);
        }
    });

})(window);