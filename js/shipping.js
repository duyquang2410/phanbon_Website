// Quản lý vận chuyển GHTK
class ShippingManager {
    constructor() {
        this.initializeElements();
        this.setupEventListeners();
    }

    initializeElements() {
        // Form elements
        this.form = document.getElementById('checkoutForm');
        this.shippingFeeDisplay = document.getElementById('shipping-fee');
        this.shippingFeeInput = document.querySelector('input[name="shipping_fee"]');
        this.totalWeightInput = document.querySelector('input[name="total_weight"]');
        this.totalValueInput = document.querySelector('input[name="total_value"]');
        this.errorContainer = document.getElementById('error-message');

        // Address elements
        this.provinceSelect = document.getElementById('province');
        this.districtSelect = document.getElementById('district');
        this.wardSelect = document.getElementById('ward');
        this.addressInput = document.getElementById('address');

        // Payment elements
        this.totalPaymentSpan = document.querySelector('.total-row .text-danger');
        this.totalAmountInput = document.querySelector('input[name="total_amount"]');
        this.promoCodeInput = document.getElementById('promoCodeInput');

        // Pickup information
        this.pickupInfo = {
            province: document.querySelector('input[name="pick_province"]').value,
            district: document.querySelector('input[name="pick_district"]').value,
            ward: document.querySelector('input[name="pick_ward"]').value,
            address: document.querySelector('input[name="pick_address"]').value
        };

        console.log('ShippingManager elements initialized:', {
            form: !!this.form,
            shippingFeeDisplay: !!this.shippingFeeDisplay,
            provinceSelect: !!this.provinceSelect,
            pickupInfo: this.pickupInfo
        });
    }

    setupEventListeners() {
        // Theo dõi thay đổi địa chỉ
        this.provinceSelect.addEventListener('change', () => this.handleAddressChange());
        this.districtSelect.addEventListener('change', () => this.handleAddressChange());
        this.wardSelect.addEventListener('change', () => this.handleAddressChange());
        this.addressInput.addEventListener('change', () => this.handleAddressChange());

        // Xử lý submit form
        this.form.addEventListener('submit', (e) => this.handleFormSubmit(e));

        console.log('ShippingManager event listeners set up');
    }

    handleAddressChange() {
        // Chỉ tính phí vận chuyển khi đã chọn đầy đủ tỉnh/thành, quận/huyện, phường/xã
        if (this.provinceSelect.value && this.districtSelect.value && this.wardSelect.value) {
            this.calculateShippingFee();
        } else {
            // Nếu chưa chọn đủ thông tin địa chỉ, hiển thị thông báo
            this.shippingFeeDisplay.textContent = 'Vui lòng chọn đầy đủ địa chỉ';
            this.shippingFeeInput.value = 0;
            this.updateTotalPayment();
        }
    }

    validateAddress() {
        const hasRequiredFields = this.provinceSelect.value &&
            this.districtSelect.value &&
            this.wardSelect.value;

        // Địa chỉ chi tiết là tùy chọn, không bắt buộc khi tính phí
        return hasRequiredFields;
    }

    async calculateShippingFee() {
        try {
            this.shippingFeeDisplay.textContent = 'Đang tính...';

            // Tính phí vận chuyển đơn giản dựa trên khoảng cách
            const baseShippingFee = 30000; // Phí cơ bản
            const weight = parseInt(this.totalWeightInput.value) || 1000;
            const weightFee = Math.floor(weight / 1000) * 5000; // 5000đ cho mỗi kg

            const shippingFee = baseShippingFee + weightFee;

            this.updateShippingFee(shippingFee);
            this.hideError();

        } catch (error) {
            console.error('Error calculating shipping fee:', error);
            this.showError(`Lỗi tính phí vận chuyển: ${error.message}`);
            this.shippingFeeDisplay.textContent = 'Không thể tính phí';
            this.shippingFeeInput.value = 0;
            this.updateTotalPayment();
        }
    }

    updateShippingFee(fee) {
        this.shippingFeeDisplay.textContent = `${fee.toLocaleString('vi-VN')}đ`;
        this.shippingFeeInput.value = fee;
        this.updateTotalPayment();
    }

