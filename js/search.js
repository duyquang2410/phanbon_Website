$(document).ready(function() {
    // Chức năng tự động gợi ý tìm kiếm
    $("#search-box").autocomplete({
        source: function(request, response) {
            $.getJSON("search_suggestions.php", {
                term: request.term
            }, function(data) {
                // Kiểm tra nếu không có kết quả
                if (data.length === 0) {
                    var result = [{
                        label: 'Không tìm thấy sản phẩm nào',
                        value: '',
                        class: 'no-results'
                    }];
                    response(result);
                } else {
                    response(data);
                }
            });
        },
        minLength: 1, // Số ký tự tối thiểu để bắt đầu tìm kiếm
        delay: 300, // Độ trễ tìm kiếm (ms)

        // Tùy chỉnh hiển thị mỗi mục trong kết quả
        open: function() {
            $(this).autocomplete("widget").css({
                "width": ($(this).outerWidth() + 50) + "px"
            });
        }
    });

    // Ghi đè phương thức _renderItem một lần duy nhất
    var autocomplete = $("#search-box").data("ui-autocomplete");
    if (autocomplete) {
        autocomplete._renderItem = function(ul, item) {
            // Trường hợp không có kết quả
            if (item.class === 'no-results') {
                return $("<li class='ui-menu-item no-results'></li>")
                    .append(item.label)
                    .appendTo(ul);
            }

            // Hiển thị kết quả bình thường
            var html = '<a href="' + item.link + '" class="search-item">' +
                '<div class="search-item-image">' +
                '<img src="' + item.image + '" alt="' + item.label + '">' +
                '</div>' +
                '<div class="search-item-details">' +
                '<div class="search-item-name">' + item.label + '</div>' +
                '<div class="search-item-price">' + item.price + '</div>' +
                '</div>' +
                '</a>';

            return $("<li></li>")
                .data("item.autocomplete", item)
                .append(html)
                .appendTo(ul);
        };
    }

    // Xử lý khi chọn một mục từ gợi ý
    $("#search-box").on("autocompleteselect", function(event, ui) {
        if (ui.item.link && ui.item.class !== 'no-results') {
            window.location.href = ui.item.link;
            return false;
        }
    });

    // Form tìm kiếm
    $("#search-form").on("submit", function(e) {
        e.preventDefault();
        var searchTerm = $("#search-box").val().trim();
        if (searchTerm !== '') {
            window.location.href = "shop.php?search=" + encodeURIComponent(searchTerm);
        }
    });
});

$(function() {
    var $searchBox = $("#search-box");
    if ($searchBox.length && $.ui && $.ui.autocomplete) {
        $searchBox.autocomplete({
            // ... existing options ...
        });
        $searchBox.autocomplete("instance")._renderItem = function(ul, item) {
            // ... existing code ...
        };
    }
});