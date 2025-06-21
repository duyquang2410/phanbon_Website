// Quản lý địa chỉ và tính phí vận chuyển ViettelPost
class AddressManager {
    constructor() {
        console.log('Khởi tạo AddressManager...');
        this.initializeElements();
        this.setupConfig();

        if (this.validateElements()) {
            this.setupEventListeners();
            this.loadProvinces();
        } else {
            console.error('Thiếu các phần tử bắt buộc');
            this.showError('Không thể khởi tạo form địa chỉ. Vui lòng tải lại trang.');
        }
    }

    initializeElements() {
        this.provinceSelect = document.getElementById('province');
        this.districtSelect = document.getElementById('district');
        this.wardSelect = document.getElementById('ward');
        this.shippingFeeDisplay = document.getElementById('shipping-fee');
        this.shippingFeeInput = document.querySelector('input[name="shipping_fee"]');
        this.errorMessageContainer = document.getElementById('error-message');
        this.totalPriceInput = document.querySelector('input[name="total_amount"]');
        this.totalWeightInput = document.querySelector('input[name="total_weight"]');
        this.totalValueInput = document.querySelector('input[name="total_value"]');
        this.totalPaymentSpan = document.querySelector('.total-row .text-danger');

        console.log('Tìm thấy các phần tử:', {
            provinceSelect: !!this.provinceSelect,
            districtSelect: !!this.districtSelect,
            wardSelect: !!this.wardSelect,
            shippingFeeDisplay: !!this.shippingFeeDisplay,
            shippingFeeInput: !!this.shippingFeeInput,
            errorMessageContainer: !!this.errorMessageContainer,
            totalPriceInput: !!this.totalPriceInput,
            totalWeightInput: !!this.totalWeightInput,
            totalValueInput: !!this.totalValueInput,
            totalPaymentSpan: !!this.totalPaymentSpan
        });
    }

    setupConfig() {
        // Thông tin điểm lấy hàng mặc định
        this.pickProvince = "1"; // Hà Nội
        this.pickDistrict = "1"; // Ba Đình
        this.pickWard = "1"; // Phúc Xá
        this.pickAddress = "123 Đường Láng";

        this.shippingServices = [
            { id: 'VCN', name: 'Chuyển phát tiêu chuẩn', priority: 1 },
            { id: 'VPT', name: 'Chuyển phát nhanh', priority: 2 }
        ].sort((a, b) => a.priority - b.priority);

        this.defaultProductConfig = {
            weight: 1000, // 1kg
            length: 20,
            width: 20,
            height: 20,
            quantity: 1
        };

        this.isCalculating = false;
        const discountInput = document.querySelector('input[name="total_discount"]');
        this.totalDiscount = parseFloat(discountInput ? discountInput.value : '0');
    }

    validateElements() {
        const requiredElements = [
            this.provinceSelect,
            this.districtSelect,
            this.wardSelect,
            this.shippingFeeDisplay,
            this.shippingFeeInput,
            this.totalPriceInput,
            this.totalWeightInput,
            this.totalValueInput,
            this.totalPaymentSpan
        ];

        return requiredElements.every(element => !!element);
    }