    updateTotalPayment() {
        const totalAmount = parseFloat(this.totalAmountInput.value) || 0;
        const shippingFee = parseFloat(this.shippingFeeInput.value) || 0;
        const promoDiscount = 0; // Implement promo logic if needed

        const total = totalAmount + shippingFee - promoDiscount;
        this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(total);
    }

    showError(message) {
        if (this.errorContainer) {
            this.errorContainer.textContent = message;
            this.errorContainer.style.display = 'block';
        }
    }

    hideError() {
        if (this.errorContainer) {
            this.errorContainer.style.display = 'none';
        }
    }

    async handleFormSubmit(event) {
        if (!this.validateAddress()) {
            event.preventDefault();
            this.showError('Vui lòng chọn đầy đủ địa chỉ giao hàng');
            return;
        }

        if (!this.shippingFeeInput.value || this.shippingFeeInput.value === '0') {
            event.preventDefault();
            this.showError('Vui lòng đợi tính phí vận chuyển');
            return;
        }

        // Form hợp lệ, cho phép submit
        this.hideError();
    }
}

// Khởi tạo ShippingManager khi trang đã tải xong
document.addEventListener('DOMContentLoaded', function() {
    console.log('Initializing ShippingManager...');
    try {
        window.shippingManager = new ShippingManager();
    } catch (error) {
        console.error('Error initializing ShippingManager:', error);
    }
});

class ViettelPostAPI {
    constructor() {
        this.baseUrl = 'viettelpost_api.php';
    }

    async logApiCall(endpoint, params, response = null, error = null) {
        try {
            await fetch('log_viettelpost.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    action: 'log_api_call',
                    endpoint: endpoint,
                    params: params,
                    response: response,
                    error: error
                })
            });
        } catch (e) {
            console.error('Logging error:', e);
        }
    }

    async getProvinces() {
        try {
            const response = await fetch(`${this.baseUrl}?endpoint=categories/listProvince`);
            const data = await response.json();
            await this.logApiCall('listProvince', {}, data);
            return data.data || [];
        } catch (error) {
            await this.logApiCall('listProvince', {}, null, error.message);
            console.error('Get provinces error:', error);
            return [];
        }
    }

    async getDistricts(provinceId) {
        try {
            const response = await fetch(`${this.baseUrl}?endpoint=categories/listDistrict&provinceId=${provinceId}`);
            const data = await response.json();
            await this.logApiCall('listDistrict', { provinceId }, data);
            return data.data || [];
        } catch (error) {
            await this.logApiCall('listDistrict', { provinceId }, null, error.message);
            console.error('Get districts error:', error);
            return [];
        }
    }

    async getWards(districtId) {
        try {
            const response = await fetch(`${this.baseUrl}?endpoint=categories/listWards&districtId=${districtId}`);
            const data = await response.json();
            await this.logApiCall('listWards', { districtId }, data);
            return data.data || [];
        } catch (error) {
            await this.logApiCall('listWards', { districtId }, null, error.message);
            console.error('Get wards error:', error);
            return [];
        }
    }

    async calculateShipping(params) {
        try {
            const response = await fetch(`${this.baseUrl}?endpoint=order/getPriceAll`, {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(params)
            });
            const data = await response.json();
            await this.logApiCall('getPriceAll', params, data);
            return data.data || [];
        } catch (error) {
            await this.logApiCall('getPriceAll', params, null, error.message);
            console.error('Calculate shipping error:', error);
            return [];
        }
    }
}

class AddressSelector {
    constructor() {
        this.api = new ViettelPostAPI();
        this.provinceSelect = document.getElementById('province');
        this.districtSelect = document.getElementById('district');
        this.wardSelect = document.getElementById('ward');
        this.addressInput = document.getElementById('address');
        this.streetInput = document.getElementById('street_address');

        // Khởi tạo các select với option mặc định
        this.provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
        this.districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        this.wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

        this.init();
    }

