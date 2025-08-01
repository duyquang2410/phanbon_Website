<script>
$(document).ready(function() {
    var table = $('#orderTable').DataTable({
        responsive: true,
        language: {
            "sProcessing": "Đang xử lý...",
            "sLengthMenu": "Hiển thị _MENU_ mục",
            "sZeroRecords": "Không tìm thấy dữ liệu",
            "sInfo": "Hiển thị _START_ đến _END_ trong _TOTAL_ mục",
            "sInfoEmpty": "Hiển thị 0 đến 0 trong 0 mục",
            "sInfoFiltered": "(được lọc từ _MAX_ mục)",
            "sInfoPostFix": "",
            "sSearch": "Tìm kiếm:",
            "sUrl": "",
            "oPaginate": {
                "sFirst": "Đầu",
                "sPrevious": "Trước",
                "sNext": "Tiếp",
                "sLast": "Cuối"
            }
        },
        order: [[0, 'desc']],
        columnDefs: [
            {
                targets: -1,
                orderable: false
            }
        ],
        dom: '<"row"<"col-sm-12 col-md-6"l><"col-sm-12 col-md-6"f>>' +
             '<"row"<"col-sm-12"tr>>' +
             '<"row"<"col-sm-12 col-md-5"i><"col-sm-12 col-md-7"p>>',
        lengthMenu: [
            [10, 25, 50, -1],
            ['10 mục', '25 mục', '50 mục', 'Tất cả']
        ],
        searching: true,
        search: {
            return: true
        }
    });

    // Xử lý tìm kiếm
    $('#searchInput').on('keyup', function() {
        table.search($(this).val()).draw();
    });

    // Highlight dòng được chọn
    $('.bill-row').click(function() {
        $('.bill-row').removeClass('bg-light');
        $(this).addClass('bg-light');
    });
});

// Hàm in hóa đơn
function printBillDetail() {
    var printContents = document.querySelector('.card-plain').innerHTML;
    var originalContents = document.body.innerHTML;
    document.body.innerHTML = printContents;
    window.print();
    document.body.innerHTML = originalContents;
    location.reload();
}
</script> 