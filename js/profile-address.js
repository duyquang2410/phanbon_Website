class ProfileAddressManager {
    constructor() {
        this.initializeElements();
        this.setupEventListeners();
        this.loadProvinces().then(() => {
            // Sau khi load tỉnh/thành phố xong, khôi phục địa chỉ đã lưu
            this.restoreAddress();
        });
    }

    initializeElements() {
        this.provinceSelect = document.getElementById('province');
        this.districtSelect = document.getElementById('district');
        this.wardSelect = document.getElementById('ward');
        this.streetInput = document.getElementById('street_address');
        this.fullAddressDisplay = document.getElementById('full-address-display').querySelector('span');
        this.fullAddressInput = document.getElementById('full_address');
        this.errorContainer = document.getElementById('address-error');
    }

    setupEventListeners() {
        // Xử lý sự kiện thay đổi tỉnh/thành phố
        this.provinceSelect.addEventListener('change', () => {
            this.loadDistricts(this.provinceSelect.value);
            this.updateFullAddress();
        });

        // Xử lý sự kiện thay đổi quận/huyện
        this.districtSelect.addEventListener('change', () => {
            this.loadWards(this.districtSelect.value);
            this.updateFullAddress();
        });

        // Xử lý sự kiện thay đổi phường/xã
        this.wardSelect.addEventListener('change', () => {
            this.updateFullAddress();
        });

        // Xử lý sự kiện thay đổi địa chỉ đường
        this.streetInput.addEventListener('input', () => {
            this.updateFullAddress();
        });
    }

    async fetchApi(endpoint, params = {}) {
        try {
            let url = `viettelpost_api.php?endpoint=${endpoint}`;
            if (Object.keys(params).length > 0) {
                url += '&' + new URLSearchParams(params).toString();
            }

            const response = await fetch(url);
            if (!response.ok) {
                throw new Error(`HTTP error! status: ${response.status}`);
            }
            const data = await response.json();
            return data;
        } catch (error) {
            console.error('API Error:', error);
            this.showError('Không thể tải dữ liệu. Vui lòng thử lại sau.');
            return null;
        }
    }

    async loadProvinces() {
        try {
            const response = await this.fetchApi('categories/listProvince');
            if (response && response.data) {
                this.provinceSelect.innerHTML = '<option value="">Chọn Tỉnh/Thành phố</option>';
                response.data.forEach(province => {
                    const option = document.createElement('option');
                    option.value = province.PROVINCE_ID;
                    option.textContent = province.PROVINCE_NAME;
                    this.provinceSelect.appendChild(option);
                });
            }
        } catch (error) {
            console.error('Error loading provinces:', error);
            this.showError('Không thể tải danh sách tỉnh/thành phố');
        }
    }

    async loadDistricts(provinceId) {
        if (!provinceId) return;

        try {
            this.districtSelect.disabled = true;
            this.wardSelect.disabled = true;
            this.districtSelect.innerHTML = '<option value="">Chọn Quận/Huyện</option>';
            this.wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

            const response = await this.fetchApi('categories/listDistrict', { provinceId });
            if (response && response.data) {
                response.data.forEach(district => {
                    const option = document.createElement('option');
                    option.value = district.DISTRICT_ID;
                    option.textContent = district.DISTRICT_NAME;
                    this.districtSelect.appendChild(option);
                });
                this.districtSelect.disabled = false;
            }
        } catch (error) {
            console.error('Error loading districts:', error);
            this.showError('Không thể tải danh sách quận/huyện');
        }
    }

    async loadWards(districtId) {
        if (!districtId) return;

        try {
            this.wardSelect.disabled = true;
            this.wardSelect.innerHTML = '<option value="">Chọn Phường/Xã</option>';

            const response = await this.fetchApi('categories/listWards', { districtId });
            if (response && response.data) {
                response.data.forEach(ward => {
                    const option = document.createElement('option');
                    option.value = ward.WARDS_ID;
                    option.textContent = ward.WARDS_NAME;
                    this.wardSelect.appendChild(option);
                });
                this.wardSelect.disabled = false;
            }
        } catch (error) {
            console.error('Error loading wards:', error);
            this.showError('Không thể tải danh sách phường/xã');
        }
    }

    updateFullAddress() {
        const street = this.streetInput.value.trim();
        const wardOption = this.wardSelect.selectedOptions && this.wardSelect.selectedOptions[0];
        const districtOption = this.districtSelect.selectedOptions && this.districtSelect.selectedOptions[0];
        const provinceOption = this.provinceSelect.selectedOptions && this.provinceSelect.selectedOptions[0];

        const ward = wardOption ? wardOption.text : '';
        const district = districtOption ? districtOption.text : '';
        const province = provinceOption ? provinceOption.text : '';

        const addressParts = [street, ward, district, province].filter(part => part);
        const fullAddress = addressParts.join(', ');

        this.fullAddressDisplay.textContent = fullAddress || 'Chưa có địa chỉ';
        this.fullAddressInput.value = fullAddress;
    }

    async restoreAddress() {
        if (!this.fullAddressInput.value) return;

        try {
            // Tách địa chỉ thành các phần
            const addressParts = this.fullAddressInput.value.split(', ');
            if (addressParts.length >= 4) {
                // Đặt giá trị cho đường
                this.streetInput.value = addressParts[0];

                // Tìm và chọn tỉnh/thành phố
                const provinceOption = Array.from(this.provinceSelect.options)
                    .find(option => option.text === addressParts[3]);
                if (provinceOption) {
                    this.provinceSelect.value = provinceOption.value;
                    // Load quận/huyện
                    await this.loadDistricts(provinceOption.value);

                    // Tìm và chọn quận/huyện
                    const districtOption = Array.from(this.districtSelect.options)
                        .find(option => option.text === addressParts[2]);
                    if (districtOption) {
                        this.districtSelect.value = districtOption.value;
                        // Load phường/xã
                        await this.loadWards(districtOption.value);

                        // Tìm và chọn phường/xã
                        const wardOption = Array.from(this.wardSelect.options)
                            .find(option => option.text === addressParts[1]);
                        if (wardOption) {
                            this.wardSelect.value = wardOption.value;
                        }
                    }
                }

                // Cập nhật địa chỉ đầy đủ
                this.updateFullAddress();
            }
        } catch (error) {
            console.error('Error restoring address:', error);
        }
    }

    showError(message) {
        if (this.errorContainer) {
            this.errorContainer.textContent = message;
            this.errorContainer.classList.remove('d-none');
        }
    }

    hideError() {
        if (this.errorContainer) {
            this.errorContainer.classList.add('d-none');
        }
    }

    isAddressComplete() {
        return (
            this.streetInput.value.trim() !== '' &&
            this.provinceSelect.value !== '' &&
            this.districtSelect.value !== '' &&
            this.wardSelect.value !== ''
        );
    }
}

// Khởi tạo khi trang đã load
document.addEventListener('DOMContentLoaded', function() {
    window.profileAddressManager = new ProfileAddressManager();
});