    setupEventListeners() {
        console.log('Cài đặt sự kiện...');

        this.provinceSelect.addEventListener('change', () => {
            this.loadDistricts();
            this.clearWards();
            this.calculateShippingDebounced();
        });

        this.districtSelect.addEventListener('change', () => {
            this.loadWards();
            this.calculateShippingDebounced();
        });

        this.wardSelect.addEventListener('change', () => {
            this.calculateShippingDebounced();
        });

        this.calculateShippingDebounced = this.debounce(this.calculateShipping.bind(this), 500);
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

    async fetchViettelPost(endpoint, params = {}) {
        try {
            const cleanEndpoint = endpoint.replace(/^\/+|\/+$/, '').trim();
            if (!cleanEndpoint) {
                throw new Error('Endpoint không được để trống');
            }

            const url = new URL('viettelpost_api.php', window.location.origin + window.location.pathname.replace(/[^/]+$/, ''));
            url.searchParams.append('endpoint', cleanEndpoint);
            Object.entries(params).forEach(([key, value]) => {
                url.searchParams.append(key, value);
            });

            console.log(`Gửi yêu cầu ViettelPost tới: ${url.toString()}`);

            const response = await fetch(url.toString(), {
                method: 'GET',
                headers: {
                    'Accept': 'application/json',
                    'Content-Type': 'application/json'
                }
            });

            if (!response.ok) {
                throw new Error(`Lỗi HTTP: ${response.status}`);
            }

            const contentType = response.headers.get('content-type');
            if (!contentType || !contentType.includes('application/json')) {
                const text = await response.text();
                console.error('Phản hồi không phải JSON:', text);
                throw new Error('Phản hồi không phải là JSON');
            }

            const data = await response.json();
            if (data.error) {
                throw new Error(data.error);
            }

            return data;
        } catch (error) {
            console.error('Lỗi API ViettelPost:', error);
            this.showError(`Lỗi khi gọi API: ${error.message}`);
            throw error;
        }
    }

    async loadProvinces() {
        try {
            this.provinceSelect.disabled = true;
            this.provinceSelect.innerHTML = '<option value="">Đang tải tỉnh/thành...</option>';

            const response = await this.fetchViettelPost('categories/listProvince');
            if (!response.data || !Array.isArray(response.data)) {
                throw new Error('Dữ liệu tỉnh/thành không hợp lệ');
            }

            // Sắp xếp tỉnh/thành theo alphabet
            const sortedProvinces = response.data.sort((a, b) =>
                a.PROVINCE_NAME.localeCompare(b.PROVINCE_NAME, 'vi-VN')
            );

            this.provinceSelect.innerHTML = '<option value="">Chọn tỉnh/thành</option>';
            sortedProvinces.forEach(province => {
                const option = document.createElement('option');
                option.value = province.PROVINCE_ID;
                option.textContent = province.PROVINCE_NAME;
                this.provinceSelect.appendChild(option);
            });

            this.provinceSelect.disabled = false;
        } catch (error) {
            console.error('Lỗi tải tỉnh/thành:', error);
            this.showError('Không thể tải danh sách tỉnh/thành. Vui lòng tải lại trang.');
            this.provinceSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
            this.provinceSelect.disabled = false;
        }
    }

    async loadDistricts() {
        try {
            this.districtSelect.disabled = true;
            this.districtSelect.innerHTML = '<option value="">Đang tải quận/huyện...</option>';
            this.clearWards();

            if (!this.provinceSelect.value) {
                this.districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
                return;
            }

            const response = await this.fetchViettelPost('categories/listDistrict', {
                provinceId: this.provinceSelect.value
            });

            // Sắp xếp quận/huyện theo alphabet
            const sortedDistricts = response.data.sort((a, b) =>
                a.DISTRICT_NAME.localeCompare(b.DISTRICT_NAME, 'vi-VN')
            );

            this.districtSelect.innerHTML = '<option value="">Chọn quận/huyện</option>';
            sortedDistricts.forEach(district => {
                const option = document.createElement('option');
                option.value = district.DISTRICT_ID;
                option.textContent = district.DISTRICT_NAME;
                this.districtSelect.appendChild(option);
            });

            this.districtSelect.disabled = false;
        } catch (error) {
            console.error('Lỗi tải quận/huyện:', error);
            this.showError('Không thể tải danh sách quận/huyện. Vui lòng thử lại.');
            this.districtSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        }
    }

    async loadWards() {
        try {
            this.wardSelect.disabled = true;
            this.wardSelect.innerHTML = '<option value="">Đang tải phường/xã...</option>';

            if (!this.districtSelect.value) {
                this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
                return;
            }

            const response = await this.fetchViettelPost('categories/listWards', {
                districtId: this.districtSelect.value
            });

            // Sắp xếp phường/xã theo alphabet
            const sortedWards = response.data.sort((a, b) =>
                a.WARDS_NAME.localeCompare(b.WARDS_NAME, 'vi-VN')
            );

            this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
            sortedWards.forEach(ward => {
                const option = document.createElement('option');
                option.value = ward.WARDS_ID;
                option.textContent = ward.WARDS_NAME;
                this.wardSelect.appendChild(option);
            });

            this.wardSelect.disabled = false;
        } catch (error) {
            console.error('Lỗi tải phường/xã:', error);
            this.showError('Không thể tải danh sách phường/xã. Vui lòng thử lại.');
            this.wardSelect.innerHTML = '<option value="">Lỗi tải dữ liệu</option>';
        }
    }

    clearWards() {
        this.wardSelect.innerHTML = '<option value="">Chọn phường/xã</option>';
        this.wardSelect.disabled = true;
    }

    showError(message) {
        if (this.errorMessageContainer) {
            this.errorMessageContainer.textContent = message;
            this.errorMessageContainer.style.display = 'block';
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

    async calculateShippingFee() {
        try {
            const weight = parseInt(this.totalWeightInput.value) || 1000; // Mặc định 1kg
            const data = {
                SENDER_PROVINCE: this.pickProvince,
                SENDER_DISTRICT: this.pickDistrict,
                SENDER_WARD: this.pickWard,
                RECEIVER_PROVINCE: this.provinceSelect.value,
                RECEIVER_DISTRICT: this.districtSelect.value,
                RECEIVER_WARD: this.wardSelect.value,
                PRODUCT_TYPE: "HH",
                PRODUCT_WEIGHT: weight,
                PRODUCT_PRICE: this.totalValueInput.value || 0,
                MONEY_COLLECTION: "0",
                TYPE: "1"
            };

            const response = await fetch('viettelpost_api.php?endpoint=order/getPriceAll', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify(data)
            });

            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }

            const result = await response.json();
            if (result.error) {
                throw new Error(result.error);
            }

            // Lấy giá vận chuyển thấp nhất từ các dịch vụ
            const services = result.data || [];
            if (services.length === 0) {
                throw new Error('Không có dịch vụ vận chuyển phù hợp');
            }

            const cheapestService = services.reduce((min, service) =>
                (!min || service.GIA_CUOC < min.GIA_CUOC) ? service : min
            );

            return cheapestService.GIA_CUOC;
        } catch (error) {
            console.error('Lỗi tính phí vận chuyển:', error);
            // Tính phí vận chuyển mặc định: 30,000đ + 5,000đ/kg
            const weight = parseInt(this.totalWeightInput.value) || 1000; // Mặc định 1kg
            const weightInKg = weight / 1000;
            return 30000 + (weightInKg * 5000);
        }
    }

    updateShippingFee(fee) {
        const formattedFee = new Intl.NumberFormat('vi-VN').format(fee);
        this.shippingFeeDisplay.textContent = `${formattedFee}đ`;
        this.shippingFeeInput.value = fee;
    }

    updateTotalPayment() {
        const totalAmount = parseFloat(this.totalPriceInput.value) || 0;
        const shippingFee = parseFloat(this.shippingFeeInput.value) || 0;
        const totalDiscount = this.totalDiscount || 0;

        const totalPayment = totalAmount + shippingFee - totalDiscount;
        this.totalPaymentSpan.textContent = new Intl.NumberFormat('vi-VN').format(totalPayment) + 'đ';
    }
}

// Khởi tạo AddressManager khi trang đã tải xong
document.addEventListener('DOMContentLoaded', () => {
    window.addressManager = new AddressManager();
});