    async init() {
        try {
            await this.loadProvinces();
            this.setupEventListeners();
        } catch (error) {
            console.error('Error initializing AddressSelector:', error);
            this.showError('Không thể tải danh sách tỉnh/thành phố');
        }
    }

    async loadProvinces() {
        const provinces = await this.api.getProvinces();
        if (provinces.length === 0) {
            this.showError('Không thể tải danh sách tỉnh/thành phố');
        } else {
            this.populateSelect(this.provinceSelect, provinces, 'PROVINCE_ID', 'PROVINCE_NAME');
        }
    }

    async loadDistricts(provinceId) {
        this.districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
        this.wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        if (provinceId) {
            const districts = await this.api.getDistricts(provinceId);
            if (districts.length === 0) {
                this.showError('Không thể tải danh sách quận/huyện');
            } else {
                this.populateSelect(this.districtSelect, districts, 'DISTRICT_ID', 'DISTRICT_NAME');
            }
        }
    }

    async loadWards(districtId) {
        this.wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';
        if (districtId) {
            const wards = await this.api.getWards(districtId);
            if (wards.length === 0) {
                this.showError('Không thể tải danh sách phường/xã');
            } else {
                this.populateSelect(this.wardSelect, wards, 'WARDS_ID', 'WARDS_NAME');
            }
        }
    }

    populateSelect(select, data, valueKey, textKey) {
        select.innerHTML = `<option value="">Chọn ${select.getAttribute('data-placeholder')}</option>`;
        data.forEach(item => {
            const option = document.createElement('option');
            option.value = item[valueKey];
            option.textContent = item[textKey];
            select.appendChild(option);
        });
    }

    setupEventListeners() {
        this.provinceSelect.addEventListener('change', async(e) => {
            await this.loadDistricts(e.target.value);
            this.updateFullAddress();
            this.triggerShippingCalculation();
        });

        this.districtSelect.addEventListener('change', async(e) => {
            await this.loadWards(e.target.value);
            this.updateFullAddress();
            this.triggerShippingCalculation();
        });

        this.wardSelect.addEventListener('change', () => {
            this.updateFullAddress();
            this.triggerShippingCalculation();
        });

        if (this.streetInput) {
            this.streetInput.addEventListener('change', () => {
                this.updateFullAddress();
            });
        }
    }

    updateFullAddress() {
        const province = this.provinceSelect.selectedOptions && this.provinceSelect.selectedOptions[0] ? this.provinceSelect.selectedOptions[0].text : '';
        const district = this.districtSelect.selectedOptions && this.districtSelect.selectedOptions[0] ? this.districtSelect.selectedOptions[0].text : '';
        const ward = this.wardSelect.selectedOptions && this.wardSelect.selectedOptions[0] ? this.wardSelect.selectedOptions[0].text : '';
        const street = this.streetInput ? this.streetInput.value : '';

        if (province && district && ward) {
            this.addressInput.value = `${street ? street + ', ' : ''}${ward}, ${district}, ${province}`;
        }
    }

    getSelectedIds() {
        return {
            provinceId: this.provinceSelect.value,
            districtId: this.districtSelect.value,
            wardId: this.wardSelect.value
        };
    }

    showError(message) {
        const errorContainer = document.getElementById('error-message');
        if (errorContainer) {
            errorContainer.textContent = message;
            errorContainer.style.display = 'block';
        }
    }

    triggerShippingCalculation() {
        if (window.shippingCalculator) {
            window.shippingCalculator.calculateShippingFee();
        }
    }
}

