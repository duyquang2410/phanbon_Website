// Quản lý địa chỉ và tính phí vận chuyển ViettelPost
(function(window) {
    console.log('Address.js loaded');

    class AddressManager {
        constructor(config = {}) {
            console.log('AddressManager constructor called with config:', config);

            // Validate config
            if (!config) {
                console.error('Config is required');
                throw new Error('Config is required');
            }

            if (!config.pickProvince || !config.pickDistrict) {
                console.error('Missing required config:', config);
                throw new Error('Missing required config values');
            }

            this.config = config;
            this.shippingMethod = 'standard'; // Mặc định là giao hàng tiêu chuẩn
            console.log('Config validated successfully');

            this.initializeElements();
            this.setupConfig();
            this.isIntraProvince = false;

            if (this.validateElements()) {
                this.setupEventListeners();
                this.loadProvinces().then(() => {
                    // Khôi phục địa chỉ đã lưu nếu có
                    if (this.config.savedAddress) {
                        this.restoreSavedAddress();
                    }
                });
            } else {
                console.error('Required elements are missing');
                this.showError('Không thể khởi tạo form địa chỉ. Vui lòng tải lại trang.');
            }
        }

        initializeElements() {
            console.log('Initializing elements...');

            try {
                // Lấy các phần tử
                this.provinceSelect = document.getElementById('province');
                this.districtSelect = document.getElementById('district');
                this.wardSelect = document.getElementById('ward');
                this.shippingFeeDisplay = document.getElementById('shipping-fee');
                this.shippingFeeInput = document.querySelector('input[name="shipping_fee"]');
                this.errorMessageContainer = document.getElementById('address-error');
                this.totalPriceInput = document.querySelector('input[name="total_amount"]');
                this.totalWeightInput = document.querySelector('input[name="total_weight"]');
                this.totalValueInput = document.querySelector('input[name="total_value"]');
                this.totalPaymentSpan = document.querySelector('.total-row .text-danger');
                this.streetAddressInput = document.getElementById('street_address');
                this.fullAddressInput = document.getElementById('full_address');
                this.fullAddressDisplay = document.getElementById('full-address-display');

                // Log element status
                console.log('Elements found:', {
                    provinceSelect: !!this.provinceSelect,
                    districtSelect: !!this.districtSelect,
                    wardSelect: !!this.wardSelect,
                    shippingFeeDisplay: !!this.shippingFeeDisplay,
                    shippingFeeInput: !!this.shippingFeeInput,
                    errorMessageContainer: !!this.errorMessageContainer,
                    totalPriceInput: !!this.totalPriceInput,
                    totalWeightInput: !!this.totalWeightInput,
                    totalValueInput: !!this.totalValueInput,
                    totalPaymentSpan: !!this.totalPaymentSpan,
                    streetAddressInput: !!this.streetAddressInput,
                    fullAddressInput: !!this.fullAddressInput,
                    fullAddressDisplay: !!this.fullAddressDisplay
                });
            } catch (error) {
                console.error('Error initializing elements:', error);
                throw error;
            }
        }

        setupConfig() {
            // Thông tin điểm lấy hàng từ cấu hình
            this.pickProvince = this.config.pickProvince || "1"; // Hà Nội
            this.pickDistrict = this.config.pickDistrict || "1"; // Ba Đình
            this.pickWard = this.config.pickWard || "1"; // Phúc Xá
            this.pickAddress = this.config.pickAddress || "123 Đường Láng";

            this.shippingServices = [
                { id: 'VCN', name: 'Chuyển phát tiêu chuẩn', priority: 1 },
                { id: 'VPT', name: 'Chuyển phát nhanh', priority: 2 }
            ].sort((a, b) => a.priority - b.priority);

            this.defaultProductConfig = {
                weight: this.config.defaultWeight || 1000, // 1kg
                length: (this.config.defaultDimensions && this.config.defaultDimensions.length) || 20,
                width: (this.config.defaultDimensions && this.config.defaultDimensions.width) || 20,
                height: (this.config.defaultDimensions && this.config.defaultDimensions.height) || 20,
                quantity: 1
            };

            const discountInput = document.querySelector('input[name="total_discount"]');
            this.totalDiscount = parseFloat(discountInput ? discountInput.value : '0');
        }

        validateElements() {
            const requiredElements = [
                this.provinceSelect,
                this.districtSelect,
                this.wardSelect,
                this.shippingFeeInput,
                this.totalPriceInput,
                this.totalWeightInput,
                this.totalValueInput,
                this.totalPaymentSpan,
                this.streetAddressInput,
                this.fullAddressInput,
                this.fullAddressDisplay
            ];

            const hasAllRequired = requiredElements.every(element => !!element);

            if (!hasAllRequired) {
                console.warn('Missing elements:', {
                    provinceSelect: !!this.provinceSelect,
                    districtSelect: !!this.districtSelect,
                    wardSelect: !!this.wardSelect,
                    shippingFeeInput: !!this.shippingFeeInput,
                    totalPriceInput: !!this.totalPriceInput,
                    totalWeightInput: !!this.totalWeightInput,
                    totalValueInput: !!this.totalValueInput,
                    totalPaymentSpan: !!this.totalPaymentSpan,
                    streetAddressInput: !!this.streetAddressInput,
                    fullAddressInput: !!this.fullAddressInput,
                    fullAddressDisplay: !!this.fullAddressDisplay
                });
            }

            return hasAllRequired;
        }

        setupEventListeners() {
            console.log('Cài đặt sự kiện...');

            this.provinceSelect.addEventListener('change', () => {
                const selectedProvinceId = this.provinceSelect.value;
                console.log('Province changed:', selectedProvinceId);

                // Disable district select while loading
                this.districtSelect.disabled = true;
                this.wardSelect.disabled = true;

                this.loadDistricts(selectedProvinceId);
                this.clearWards();
                this.calculateShippingDebounced();
                this.updateFullAddress();
            });

            this.districtSelect.addEventListener('change', () => {
                const selectedDistrictId = this.districtSelect.value;
                console.log('District changed:', selectedDistrictId);

                // Disable ward select while loading
                this.wardSelect.disabled = true;

                this.loadWards(selectedDistrictId);
                this.calculateShippingDebounced();
                this.updateFullAddress();
            });

            this.wardSelect.addEventListener('change', () => {
                console.log('Ward changed:', this.wardSelect.value);
                this.calculateShippingDebounced();
                this.updateFullAddress();
            });

            if (this.streetAddressInput) {
                this.streetAddressInput.addEventListener('input', () => {
                    this.updateFullAddress();
                });
            }

            // Set up debounced shipping calculation
            this.calculateShippingDebounced = this.debounce(this.calculateShipping.bind(this), 500);

            // Thêm listener cho thay đổi phương thức vận chuyển
            const standardRadio = document.getElementById('standard');
            const expressRadio = document.getElementById('express');
            if (standardRadio) {
                standardRadio.addEventListener('change', () => {
                    if (standardRadio.checked) {
                        this.shippingMethod = 'standard';
                        this.calculateShippingDebounced();
                    }
                });
            }
            if (expressRadio) {
                expressRadio.addEventListener('change', () => {
                    if (expressRadio.checked) {
                        this.shippingMethod = 'express';
                        this.calculateShippingDebounced();
                    }
                });
            }
        }

        debounce(func, wait) {
            let timeout;
            return function executedFunction(...args) {
                const later = () => {
                    clearTimeout(timeout);
                    func(...args);
                };
                clearTimeout(timeout);
                timeout = setTimeout(later, wait);
            };
        }

        async fetchViettelPost(endpoint, params = {}, method = 'GET') {
            try {
                console.log('Calling ViettelPost API:', {
                    endpoint,
                    params,
                    method
                });

                let url = `viettelpost_api.php?endpoint=${encodeURIComponent(endpoint)}`;

                // Add query parameters for GET requests
                if (method === 'GET' && Object.keys(params).length > 0) {
                    Object.entries(params).forEach(([key, value]) => {
                        url += `&${encodeURIComponent(key)}=${encodeURIComponent(value)}`;
                    });
                }

                console.log('Fetch request:', {
                    url,
                    options: {
                        method,
                        headers: {
                            'Content-Type': 'application/json',
                            'Accept': 'application/json'
                        },
                        body: method === 'POST' ? JSON.stringify(params) : undefined
                    }
                });

                const response = await fetch(url, {
                    method,
                    headers: {
                        'Content-Type': 'application/json',
                        'Accept': 'application/json'
                    },
                    body: method === 'POST' ? JSON.stringify(params) : undefined
                });

                console.log('Response status:', response.status);

                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }

                const data = await response.json();
                console.log('Response data:', data);

                if (data.error) {
                    throw new Error(data.message || 'Unknown API error');
                }

                return data;
            } catch (error) {
                console.error('Error in fetchViettelPost:', error);
                throw error;
            }
        }

        async loadProvinces() {
            try {
                console.log('Đang tải danh sách tỉnh/thành phố');
                const responseData = await this.fetchViettelPost('categories/listProvince');
                console.log('Dữ liệu tỉnh/thành phố:', responseData);

                // Clear existing options
                this.provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành phố</option>';

                // Check if data exists and is an array
                if (responseData.data && Array.isArray(responseData.data)) {
                    // Add new options
                    responseData.data.forEach(province => {
                        const option = document.createElement('option');
                        option.value = province.PROVINCE_ID.toString(); // Convert to string
                        option.textContent = province.PROVINCE_NAME;
                        this.provinceSelect.appendChild(option);
                    });

                    // Nếu có địa chỉ đã lưu, chọn tỉnh/thành phố tương ứng
                    if (this.config.savedAddress && this.config.savedAddress.province_id) {
                        const savedProvinceId = this.config.savedAddress.province_id.toString();
                        console.log('Trying to select province:', savedProvinceId);
                        this.provinceSelect.value = savedProvinceId;

                        if (this.provinceSelect.value === savedProvinceId) {
                            console.log('Successfully selected province:', savedProvinceId);
                            // Trigger change event để load districts
                            const event = new Event('change');
                            this.provinceSelect.dispatchEvent(event);
                        } else {
                            console.error('Failed to select province:', savedProvinceId, 'Available options:', Array.from(this.provinceSelect.options).map(opt => opt.value));
                        }
                    }
                } else {
                    throw new Error('Dữ liệu tỉnh/thành phố không hợp lệ');
                }

                console.log('Đã tải xong danh sách tỉnh/thành phố');
            } catch (error) {
                console.error('Lỗi khi tải danh sách tỉnh/thành phố:', error);
                this.showError('Không thể tải danh sách tỉnh/thành phố. ' + error.message);
            }
        }

        async loadDistricts(provinceId) {
            try {
                // Disable district select while loading
                this.districtSelect.disabled = true;

                if (!provinceId) {
                    this.districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                    this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    return;
                }

                console.log('Đang tải danh sách quận/huyện cho tỉnh/thành phố:', provinceId);
                const responseData = await this.fetchViettelPost(`categories/listDistrict`, { provinceId });
                console.log('Dữ liệu quận/huyện:', responseData);

                // Clear existing options
                this.districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                // Check if data exists and is an array
                if (responseData.data && Array.isArray(responseData.data)) {
                    // Add new options
                    responseData.data.forEach(district => {
                        const option = document.createElement('option');
                        option.value = district.DISTRICT_ID.toString(); // Convert to string
                        option.textContent = district.DISTRICT_NAME;
                        this.districtSelect.appendChild(option);
                    });

                    // Enable district select after loading data
                    this.districtSelect.disabled = false;

                    // Nếu có địa chỉ đã lưu và đang load districts cho tỉnh/thành phố đã lưu
                    if (this.config.savedAddress &&
                        this.config.savedAddress.province_id.toString() === provinceId.toString() &&
                        this.config.savedAddress.district_id) {
                        const savedDistrictId = this.config.savedAddress.district_id.toString();
                        console.log('Trying to select district:', savedDistrictId);
                        this.districtSelect.value = savedDistrictId;

                        if (this.districtSelect.value === savedDistrictId) {
                            console.log('Successfully selected district:', savedDistrictId);
                            // Trigger change event để load wards
                            const event = new Event('change');
                            this.districtSelect.dispatchEvent(event);
                        } else {
                            console.error('Failed to select district:', savedDistrictId, 'Available options:', Array.from(this.districtSelect.options).map(opt => opt.value));
                        }
                    }

                    this.hideError();
                } else {
                    throw new Error('Dữ liệu quận/huyện không hợp lệ');
                }

                console.log('Đã tải xong danh sách quận/huyện');
            } catch (error) {
                console.error('Lỗi khi tải danh sách quận/huyện:', error);
                this.showError('Không thể tải danh sách quận/huyện. ' + error.message);
                this.districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                this.districtSelect.disabled = true;
            }
        }

        async loadWards(districtId) {
            try {
                // Disable ward select while loading
                this.wardSelect.disabled = true;

                if (!districtId) {
                    this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                    return;
                }

                console.log('Đang tải danh sách phường/xã cho quận/huyện:', districtId);
                const responseData = await this.fetchViettelPost(`categories/listWards`, { districtId });
                console.log('Dữ liệu phường/xã:', responseData);

                // Clear existing options
                this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';

                // Check if data exists and is an array
                if (responseData.data && Array.isArray(responseData.data)) {
                    // Add new options
                    responseData.data.forEach(ward => {
                        const option = document.createElement('option');
                        option.value = ward.WARDS_ID.toString(); // Convert to string
                        option.textContent = ward.WARDS_NAME;
                        this.wardSelect.appendChild(option);
                    });

                    // Enable ward select after loading data
                    this.wardSelect.disabled = false;

                    // Nếu có địa chỉ đã lưu và đang load wards cho quận/huyện đã lưu
                    if (this.config.savedAddress &&
                        this.config.savedAddress.district_id.toString() === districtId.toString() &&
                        this.config.savedAddress.ward_id) {
                        const savedWardId = this.config.savedAddress.ward_id.toString();
                        console.log('Trying to select ward:', savedWardId);
                        this.wardSelect.value = savedWardId;

                        if (this.wardSelect.value === savedWardId) {
                            console.log('Successfully selected ward:', savedWardId);
                            // Trigger change event
                            const event = new Event('change');
                            this.wardSelect.dispatchEvent(event);
                        } else {
                            console.error('Failed to select ward:', savedWardId, 'Available options:', Array.from(this.wardSelect.options).map(opt => opt.value));
                        }
                    }

                    this.hideError();
                } else {
                    throw new Error('Dữ liệu phường/xã không hợp lệ');
                }

                console.log('Đã tải xong danh sách phường/xã');
            } catch (error) {
                console.error('Lỗi khi tải danh sách phường/xã:', error);
                this.showError('Không thể tải danh sách phường/xã. ' + error.message);
                this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                this.wardSelect.disabled = true;
            }
        }

        clearWards() {
            this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            this.wardSelect.disabled = true;
        }

        showError(message) {
            if (this.errorMessageContainer) {
                this.errorMessageContainer.textContent = message;
                this.errorMessageContainer.classList.remove('d-none');
            } else {
                console.error('Không tìm thấy phần tử hiển thị lỗi địa chỉ');
            }
        }

        hideError() {
            if (this.errorMessageContainer) {
                this.errorMessageContainer.classList.add('d-none');
            }
        }

        async calculateShipping() {
            if (this.isCalculating) return;

            try {
                this.isCalculating = true;
                this.shippingFeeDisplay.textContent = 'Đang tính...';

                if (!this.validateAddressSelection()) {
                    this.shippingFeeDisplay.textContent = 'Chọn địa chỉ giao hàng';
                    return;
                }

                const fee = await this.calculateShippingFee();
                this.updateShippingFee(fee);
                this.updateTotalPayment();

            } catch (error) {
                console.error('Lỗi tính phí vận chuyển:', error);
                this.showError('Không thể tính phí vận chuyển. Vui lòng thử lại sau.');
                this.shippingFeeDisplay.textContent = 'Lỗi tính phí';
            } finally {
                this.isCalculating = false;
            }
        }

        validateAddressSelection() {
            return this.provinceSelect.value && this.districtSelect.value && this.wardSelect.value;
        }

        // Kiểm tra địa chỉ đã đủ thông tin chưa
        isAddressComplete() {
            return (
                this.provinceSelect && this.provinceSelect.value &&
                this.districtSelect && this.districtSelect.value &&
                this.wardSelect && this.wardSelect.value &&
                this.streetAddressInput && this.streetAddressInput.value.trim() !== ''
            );
        }

        formatCurrency(amount) {
            // Làm tròn số
            amount = Math.round(amount);
            return new Intl.NumberFormat('vi-VN').format(amount);
        }

        async calculateShippingFee() {
            try {
                const selectedProvince = this.provinceSelect.value;
                const selectedDistrict = this.districtSelect.value;

                if (!selectedProvince || !selectedDistrict) {
                    console.log('Chưa chọn đủ thông tin địa chỉ');
                    return 0;
                }

                // Cập nhật trạng thái giao hàng nội tỉnh
                this.isIntraProvince = selectedProvince === this.config.pickProvince;

                const weight = this.totalWeightInput && parseFloat(this.totalWeightInput.value) || this.config.defaultWeight;
                const productValue = this.totalValueInput && parseFloat(this.totalValueInput.value) || 0;

                const defaultDimensions = this.config.defaultDimensions || {};
                const requestData = {
                    SENDER_PROVINCE: this.config.pickProvince,
                    SENDER_DISTRICT: this.config.pickDistrict,
                    RECEIVER_PROVINCE: selectedProvince,
                    RECEIVER_DISTRICT: selectedDistrict,
                    PRODUCT_TYPE: "HH",
                    PRODUCT_WEIGHT: weight,
                    PRODUCT_PRICE: productValue,
                    MONEY_COLLECTION: 0,
                    PRODUCT_LENGTH: defaultDimensions.length || 10,
                    PRODUCT_WIDTH: defaultDimensions.width || 10,
                    PRODUCT_HEIGHT: defaultDimensions.height || 10
                };

                const response = await this.fetchViettelPost('order/getPriceAll', requestData, 'POST');
                if (!response) {
                    throw new Error('Không nhận được phản hồi từ API vận chuyển');
                }
                if (!Array.isArray(response)) {
                    throw new Error('Định dạng dữ liệu phí vận chuyển không hợp lệ');
                }
                if (response.length === 0) {
                    throw new Error('Không có dịch vụ vận chuyển phù hợp cho địa chỉ này');
                }
                // Sắp xếp theo giá từ thấp đến cao
                const sortedServices = response.sort((a, b) => a.GIA_CUOC - b.GIA_CUOC);
                const cheapestService = sortedServices[0];
                if (!cheapestService.GIA_CUOC || isNaN(cheapestService.GIA_CUOC)) {
                    throw new Error('Không thể tính phí vận chuyển cho địa chỉ này');
                }

                // Bắt đầu tính phí vận chuyển
                let shippingFee = Math.round(cheapestService.GIA_CUOC);
                const productTotal = parseFloat(this.totalPriceInput.value) || 0;
                let finalFee = shippingFee;

                // 1. Áp dụng giảm giá theo trọng lượng
                if (weight > 20000) { // > 20kg
                    finalFee = Math.round(finalFee * 0.75); // giảm 25%
                } else if (weight > 10000) { // > 10kg
                    finalFee = Math.round(finalFee * 0.85); // giảm 15%
                } else if (weight > 5000) { // > 5kg
                    finalFee = Math.round(finalFee * 0.95); // giảm 5%
                }

                // Hiển thị giá gốc cho phương thức vận chuyển
                const baseFormattedFee = new Intl.NumberFormat('vi-VN').format(finalFee) + 'đ';
                const shippingMethodDisplay = document.querySelector('.shipping-method-fee');
                if (shippingMethodDisplay) {
                    shippingMethodDisplay.textContent = baseFormattedFee;
                }

                // 2. Áp dụng phụ phí giao hàng nhanh nếu có
                if (this.shippingMethod === 'express') {
                    finalFee = Math.round(finalFee * 1.5);
                }

                // 3. Giảm 50% cho đơn từ 300,000đ
                let discount300k = 0;
                if (productTotal >= 300000) {
                    discount300k = Math.round(finalFee * 0.5);
                    finalFee = finalFee - discount300k;
                }

                // 4. Giảm thêm 20,000đ cho đơn từ 100,000đ
                let discount20k = 0;
                if (productTotal >= 100000) {
                    discount20k = Math.min(20000, finalFee); // Không để phí ship âm
                    finalFee = Math.max(0, finalFee - discount20k);
                }

                // Cập nhật hiển thị tổng phí
                const formattedFee = new Intl.NumberFormat('vi-VN').format(finalFee) + 'đ';
                if (this.shippingFeeDisplay) {
                    this.shippingFeeDisplay.textContent = formattedFee;
                    if (this.shippingMethod === 'express') {
                        this.shippingFeeDisplay.textContent += ' (Đã bao gồm phụ phí giao nhanh +50%)';
                    }
                }
                if (this.shippingFeeInput) {
                    this.shippingFeeInput.value = finalFee;
                }
                const shippingFeeSummary = document.getElementById('shipping-fee-summary');
                if (shippingFeeSummary) {
                    shippingFeeSummary.textContent = formattedFee;
                }

                // Emit event for other components
                const event = new CustomEvent('shipping-fee-updated', {
                    detail: finalFee
                });
                document.dispatchEvent(event);

                return finalFee;
            } catch (error) {
                console.error('Lỗi khi tính phí vận chuyển:', error);
                this.showError('Không thể tính phí vận chuyển: ' + error.message);
                return 0;
            }
        }

        updateShippingFee(fee) {
            const formattedFee = this.formatCurrency(fee);
            this.shippingFeeDisplay.textContent = `${formattedFee}đ`;
            this.shippingFeeInput.value = fee;
            // Cập nhật phần tóm tắt đơn hàng nếu có
            const shippingFeeSummary = document.getElementById('shipping-fee-summary');
            if (shippingFeeSummary) {
                shippingFeeSummary.textContent = `${formattedFee}đ`;
            }
            this.updateTotalPayment();
        }

        updateTotalPayment() {
            const totalAmount = parseFloat(this.totalPriceInput.value) || 0;
            const shippingFee = parseFloat(this.shippingFeeInput.value) || 0;
            const totalDiscount = this.totalDiscount || 0;

            const totalPayment = totalAmount + shippingFee - totalDiscount;
            this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN').format(totalPayment) + 'đ';
        }

        updateFullAddress() {
            const streetAddress = this.streetAddressInput ? this.streetAddressInput.value.trim() : '';
            const wardOption = this.wardSelect.options[this.wardSelect.selectedIndex];
            const districtOption = this.districtSelect.options[this.districtSelect.selectedIndex];
            const provinceOption = this.provinceSelect.options[this.provinceSelect.selectedIndex];

            // Chỉ lấy số nhà và tên đường từ input, không lấy phần xã/huyện/tỉnh nếu người dùng có nhập
            let cleanStreetAddress = streetAddress;
            const wardText = wardOption ? wardOption.text : '';
            const districtText = districtOption ? districtOption.text : '';
            const provinceText = provinceOption ? provinceOption.text : '';

            // Loại bỏ các phần trùng lặp với xã/huyện/tỉnh từ địa chỉ đường
            if (wardText && cleanStreetAddress.toLowerCase().includes(wardText.toLowerCase())) {
                cleanStreetAddress = cleanStreetAddress.replace(new RegExp(wardText, 'i'), '').trim();
            }
            if (districtText && cleanStreetAddress.toLowerCase().includes(districtText.toLowerCase())) {
                cleanStreetAddress = cleanStreetAddress.replace(new RegExp(districtText, 'i'), '').trim();
            }
            if (provinceText && cleanStreetAddress.toLowerCase().includes(provinceText.toLowerCase())) {
                cleanStreetAddress = cleanStreetAddress.replace(new RegExp(provinceText, 'i'), '').trim();
            }

            // Loại bỏ dấu phẩy thừa ở cuối địa chỉ đường
            cleanStreetAddress = cleanStreetAddress.replace(/,\s*$/, '').trim();

            const addressParts = [
                cleanStreetAddress,
                wardText,
                districtText,
                provinceText
            ].filter(part => part !== '');

            const fullAddress = addressParts.join(', ');

            // Update hidden input for full address
            if (this.fullAddressInput) {
                this.fullAddressInput.value = fullAddress;
            }

            // Update display element
            if (this.fullAddressDisplay) {
                this.fullAddressDisplay.textContent = fullAddress || 'Chưa có địa chỉ';
            }

            console.log('Địa chỉ đầy đủ đã được cập nhật:', fullAddress);
        }

        async restoreSavedAddress() {
            try {
                const saved = this.config.savedAddress;
                if (!saved) {
                    console.log('No saved address to restore');
                    return;
                }

                console.log('Starting address restoration with data:', saved);

                // Validate saved data
                if (!saved.province_id || !saved.district_id || !saved.ward_id) {
                    console.error('Invalid saved address data:', saved);
                    throw new Error('Dữ liệu địa chỉ không hợp lệ hoặc thiếu thông tin');
                }

                // Set street address first
                if (saved.street) {
                    console.log('Setting street address:', saved.street);
                    this.streetAddressInput.value = saved.street;
                }

                // Load provinces and wait for it to complete
                console.log('Loading provinces...');
                await this.loadProvinces();

                // Wait a bit for the province select to be populated
                await new Promise(resolve => setTimeout(resolve, 500));

                // Set province
                console.log('Setting province:', saved.province_id);
                this.provinceSelect.value = saved.province_id.toString();
                if (this.provinceSelect.value !== saved.province_id.toString()) {
                    console.error('Failed to set province. Available options:',
                        Array.from(this.provinceSelect.options).map(opt => ({ value: opt.value, text: opt.text })));
                    throw new Error('Không thể chọn Tỉnh/Thành phố');
                }

                // Trigger province change event
                const provinceEvent = new Event('change');
                this.provinceSelect.dispatchEvent(provinceEvent);

                // Wait for districts to load
                await new Promise(resolve => setTimeout(resolve, 500));

                // Set district
                console.log('Setting district:', saved.district_id);
                this.districtSelect.value = saved.district_id.toString();
                if (this.districtSelect.value !== saved.district_id.toString()) {
                    console.error('Failed to set district. Available options:',
                        Array.from(this.districtSelect.options).map(opt => ({ value: opt.value, text: opt.text })));
                    throw new Error('Không thể chọn Quận/Huyện');
                }

                // Trigger district change event
                const districtEvent = new Event('change');
                this.districtSelect.dispatchEvent(districtEvent);

                // Wait for wards to load
                await new Promise(resolve => setTimeout(resolve, 500));

                // Set ward
                console.log('Setting ward:', saved.ward_id);
                this.wardSelect.value = saved.ward_id.toString();
                if (this.wardSelect.value !== saved.ward_id.toString()) {
                    console.error('Failed to set ward. Available options:',
                        Array.from(this.wardSelect.options).map(opt => ({ value: opt.value, text: opt.text })));
                    throw new Error('Không thể chọn Phường/Xã');
                }

                // Trigger ward change event
                const wardEvent = new Event('change');
                this.wardSelect.dispatchEvent(wardEvent);

                // Update full address display
                this.updateFullAddress();

                // Trigger shipping calculation
                this.calculateShippingDebounced();

                console.log('Address restoration completed successfully');
            } catch (error) {
                console.error('Error restoring saved address:', error);
                this.showError('Không thể khôi phục địa chỉ đã lưu: ' + error.message);
            }
        }

        triggerShippingCalculation() {
            const event = new Event('addressChanged');
            document.dispatchEvent(event);
        }
    }

    // Xuất ra window
    window.AddressManager = AddressManager;

    // Lắng nghe sự kiện shipping-fee-updated để cập nhật UI đồng bộ
    document.addEventListener('shipping-fee-updated', function(e) {
        if (window.addressManager) {
            window.addressManager.updateShippingFee(parseInt(e.detail, 10) || 0);
        }
    });

    // === BỔ SUNG TÍNH NĂNG CHỌN PHƯƠNG THỨC VẬN CHUYỂN ===
    document.addEventListener('DOMContentLoaded', function() {
        const standardRadio = document.getElementById('standard');
        const expressRadio = document.getElementById('express');
        const shippingFeeDisplay = document.getElementById('shipping-fee');
        const shippingFeeInput = document.querySelector('input[name="shipping_fee"]');
        let baseShippingFee = 0; // Chỉ cập nhật khi tính phí xong

        // Hàm cập nhật phí hiển thị theo radio đang chọn
        function updateFeeDisplay() {
            if (!baseShippingFee || baseShippingFee === 0) {
                // Nếu chưa có phí tiêu chuẩn, hiển thị đang tính
                if (shippingFeeDisplay) shippingFeeDisplay.textContent = 'Đang tính...';
                if (shippingFeeInput) shippingFeeInput.value = 0;
                const shippingFeeSummary = document.getElementById('shipping-fee-summary');
                if (shippingFeeSummary) shippingFeeSummary.textContent = 'Đang tính...';
                if (window.addressManager && typeof window.addressManager.updateTotalPayment === 'function') {
                    window.addressManager.updateTotalPayment();
                }
                return;
            }
            let finalFee = baseShippingFee;
            if (expressRadio && expressRadio.checked) {
                finalFee = Math.round(baseShippingFee * 1.5);
            }
            if (shippingFeeDisplay) {
                shippingFeeDisplay.textContent = new Intl.NumberFormat('vi-VN').format(finalFee) + 'đ';
            }
            if (shippingFeeInput) {
                shippingFeeInput.value = finalFee;
            }
            const shippingFeeSummary = document.getElementById('shipping-fee-summary');
            if (shippingFeeSummary) {
                shippingFeeSummary.textContent = new Intl.NumberFormat('vi-VN').format(finalFee) + 'đ';
            }
            if (window.addressManager && typeof window.addressManager.updateTotalPayment === 'function') {
                window.addressManager.updateTotalPayment();
            }
        }

        // Chỉ cập nhật baseShippingFee khi tính phí xong
        document.addEventListener('shipping-fee-updated', function(e) {
            baseShippingFee = parseInt(e.detail, 10) || 0;
            updateFeeDisplay();
        });

        if (standardRadio) {
            standardRadio.addEventListener('change', function() {
                if (this.checked) {
                    updateFeeDisplay();
                }
            });
        }
        if (expressRadio) {
            expressRadio.addEventListener('change', function() {
                if (this.checked) {
                    updateFeeDisplay();
                }
            });
        }
    });
})(window);