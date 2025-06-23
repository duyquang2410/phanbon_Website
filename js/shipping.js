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
        this.shippingFeeInput = document.querySelector('input[name="shipping_fee"]');
        this.totalWeightInput = document.querySelector('input[name="total_weight"]');
        this.totalValueInput = document.querySelector('input[name="total_value"]');
        this.totalAmountInput = document.querySelector('input[name="total_amount"]');
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

        // Initial calculation
        this.calculateShippingFee();

        console.log('ShippingManager initialized');
    }

    // Calculate shipping fee using ViettelPost API
    ShippingManager.prototype.calculateShippingFee = async function() {
        try {
            if (!this.shippingFeeDisplay) return;

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
                PRODUCT_WEIGHT: weight,
                PRODUCT_PRICE: value,
                TYPE: serviceType
            };

            // Call ViettelPost API
            const response = await fetch('viettelpost_api.php?endpoint=order/getPriceAll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                },
                body: JSON.stringify(requestData)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const data = await response.json();
            console.log('ViettelPost API response:', data);

            if (!Array.isArray(data)) {
                throw new Error('Định dạng dữ liệu phí vận chuyển không hợp lệ');
            }

            if (data.length === 0) {
                throw new Error('Không có dịch vụ vận chuyển phù hợp cho địa chỉ này');
            }

            // Get shipping fee from response based on selected method
            let shippingFee = 0;

            if (selectedMethod) {
                // Sort services by price
                const sortedServices = data.sort((a, b) => a.GIA_CUOC - b.GIA_CUOC);

                if (selectedMethod.value === 'express') {
                    // For express, find the service with TYPE = 1 (Express)
                    const expressService = data.find(service => service.MA_DV_CHINH === 'VCN' || service.MA_DV_CHINH === 'VPT');
                    if (expressService) {
                        shippingFee = expressService.GIA_CUOC;
                    } else {
                        // If no express service found, use standard service
                        shippingFee = sortedServices[0].GIA_CUOC || 0;
                    }
                } else {
                    // For standard, find the service with TYPE = 2 (Standard)
                    const standardService = data.find(service => service.MA_DV_CHINH === 'VBS' || service.MA_DV_CHINH === 'V60');
                    if (standardService) {
                        shippingFee = standardService.GIA_CUOC;
                    } else {
                        // If no standard service found, use the cheapest service
                        shippingFee = sortedServices[0].GIA_CUOC || 0;
                    }
                }
            } else {
                // If no method selected, use the cheapest service
                shippingFee = data[0].GIA_CUOC || 0;
            }

            // Ensure we have a valid shipping fee
            if (!shippingFee || isNaN(shippingFee)) {
                throw new Error('Không thể tính phí vận chuyển cho địa chỉ này');
            }

            console.log('Final shipping fee:', shippingFee);
            this.updateShippingFee(shippingFee);
            this.hideError();

        } catch (error) {
            console.error('Error calculating shipping fee:', error);
            this.showError('Lỗi tính phí vận chuyển: ' + error.message);
            if (this.shippingFeeDisplay) {
                this.shippingFeeDisplay.textContent = 'Không thể tính phí';
            }
            if (this.shippingFeeSummary) {
                this.shippingFeeSummary.textContent = 'Không thể tính phí';
            }
            if (this.shippingFeeInput) {
                this.shippingFeeInput.value = '0';
            }
            this.updateTotalPayment();
        }
    };

    // Update shipping fee display and input
    ShippingManager.prototype.updateShippingFee = function(fee) {
        // Format fee for display
        var formattedFee = fee.toLocaleString('vi-VN') + 'đ';

        // Update shipping fee display in shipping method section
        if (this.shippingFeeDisplay) {
            this.shippingFeeDisplay.textContent = formattedFee;
        }

        // Update shipping fee in order summary
        if (this.shippingFeeSummary) {
            this.shippingFeeSummary.textContent = formattedFee;
        }

        // Update hidden input
        if (this.shippingFeeInput) {
            this.shippingFeeInput.value = fee;
        }

        // Log shipping fee updates
        console.log('ShippingManager - Updating shipping fee:', {
            fee: fee,
            formattedFee: formattedFee,
            displayElement: this.shippingFeeDisplay ? this.shippingFeeDisplay.textContent : null,
            summaryElement: this.shippingFeeSummary ? this.shippingFeeSummary.textContent : null,
            inputValue: this.shippingFeeInput ? this.shippingFeeInput.value : null
        });

        // Emit event for PromoManager to update total
        var event = new CustomEvent('shippingFeeChanged', {
            detail: {
                shippingFee: fee
            }
        });
        document.dispatchEvent(event);
    };

    // Remove updateTotalPayment from ShippingManager as it's now handled by PromoManager
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