class ShippingCalculator {
    constructor() {
        console.log('Initializing ShippingCalculator...');

        // Get DOM elements
        this.form = document.getElementById('checkoutForm');
        this.shippingFeeDisplay = document.getElementById('shipping-fee');
        this.shippingFeeSummary = document.getElementById('shipping-fee-summary');
        this.shippingFeeInput = document.querySelector('input[name="shipping_fee"]');
        this.totalWeightInput = document.querySelector('input[name="total_weight"]');
        this.totalValueInput = document.querySelector('input[name="total_value"]');
        this.totalPaymentSpan = document.querySelector('.total-row .text-danger');
        this.errorContainer = document.getElementById('error-message');

        // Add shipping options container
        this.shippingOptionsContainer = document.createElement('div');
        this.shippingOptionsContainer.id = 'shipping-options';
        this.shippingOptionsContainer.className = 'mb-3';
        if (this.shippingFeeDisplay) {
            this.shippingFeeDisplay.parentNode.insertBefore(this.shippingOptionsContainer, this.shippingFeeDisplay.nextSibling);
        }

        // Log found elements
        console.log('Found elements:', {
            form: !!this.form,
            shippingFeeDisplay: !!this.shippingFeeDisplay,
            shippingFeeSummary: !!this.shippingFeeSummary,
            shippingFeeInput: !!this.shippingFeeInput,
            totalWeightInput: !!this.totalWeightInput,
            totalValueInput: !!this.totalValueInput,
            totalPaymentSpan: !!this.totalPaymentSpan,
            errorContainer: !!this.errorContainer,
            shippingOptionsContainer: !!this.shippingOptionsContainer
        });

        // Get pickup information from hidden inputs
        const pickProvinceInput = document.querySelector('input[name="pick_province"]');
        const pickDistrictInput = document.querySelector('input[name="pick_district"]');

        this.pickupInfo = {
            provinceId: pickProvinceInput ? pickProvinceInput.value : 1,
            districtId: pickDistrictInput ? pickDistrictInput.value : 1
        };

        console.log('Pickup info:', this.pickupInfo);

        // Get API instance from AddressSelector
        if (!window.addressSelector || !window.addressSelector.api) {
            throw new Error('AddressSelector not initialized');
        }
        this.viettelPostApi = window.addressSelector.api;

        // Shipping service descriptions
        this.serviceDescriptions = {
            'VCN': 'Chuyển phát tiêu chuẩn (2-3 ngày)',
            'VPT': 'Chuyển phát nhanh (1-2 ngày)',
            'VTK': 'Tiết kiệm (3-5 ngày)',
            'V60': 'Nhanh 60 phút (trong ngày)',
            'V30': 'Siêu tốc 30 phút (trong ngày)'
        };

        this.init();
    }

    init() {
        console.log('Initializing shipping calculator...');
        this.initializeEventListeners();
        this.calculateShippingFee();
    }

    initializeEventListeners() {
        console.log('Setting up shipping event listeners...');
        if (this.form) {
            this.form.addEventListener('submit', this.handleFormSubmit.bind(this));
        }
    }

    showError(message) {
        console.error('Shipping error:', message);
        if (this.errorContainer) {
            this.errorContainer.textContent = message;
            this.errorContainer.style.display = 'block';
        }
    }

    hideError() {
        if (this.errorContainer) {
            this.errorContainer.style.display = 'none';
        }
    }

    displayShippingOptions(options) {
        if (!this.shippingOptionsContainer) return;

        this.shippingOptionsContainer.innerHTML = '';
        if (!options || options.length === 0) {
            this.shippingOptionsContainer.style.display = 'none';
            return;
        }

        const form = document.createElement('form');
        form.className = 'shipping-options-form';

        options.forEach((option, index) => {
            const serviceCode = option.MA_DV_CHINH || 'Unknown';
            const serviceName = this.serviceDescriptions[serviceCode] || `Dịch vụ ${serviceCode}`;
            const fee = option.GIA_CUOC || 0;
            const estimatedTime = option.THOI_GIAN || 'N/A';

            const radioDiv = document.createElement('div');
            radioDiv.className = 'form-check mb-2';

            const input = document.createElement('input');
            input.type = 'radio';
            input.className = 'form-check-input';
            input.name = 'shipping-option';
            input.id = `shipping-option-${index}`;
            input.value = fee;
            input.checked = index === 0;

            const label = document.createElement('label');
            label.className = 'form-check-label';
            label.htmlFor = `shipping-option-${index}`;
            label.innerHTML = `
                <strong>${serviceName}</strong><br>
                <span class="text-muted">Phí: ${new Intl.NumberFormat('vi-VN').format(fee)}đ</span>
                <span class="text-muted"> | Thời gian dự kiến: ${estimatedTime}</span>
            `;

            radioDiv.appendChild(input);
            radioDiv.appendChild(label);
            form.appendChild(radioDiv);

            input.addEventListener('change', () => {
                if (input.checked) {
                    this.updateShippingFee(fee);
                }
            });
        });

        this.shippingOptionsContainer.appendChild(form);
        this.shippingOptionsContainer.style.display = 'block';
    }

