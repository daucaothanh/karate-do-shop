$(document).ready(function () {
    const csrfToken = $('meta[name="csrf-token"]').attr('content');

    $(document).on('submit', 'form[action*="/cart"], form[action*="/wishlist"]', function (event) {
        const form = $(this);
        const action = form.attr('action');

        if (!action || !/\/cart|\/wishlist/.test(action)) {
            return;
        }

        event.preventDefault();

        const fetchOptions = {
            method: 'POST',
            body: new FormData(this),
            credentials: 'same-origin',
            headers: {
                'X-CSRF-TOKEN': csrfToken || '',
                'X-Requested-With': 'XMLHttpRequest',
                'Accept': 'application/json'
            }
        };

        fetch(action, fetchOptions)
            .then(async response => {
                const text = await response.text();
                let payload = {};

                try {
                    payload = text ? JSON.parse(text) : {};
                } catch (error) {
                    payload = { message: text || 'Thao tác thành công.' };
                }

                if (!response.ok) {
                    throw new Error(payload.message || 'Có lỗi xảy ra.');
                }

                if (payload.message && typeof toastr !== 'undefined') {
                    toastr.success(payload.message, 'Thành công', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 2500
                    });
                }

                if (typeof payload.cart_count !== 'undefined') {
                    $('#headerCartCountBadge, #headerCartCountBadgeHome, #mobileCartCountBadge').text(payload.cart_count);
                    $('.ltn__utilize-menu-title').filter(function () {
                        return $(this).text().indexOf('Giỏ hàng của bạn') !== -1;
                    }).text('Giỏ hàng của bạn (' + payload.cart_count + ')');
                }

                if (payload.cart_items) {
                    updateMiniCart(payload.cart_items, payload.cart_subtotal);
                }

                if (payload.refresh) {
                    window.location.reload();
                    return;
                }

                if (payload.redirect) {
                    window.location.href = payload.redirect;
                }
            })
            .catch(error => {
                if (typeof toastr !== 'undefined') {
                    toastr.error(error.message || 'Có lỗi xảy ra.', 'Lỗi', {
                        closeButton: true,
                        progressBar: true,
                        timeOut: 3000
                    });
                }
            });
    });

    function updateMiniCart(items, subtotal) {
        const cartWrap = $('.ltn__utilize-menu-cart-wrap');
        if (!cartWrap.length) return;

        const html = items.map(function (item) {
            const options = [item.color ? '<span class="text-danger">Màu: ' + escapeHtml(item.color) + '</span>' : '', item.size ? '<span>Size: ' + escapeHtml(item.size) + '</span>' : ''].filter(Boolean).join(' | ');
            const lineTotal = Number(item.quantity * item.price).toLocaleString('vi-VN');

            return '<div class="d-flex align-items-center py-2 border-bottom">'
                + '<img src="' + escapeHtml(item.image) + '" alt="' + escapeHtml(item.name) + '" style="width: 50px; height: 50px; object-fit: contain; border-radius: 6px; border: 1px solid #e2e8f0; margin-right: 12px;">'
                + '<div class="flex-grow-1" style="line-height: 1.3;">'
                + '<a href="' + escapeHtml(item.url) + '" class="font-weight-bold text-dark d-block text-truncate" style="max-width: 170px; font-size: 13px;">' + escapeHtml(item.name) + '</a>'
                + '<div style="font-size: 11px; color: #64748b;">' + options + '</div>'
                + '<div class="font-weight-bold text-danger" style="font-size: 12.5px;">' + item.quantity + ' × ' + lineTotal + ' ₫</div>'
                + '</div></div>';
        }).join('');

        cartWrap.html(html);
        $('#miniCartSubtotal').text(Number(subtotal).toLocaleString('vi-VN') + ' ₫');
    }

    function escapeHtml(value) {
        return $('<div>').text(value || '').html();
    }

    //đăng ký
    $('#register-form').on('submit', function (event) {
        const form = $(this);
        const name = $.trim(form.find('input[name="name"]').val());
        const email = $.trim(form.find('input[name="email"]').val());
        const password = form.find('input[name="password"]').val();
        const confirmPassword = form.find('input[name="confirmpassword"]').val();
        const checkbox1 = form.find('input[name="checkbox1"]').is(':checked');
        const checkbox2 = form.find('input[name="checkbox2"]').is(':checked');
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errors = [];

        if (name.length < 3) {
            errors.push('Họ và tên phải có ít nhất 3 ký tự.');
        }

        if (!emailRegex.test(email)) {
            errors.push('Email không hợp lệ.');
        }

        if (password.length < 6) {
            errors.push('Mật khẩu phải có ít nhất 6 ký tự.');
        }

        if (password !== confirmPassword) {
            errors.push('Mật khẩu nhập lại không khớp.');
        }

        if (!checkbox1 || !checkbox2) {
            errors.push('Bạn cần đồng ý với cả hai chính sách của shop.');
        }

        if (errors.length === 0) {
            return true;
        }

        event.preventDefault();
        const errorMessage = errors.join('<br>');

        if (typeof toastr !== 'undefined') {
            toastr.error(errorMessage, 'Vui lòng kiểm tra lại thông tin', {
                closeButton: true,
                progressBar: true,
                escapeHtml: false,
                timeOut: 5000
            });
        } else {
            alert(errors.join('\n'));
        }
    });
    //login
    $('#login-form').on('submit', function (event) {
        const form = $(this);
        const email = $.trim(form.find('input[name="email"]').val());
        const password = form.find('input[name="password"]').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errors = [];

        if (!emailRegex.test(email)) {
            errors.push('Email không hợp lệ.');
        }

        if (password.length < 6) {
            errors.push('Mật khẩu phải có ít nhất 6 ký tự.');
        }

        if (errors.length === 0) {
            return true;
        }

        event.preventDefault();
        const errorMessage = errors.join('<br>');

        if (typeof toastr !== 'undefined') {
            toastr.error(errorMessage, 'Vui lòng kiểm tra lại thông tin', {
                closeButton: true,
                progressBar: true,
                escapeHtml: false,
                timeOut: 5000
            });
        } else {
            alert(errors.join('\n'));
        }
    });
    
    // Validate reset password form
    $("#reset-password-form").on("submit", function (e) {
        const form = $(this);
        const email = $.trim(form.find('input[name="email"]').val());
        const password = form.find('input[name="password"]').val();
        const confirmPassword = form.find('input[name="password_confirmation"]').val();
        const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;
        const errors = [];

        if (!emailRegex.test(email)) {
            errors.push("Email không hợp lệ.");
        }

        if (password.length < 6) {
            errors.push("Mật khẩu phải có ít nhất 6 ký tự.");
        }

        if (password !== confirmPassword) {
            errors.push("Mật khẩu nhập lại không khớp.");
        }

        if (errors.length > 0) {
            e.preventDefault();
            const errorMessage = errors.join('<br>');
            if (typeof toastr !== 'undefined') {
                toastr.error(errorMessage, 'Lỗi', {
                    closeButton: true,
                    progressBar: true,
                    escapeHtml: false,
                    timeOut: 5000
                });
            } else {
                alert(errors.join('\n'));
            }
        }
    });
});