// Quản lý mã khuyến mãi
class PromoManager {
    constructor() {
        this.originalAmount = 0; // Thêm biến lưu giá gốc
        this.shippingFee = 0;
        this.discountAmount = 0;

        // Khởi tạo các elements
        this.totalPayment = document.getElementById('total-payment');
        this.totalAmount = document.getElementById('totalAmount');
        this.discountRow = document.getElementById('discount-row');
        this.discountAmountElement = document.getElementById('discount-amount');
        this.discountPercentText = document.getElementById('discount-percent-text');
        this.discountPercent = document.getElementById('discount-percent');
        this.originalPriceRow = document.getElementById('original-price-row');
        this.originalPrice = document.getElementById('original-price');
        this.promoCode = document.getElementById('promoCode'); // Sửa lại đúng id input hidden

        // Thêm listener cho sự kiện shippingFeeUpdated
        document.addEventListener('shippingFeeUpdated', (event) => {
            console.log('Received shippingFeeUpdated event:', event.detail);
            this.updateTotalWithShipping(event.detail);
        });

        // Lắng nghe sự kiện click cho nút áp dụng mã khuyến mãi
        document.querySelectorAll('.apply-promo').forEach(btn => {
            btn.addEventListener('click', (e) => {
                const code = btn.getAttribute('data-code');
                if (code) {
                    this.applyPromo(code);
                }
            });
        });
    }

    formatCurrency(amount) {
        // Làm tròn số
        amount = Math.round(amount);
        return new Intl.NumberFormat('vi-VN').format(amount);
    }

    showMessage(message, isError = false) {
        const alert = isError ? this.errorAlert : this.successAlert;
        if (alert) {
            alert.textContent = message;
            alert.classList.remove('d-none');
            setTimeout(() => alert.classList.add('d-none'), 3000);
        }
    }

    updateTotalWithShipping(detail) {
        console.log('updateTotalWithShipping called with:', detail);

        // Lấy giá trị gốc của đơn hàng từ detail hoặc input hidden
        const originalAmount = detail.originalAmount || parseInt(document.getElementById('totalValue').value) || 0;

        // Lấy phí vận chuyển
        const shippingFee = parseInt(detail.shippingFee) || 0;

        // Lưu giá trị phí vận chuyển
        this.shippingFee = shippingFee;

        // Lấy giá trị giảm giá hiện tại
        const discount = this.discountAmountElement ?
            parseInt(this.discountAmountElement.textContent.replace(/[^\d]/g, '')) || 0 : 0;

        console.log('Calculating final total with:', {
            originalAmount,
            shippingFee,
            discount
        });

        // Tính tổng tiền cuối cùng: Giá gốc + Phí vận chuyển - Giảm giá
        const finalTotal = originalAmount + shippingFee - discount;

        console.log('Final total calculated:', finalTotal);

        // Cập nhật hiển thị tổng tiền thanh toán
        if (this.totalPayment) {
            const formattedTotal = new Intl.NumberFormat('vi-VN').format(finalTotal);
            this.totalPayment.textContent = formattedTotal + 'đ';
            console.log('Updated total payment display:', formattedTotal + 'đ');
        }

        // Lưu tổng tiền vào input hidden
        if (this.totalAmount) {
            this.totalAmount.value = finalTotal;
            console.log('Updated total amount input:', finalTotal);
        }

        // Ghi log để debug
        console.log('Final payment breakdown:', {
            originalAmount: originalAmount,
            discount: discount,
            shippingFee: shippingFee,
            finalTotal: finalTotal,
            calculation: `${originalAmount} + ${shippingFee} - ${discount} = ${finalTotal}`
        });
    }