    async calculateShippingFee() {
        try {
            console.log('Calculating shipping fee...');
            const addressIds = window.addressSelector.getSelectedIds();

            // Only calculate if we have all required address fields
            if (!addressIds.provinceId || !addressIds.districtId) {
                this.showError('Vui lòng chọn đầy đủ địa chỉ giao hàng');
                this.updateShippingFee(0);
                return;
            }

            const weight = parseInt(this.totalWeightInput.value) || 1000; // Default to 1kg if not set
            const orderValue = parseFloat(this.totalValueInput.value) || 0;

            console.log('Shipping calculation params:', {
                weight,
                orderValue,
                addressIds,
                pickupInfo: this.pickupInfo
            });

            const params = {
                SENDER_PROVINCE: parseInt(this.pickupInfo.provinceId),
                SENDER_DISTRICT: parseInt(this.pickupInfo.districtId),
                RECEIVER_PROVINCE: parseInt(addressIds.provinceId),
                RECEIVER_DISTRICT: parseInt(addressIds.districtId),
                PRODUCT_TYPE: "HH", // Hàng hóa thông thường
                PRODUCT_WEIGHT: weight,
                PRODUCT_PRICE: orderValue,
                MONEY_COLLECTION: orderValue,
                TYPE: 1 // Giao hàng tiêu chuẩn
            };

            const shippingOptions = await this.viettelPostApi.calculateShipping(params);
            console.log('Shipping options:', shippingOptions);

            if (shippingOptions && shippingOptions.length > 0) {
                this.displayShippingOptions(shippingOptions);
                this.updateShippingFee(shippingOptions[0].GIA_CUOC);
                this.hideError();
            } else {
                console.warn('No valid shipping options returned, using fallback');
                this.fallbackShippingFee(weight);
            }
        } catch (error) {
            console.error('Error calculating shipping fee:', error);
            this.fallbackShippingFee(parseInt(this.totalWeightInput.value) || 1000);
        }
    }

    fallbackShippingFee(weight) {
        console.log('Using fallback shipping calculation for weight:', weight);
        // Calculate fallback shipping fee: 30,000đ base + 5,000đ per kg
        const baseShippingFee = 30000;
        const weightFee = Math.floor(weight / 1000) * 5000;
        const shippingFee = baseShippingFee + weightFee;

        this.updateShippingFee(shippingFee);
        this.showError('Không thể kết nối với Viettel Post. Sử dụng phí vận chuyển mặc định.');
    }

    updateShippingFee(fee) {
        console.log('Updating shipping fee:', fee);
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

        this.updateTotalPayment();
    }

    updateTotalPayment() {
        if (!this.totalPaymentSpan) return;

        const totalAmount = parseFloat(this.totalValueInput.value) || 0;
        const shippingFee = parseFloat(this.shippingFeeInput.value) || 0;

        // Get discount amount from the DOM
        const discountElement = document.getElementById('discount-amount');
        let promoDiscount = 0;
        if (discountElement && discountElement.textContent) {
            promoDiscount = parseFloat(discountElement.textContent.replace(/[^0-9]/g, '')) || 0;
        }

        const total = totalAmount + shippingFee - promoDiscount;

        console.log('Updating total payment:', {
            totalAmount,
            shippingFee,
            promoDiscount,
            total
        });

        this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN', {
            style: 'currency',
            currency: 'VND'
        }).format(total);
    }

    handleFormSubmit(event) {
        if (!this.shippingFeeInput.value || this.shippingFeeInput.value === '0') {
            event.preventDefault();
            this.showError('Vui lòng đợi tính phí vận chuyển');
            return;
        }
    }
}