    updateTotalPayment(originalAmount, discount) {
        console.log('Updating total payment:', {
            originalAmount,
            discount
        });

        // Lưu giá trị gốc và giảm giá
        this.originalAmount = originalAmount;
        this.discountAmount = discount;

        // Tính tổng tiền cuối cùng và làm tròn
        const finalTotal = Math.round(originalAmount - discount);

        // Cập nhật hiển thị tổng tiền thanh toán
        if (this.totalPayment) {
            const formattedTotal = this.formatCurrency(finalTotal);
            this.totalPayment.textContent = formattedTotal + 'đ';
            console.log('Updated total payment display:', formattedTotal + 'đ');
        }

        // Lưu tổng tiền vào input hidden
        if (this.totalAmount) {
            this.totalAmount.value = finalTotal;
            console.log('Updated total amount input:', finalTotal);
        }

        // Cập nhật hiển thị giá gốc chỉ khi có giảm giá
        const hasDiscount = discount > 0;
        if (this.originalPriceRow && this.originalPrice) {
            this.originalPriceRow.style.display = hasDiscount ? 'flex' : 'none';
            if (hasDiscount) {
                this.originalPrice.textContent = this.formatCurrency(originalAmount) + 'đ';
            }
        }

        // Ghi log để debug
        console.log('Final payment breakdown:', {
            originalAmount: originalAmount,
            discount: discount,
            finalTotal: finalTotal,
            hasDiscount: hasDiscount,
            calculation: `${originalAmount} - ${discount} = ${finalTotal}`
        });
    }

    updateDisplay(discount, isPercent = false, percentValue = 0) {
        console.log('updateDisplay called with:', {
            discount,
            isPercent,
            percentValue
        });

        // Cập nhật số tiền giảm giá (làm tròn)
        if (this.discountAmountElement) {
            this.discountAmountElement.textContent = this.formatCurrency(discount);
        }

        // Hiển thị hoặc ẩn phần giảm giá và giá gốc
        const hasDiscount = discount > 0;
        if (this.discountRow) {
            this.discountRow.style.display = hasDiscount ? 'flex' : 'none';
        }

        // Hiển thị giá gốc khi có giảm giá
        if (this.originalPriceRow && this.originalPrice) {
            this.originalPriceRow.style.display = hasDiscount ? 'flex' : 'none';
            // Sử dụng giá gốc đã lưu
            this.originalPrice.textContent = this.formatCurrency(this.originalAmount) + 'đ';
        }

        // Hiển thị phần trăm nếu có
        if (this.discountPercentText && this.discountPercent) {
            if (isPercent && percentValue > 0) {
                this.discountPercent.textContent = percentValue;
                this.discountPercentText.style.display = 'inline';
            } else {
                this.discountPercentText.style.display = 'none';
            }
        }

        // Lấy lại phí vận chuyển hiện tại
        const shippingFee = this.shippingFee || parseInt(document.getElementById('shippingFee').value) || 0;
        // Lấy lại tổng tiền hàng hiện tại (chưa có phí vận chuyển)
        const productTotal = parseInt(document.getElementById('totalValue').value) || 0;
        // Gọi lại updateTotalWithShipping để cập nhật tổng tiền đúng
        this.updateTotalWithShipping({
            originalAmount: productTotal,
            shippingFee: shippingFee
        });
    }

    async applyPromo(code) {
        try {
            console.log('Applying promo code:', code);
            // Lấy tổng tiền hàng (không gồm phí ship) để truyền vào API
            const total = parseFloat(document.getElementById('totalValue').value) || 0;

            const response = await fetch('check_promo.php', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json'
                },
                body: JSON.stringify({
                    promo_code: code,
                    total_amount: total
                })
            });

            const data = await response.json();
            console.log('Promo API response:', data);

            if (data.success) {
                // Lưu mã đã áp dụng
                if (this.promoCode) {
                    this.promoCode.value = code; // Đảm bảo cập nhật đúng input
                }

                // Cập nhật hiển thị
                this.updateDisplay(
                    data.discount,
                    data.discount_type === 'percent',
                    data.percent_value
                );
                this.showMessage(data.message);
            } else {
                this.showMessage(data.message || 'Không thể áp dụng mã khuyến mãi', true);
            }
        } catch (error) {
            console.error('Error applying promo:', error);
            this.showMessage('Có lỗi xảy ra khi áp dụng mã khuyến mãi', true);
        }
    }
}

// Khởi tạo khi trang đã tải xong
document.addEventListener('DOMContentLoaded', () => {
    window.promoManager = new PromoManager();
});