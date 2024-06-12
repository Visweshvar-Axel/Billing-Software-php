<?php include '../db_connect.php' ?>
<style>
    main {
        background-color: #252836;
    }

    .card-header {
        color: black;
        background-color: #FADC1B;
    }

    thead tr th {
        background-color: #E5E8ED;
        color: black;
    }

    tbody td {
        background-color: #252836;
        color: whitesmoke;
    }

    #cat-list {
        background-color: #252836;
    }

    .card-footer {
        background-color: #252836;
    }

    span.float-right.summary_icon {
        font-size: 3rem;
        position: absolute;
        right: 1rem;
        top: 0;
    }

    .bg-gradient-primary {
        background: #26b26a;
        background: linear-gradient(149deg, rgba(119, 172, 233, 1) 5%, rgba(83, 163, 255, 1) 10%, rgba(46, 51, 227, 1) 41%, rgba(40, 51, 218, 1) 61%, rgba(75, 158, 255, 1) 93%, rgba(124, 172, 227, 1) 98%);
    }

    .btn-primary-gradient {
        background: linear-gradient(to right, #1e85ff 0%, #00a5fa 80%, #00e2fa 100%);
    }

    .btn-danger-gradient {
        background: linear-gradient(to right, #f25858 7%, #ff7840 50%, #ff5140 105%);
    }

    main .card {
        height: calc(100%);
        background-color: #1F1D2B;
        box-shadow: 0 5px 10px rgba(203, 219, 175, 0.1);
    }

    .d-flex {
        background-color: #1F1D2B;
        color: whitesmoke;
    }

    main .card-body {
        height: calc(100%);
        overflow: auto;
        padding: 5px;
        position: relative;
    }

    main .container-fluid,
    main .container-fluid>.row,
    main .container-fluid>.row>div {
        height: calc(100%);
    }

    #o-list {
        height: calc(87%);
        overflow: auto;
    }

    #calc {
        position: fixed;
        left: 57px;
        bottom: -20px;
        height: calc(10%);
        width: calc(29%);
    }

    .prod-item {
        min-height: 12vh;
        cursor: pointer;
    }

    .prod-item:hover {
        opacity: .8;
    }

    .prod-item .card-body {
        display: flex;
        justify-content: center;
        align-items: center;

    }

    input[name="qty[]"] {
        width: 30px;
        text-align: center
    }

    input::-webkit-outer-spin-button,
    input::-webkit-inner-spin-button {
        -webkit-appearance: none;
        margin: 0;
    }

    #cat-list {
        height: calc(100%)
    }

    .cat-item {
        cursor: pointer;
    }

    .cat-item:hover {
        opacity: .8;
    }

    .menu-card-bg {
        background-color: 3a3651;
    }

    .menu-card {
        background-color: #1f1d2b;
    }

    .menu-card-img {
        position: relative;
        margin-top: -20px;
        height: auto;
        width: auto;
        display: flex;
        justify-content: center;
        align-items: center;

    }

    .menu-card:hover {
        .menu-card-img {

            position: relative;
            margin-top: -25px;
            /* height: 110%;
        width: 110%; */
        }
    }

    .menu-card:hover {
        box-shadow: -65px -52px 78px -77px rgba(255, 255, 255, 0.59) inset;
    }

    .menu-card-img-tag {
        border-radius: 50%;
        border: 3px solid #fff;
        object-fit: cover;
    }

    .menu-card-img-tag:hover {
        border-radius: 50%;
        border: 3px solid #fff;
        object-fit: cover;
        box-shadow: 1px 1px 36px -5px rgba(255, 255, 255, 1);
    }
</style>
<?php
if (isset($_GET['id'])) :
    $order = $conn->query("SELECT * FROM orders where id = {$_GET['id']}");
    foreach ($order->fetch_array() as $k => $v) {
        $$k = $v;
    }
    $items = $conn->query("SELECT o.*,p.name FROM order_items o inner join products p on p.id = o.product_id where o.order_id = $id ");
endif;


function generateOrderNumber()
{
    $lastOrderDate = isset($_SESSION['last_order_date']) ? $_SESSION['last_order_date'] : null;
    $currentDate = date('Y-m-d');

    if ($lastOrderDate !== $currentDate) {
        $_SESSION['last_order_date'] = $currentDate;
        $_SESSION['order_number_counter'] = 1;
    }

    return $_SESSION['order_number_counter']++;
}

$order_number = isset($_GET['id']) ? $order_number : generateOrderNumber();

?>
<script>
    var orderId = <?php echo json_encode($order_number); ?>;
</script>

<div class="container-fluid o-field">
    <div class="row mt-3 ml-3 mr-3">
        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <b>Order List</b>
                    <!-- <span class="float:right"><a class="btn btn-primary btn-sm col-sm-3 float-right" href="../index.php" id="">
                            <i class="fa fa-home"></i> Home
                        </a></span> -->
                </div>
                <div class="card-body" style="margin-bottom: 45px;">
                    <form action="" id="manage-order">
                        <input type="hidden" name="id" value="<?php echo isset($_GET['id']) ? $_GET['id'] : '' ?>">
                        <div id='o-list'>
                            <div class="d-flex w-100 mb-1">
                                <label for=""><b>Order No.</b></label>
                                <input type="number" class="form-control-sm" name="order_number" value="<?php echo $order_number ?>" required readonly>
                            </div>
                            <table class="table table-bordered bg-light">
                                <colgroup>
                                    <col width="60%">
                                    <col width="20%">
                                    <col width="20%">
                                    <col width="5%">

                                </colgroup>
                                <thead>
                                    <tr>
                                        <th style="text-align: center;">Order</th>
                                        <th style="text-align: center;">QTY</th>
                                        <th style="text-align: center;">Amount</th>
                                        <th></th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php
                                    if (isset($items)) :
                                        while ($row = $items->fetch_assoc()) :
                                    ?>
                                            <tr>
                                                <td>
                                                    <input type="hidden" name="item_id[]" id="" value="<?php echo $row['id'] ?>">
                                                    <input type="hidden" name="product_id[]" id="" value="<?php echo $row['product_id'] ?>"><?php echo ucwords($row['name']) ?>
                                                    <small class="psmall"> (<?php echo number_format($row['price'], 2) ?>)</small>
                                                </td>
                                                <td>
                                                    <div class="d-flex">
                                                        <span class="btn btn-sm btn-secondary btn-minus"><b><i class="fa fa-minus"></i></b></span>
                                                        <input type="number" name="qty[]" id="" value="<?php echo $row['qty'] ?>">
                                                        <span class="btn btn-sm btn-secondary btn-plus"><b><i class="fa fa-plus"></i></b></span>
                                                    </div>
                                                </td>
                                                <td class="text-right">
                                                    <input type="hidden" name="price[]" id="" value="<?php echo $row['price'] ?>">
                                                    <input type="hidden" name="amount[]" id="" value="<?php echo $row['amount'] ?>">
                                                    <span class="amount"><?php echo number_format($row['amount'], 2) ?></span>
                                                </td>
                                                <td>
                                                    <span class="btn btn-sm btn-danger btn-rem"><b><i class="fa fa-times text-white"></i></b></span>
                                                </td>
                                            </tr>
                                            <script>
                                                $(document).ready(function() {
                                                    qty_func()
                                                    calc()
                                                    cat_func();
                                                })
                                            </script>
                                        <?php endwhile; ?>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <div class="d-block" id="calc">
                            <table class="" width="100%">
                                <tbody>
                                    <tr>
                                        <td><b>
                                                <h4>Total</h4>
                                            </b></td>
                                        <td class="text-right">
                                            <input type="hidden" name="total_amount" value="0">
                                            <input type="hidden" name="total_tendered" value="0">
                                            <span class="">
                                                <h4 style="color: #09F700;"><img src="..\assets\images\rupee.png" alt="" height="20"><b id="total_amount">0.00</b></h4>
                                            </span>
                                        </td>
                                    </tr>
                                </tbody>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
        <div class="col-lg-8  p-field">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col">
                            <b>Products</b>
                        </div>
                        <?php
                        $category_id = $conn->query("SELECT id FROM categories WHERE name = '~COMBOS'")->fetch_assoc()['id'];
                        // echo $category_id;
                        ?>
                        <div class="card mx-3 mb-2 cat-item" style="height:auto !important;" data-id='<?php echo $category_id; ?>'>
                            <div class="card-body" style="background-color:#43a9d5; border-radius: 8px;">
                                <span><b>
                                        COMBOS
                                    </b></span>
                            </div>
                        </div>
                        <?php
                        $category_id = $conn->query("SELECT id FROM categories WHERE name = '~ADD-ONS'")->fetch_assoc()['id'];
                        // echo $category_id;
                        ?>
                        <div class="card mx-3 mb-2 cat-item" style="height:auto !important;" data-id='<?php echo $category_id; ?>'>
                            <div class="card-body" style="background-color:#bc9a7e; border-radius: 8px;">
                                <span><b>
                                        ADD-ONS
                                    </b></span>
                            </div>
                        </div>
                        <div class="col-auto">
                            <input type="text" id="searchProduct" class="form-control-sm" placeholder="Search products...">
                        </div>
                    </div>
                </div>
                <div class="card-body d-flex " id='prod-list' style="background-color:#343844;">
                    <div class="col-md-3">
                        <div class="mt-2 pb-3" id="cat-list" style="position: fixed; height: 75%; width: 14.5%; margin-left:-8px;">
                            <div class="p-1 mb-3 pl-5" style="background-color:#E5E8ED; border-radius: 5px;">
                                <b class="pl-3 text-dark">Category</b>
                            </div>
                            <div style="height: 94%; overflow-y: auto; max-height: 100%;">
                                <div class="card bg-light mx-3 mb-2 cat-item" style="height:auto !important;" data-id='all'>
                                    <div class="card-body" style="background-color:#253949; border-radius: 8px;">
                                        <span><b>
                                                All
                                            </b></span>
                                    </div>
                                </div>
                                <?php
                                $qry = $conn->query("SELECT * FROM categories order by name asc");
                                while ($row = $qry->fetch_assoc()) :
                                ?>
                                    <div class="card bg-light mx-3 mb-2 cat-item" style="height:auto !important;" data-id='<?php echo $row['id'] ?>'>
                                        <div class="card-body" style="background-color:#32373c; border-radius: 8px;">
                                            <span><b class="text-white">
                                                    <?php echo ucwords($row['name']) ?>
                                                </b></span>
                                        </div>
                                    </div>
                                <?php endwhile; ?>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-9">
                        <hr>
                        <div class="row">
                            <?php
                            $prod = $conn->query("SELECT * FROM products where status = 1 order by name asc");
                            while ($row = $prod->fetch_assoc()) :
                            ?>
                                <div class="col-md-3 mt-3 mb-4">
                                    <div class="card prod-item menu-card" data-json='<?php echo json_encode($row) ?>' data-category-id="<?php echo $row['category_id'] ?>">
                                        <span class="menu-card-img">
                                            <img src="images/<?php echo isset($row['image']) ? $row['image'] : 'item.jpg'; ?>" class="menu-card-img-tag" alt="" height="100" width="100" style="">
                                        </span>
                                        <div class="card-body menu-card-body" style="background: linear-gradient(to bottom, rgba(0,0,0,0) 0%,rgba(0,2,2,0) 1%,rgba(35,170,242,0.35) 100%);">
                                            <span calss="card-title">
                                                <span style="display: none;">
                                                    <?php echo $row['id']; ?><br>
                                                </span>
                                                <span class="text-center text-white">
                                                    <?php echo $row['name']; ?><br>
                                                </span>
                                            </span>
                                        </div>
                                        <span class="text-center text-dark" style="border-radius: 5px; background: #e5e8ed">
                                            <?php echo $row['price']; ?>
                                        </span>
                                    </div>
                                </div>
                            <?php endwhile; ?>
                        </div>
                    </div>
                </div>
                <div class="card-footer border-light">
                    <div class="row justify-content-center">
                        <div class="btn btn btn-sm col-sm-3 btn-success mr-2" type="button" id="pay"><img src="..\assets\images\pay.png" alt="" width="20px" height="20px" style="margin-right: 5px; padding: 0px;"> Pay</div>
                        <div class="btn btn btn-sm col-sm-3 btn-danger" type="button" id="save_order"><img src="..\assets\images\pay later.png" alt="" width="20px" height="20px" style="margin-right: 5px; padding: 0px;"> Pay later</div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<div class="modal fade" id="pay_modal" role='dialog'>
    <div class="modal-dialog modal-dialog-centered modal-md" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title"><b>Pay</b></h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <div class="container-fluid">
                    <div class="form-group">
                        <label for="">Grand Total</label>
                        <input type="number" class="form-control text-right" id="apayable" readonly="" value="">
                    </div>

                    <!-- Add Discount Field -->
                    <div class="form-row">
                        <div class="form-group col-md-6">
                            <label for="discount">Discount</label>
                            <input type="number" class="form-control text-right" id="discount" value="0" autocomplete="off">
                        </div>

                        <!-- After Discount Field -->
                        <div class="form-group col-md-6">
                            <label for="after_discount">After Discount</label>
                            <input type="number" class="form-control text-right" id="after_discount" readonly="">
                        </div>
                    </div>

                    <div class="form-group">
                        <label for="tendered"> Amount Received</label>
                        <input type="text" class="form-control text-right" id="tendered" value="" autocomplete="off">
                    </div>
                    <div class="form-group">
                        <label for="change">Balance</label>
                        <input type="text" class="form-control text-right" id="change" value="0.00" readonly="">
                    </div>
                    <div class="form-group">
                        <label style="display: inline-block; width: 150px;">Payment Method</label>
                        <div style="display: inline-block;">
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method[]" value="1" id="cashRadio" checked>
                                <label class="form-check-label" for="cashRadio">Cash</label>
                            </div>
                            <div class="form-check form-check-inline">
                                <input class="form-check-input" type="radio" name="payment_method[]" value="2" id="onlineRadio">
                                <label class="form-check-label" for="onlineRadio">Online</label>
                            </div>
                        </div>
                    </div>



                </div>
            </div>




            <div class="modal-footer">
                <button type="submit" class="btn btn-primary btn-sm" form="manage-order">Pay</button>
                <button type="button" class="btn btn-secondary btn-sm" data-dismiss="modal">Cancel</button>
            </div>
        </div>
    </div>
</div>
<script>
    $(document).ready(function() {
        function filterProducts(keyword) {
            $('.prod-item').each(function() {
                var productName = $(this).data('json').name.toLowerCase();
                var productId = $(this).data('json').id.toString();
                keyword = keyword.toLowerCase();

                if (productName.includes(keyword) || productId.includes(keyword)) {
                    $(this).parent().show();
                } else {
                    $(this).parent().hide();
                }
            });
        }

        $('#searchProduct').on('input', function() {
            var searchKeyword = $(this).val();
            filterProducts(searchKeyword);
        });
    });
</script>
<script>
    var total;
    cat_func();

    $('#prod-list .prod-item').click(function() {
        var data = $(this).attr('data-json');
        data = JSON.parse(data);
        if ($('#o-list tr[data-id="' + data.id + '"]').length > 0) {
            var tr = $('#o-list tr[data-id="' + data.id + '"]');
            var qty = tr.find('[name="qty[]"]').val();
            qty = parseInt(qty) + 1;
            qty = tr.find('[name="qty[]"]').val(qty).trigger('change');
            calc();
            return false;
        }
        var tr = $('<tr class="o-item"></tr>');
        tr.attr('data-id', data.id);
        tr.append('<td><input type="hidden" name="item_id[]" id="" value=""><input type="hidden" name="product_id[]" id="" value="' + data.id + '">' + data.name + '</td>'); // Changed the position of quantity column
        tr.append('<td><div class="d-flex"><span class="btn btn-sm btn-secondary btn-minus" id="' + data.id + '" onclick="btn_minus(' + data.id + ')"><b><i class="fa fa-minus"></i></b></span><input type="number" name="qty[]" id="" value="1"><span class="btn btn-sm btn-secondary btn-plus" id="' + data.id + '" onclick="btn_plus(' + data.id + ')"><b><i class="fa fa-plus"></i></b></span></div></td>'); // Quantity column added after product name
        tr.append('<td class="text-right"><input type="hidden" name="price[]" id="" value="' + data.price + '"><input type="hidden" name="amount[]" id="" value="' + data.price + '"><span class="amount">' + (parseFloat(data.price).toLocaleString("en-US", {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        })) + '</span></td>');
        tr.append('<td><span class="btn btn-sm btn-danger btn-rem" id="rem' + data.id + '" onclick="btn_rem(' + data.id + ')"><b><i class="fa fa-times text-white"></i></b></span></td>');
        $('#o-list tbody').append(tr);
        qty_func();
        calc();
        cat_func();
    });

    function qty_func() {
        $('[name="qty[]"]').change(calc);

        function btn_minus(data_id) {
            // alert(data_id, " ", typeof(data_id));
            var qty = $(`#${data_id}`).siblings('input').val();
            qty = qty > 1 ? parseInt(qty) - 1 : 1;
            $(`#${data_id}`).siblings('input').val(qty).trigger('change');
            calc();
        }

        function btn_plus(data_id) {
            // alert(data_id, " ", typeof(data_id));
            var qty = $(`#${data_id}`).siblings('input').val();
            qty = parseInt(qty) + 1;
            $(`#${data_id}`).siblings('input').val(qty).trigger('change');
            calc();
        }

        function btn_rem(data_id) {
            // alert(data_id, ": rem", typeof(data_id));
            $(`#rem${data_id}`).closest('tr').remove();
            calc();
        }
    }
    $('[name="qty[]"]').change(calc);

    function btn_minus(data_id) {
        // alert(data_id, " ", typeof(data_id));
        var qty = $(`#${data_id}`).siblings('input').val();
        qty = qty > 1 ? parseInt(qty) - 1 : 1;
        $(`#${data_id}`).siblings('input').val(qty).trigger('change');
        calc();
    }

    function btn_plus(data_id) {
        // alert(data_id, " ", typeof(data_id));
        var qty = $(`#${data_id}`).siblings('input').val();
        qty = parseInt(qty) + 1;
        $(`#${data_id}`).siblings('input').val(qty).trigger('change');
        calc();
    }

    function btn_rem(data_id) {
        // alert(data_id, ": rem", typeof(data_id));
        $(`#rem${data_id}`).closest('tr').remove();
        calc();
    }

    function calc() {
        $('[name="qty[]"]').each(function() {
            $(this).change(function() {
                var tr = $(this).closest('tr');
                var qty = $(this).val();
                var price = tr.find('[name="price[]"]').val();
                var amount = parseFloat(qty) * parseFloat(price);
                tr.find('[name="amount[]"]').val(amount);
                tr.find('.amount').text(parseFloat(amount).toLocaleString("en-US", {
                    style: 'decimal',
                    minimumFractionDigits: 2,
                    maximumFractionDigits: 2
                }));
            });
        });
        var total = 0;
        $('[name="amount[]"]').each(function() {
            total = parseFloat(total) + parseFloat($(this).val());
        });
        $('[name="total_amount"]').val(total);
        $('#total_amount').text(parseFloat(total).toLocaleString("en-US", {
            style: 'decimal',
            minimumFractionDigits: 2,
            maximumFractionDigits: 2
        }));
    }

    function cat_func() {
        $('.cat-item').click(function() {
            var id = $(this).attr('data-id');
            if (id == 'all') {
                $('.prod-item').parent().toggle(true);
            } else {
                $('.prod-item').each(function() {
                    if ($(this).attr('data-category-id') == id) {
                        $(this).parent().toggle(true);
                    } else {
                        $(this).parent().toggle(false);
                    }
                });
            }
        });
    }

    $("#pay").click(function() {
        start_load();
        var amount = $('[name="total_amount"]').val();
        if ($('#o-list tbody tr').length <= 0) {
            alert_toast("Please add at least 1 product first.", 'danger');
            end_load();
            return false;
        }
        $('#apayable').val(amount); // Display the amount without formatting
        $('#discount').val('');
        $('#after_discount').val(amount); // Set the default value of after_discount
        $('#pay_modal').modal('show');
        setTimeout(function() {
            $('#tendered').val('').trigger('change');
            $('#tendered').focus();
            end_load();
        }, 500);

        $('#discount').on('input', function() {
            var discount = $(this).val();
            applyDiscount(discount);
        });

        function applyDiscount(discount) {
            $.ajax({
                url: '../ajax.php?action=calculate_discount',
                method: 'POST',
                data: {
                    discount: discount,
                    total_amount: $('[name="total_amount"]').val()
                },
                success: function(resp) {
                    var discountedAmount = parseFloat(resp);
                    $('#after_discount').val(discountedAmount);
                    amount = discountedAmount;
                    updateChange();
                }
            });
        }

        $('#tendered').on('input', function(e) {
            if (e.which == 13) {
                $('#manage-order').submit();
                return false;
            }

            var tend = parseFloat($(this).val().replace(/,/g, '') || 0);
            $(this).val(tend); // Display the tendered amount without formatting
            $('[name="total_tendered"]').val(tend);

            updateChange();
        });

        function updateChange() {
            var tend = parseFloat($('#tendered').val().replace(/,/g, '') || 0);
            var change = tend - amount;

            $('#change').val(change.toLocaleString("en-US", {
                style: 'decimal',
                minimumFractionDigits: 2,
                maximumFractionDigits: 2
            }));
        }

        $('#tendered').on('input', function() {
            var val = $(this).val();
            val = val.replace(/[^0-9 \,]/, '');
            $(this).val(val);
        });

    });

    function alert_toast(message, type = 'success') {
        var bgColor = type === 'success' ? 'green' : 'red';
        var toast = $('<div class="toast"></div>').text(message).css({
            'background-color': bgColor,
            'color': 'white',
            'padding': '10px',
            'position': 'fixed',
            'top': '10px',
            'right': '10px',
            'z-index': '10000',
            'border-radius': '5px',
            'box-shadow': '0px 0px 10px rgba(0,0,0,0.1)'
        });
        $('body').append(toast);
        setTimeout(function() {
            toast.fadeOut(function() {
                $(this).remove();
            });
        }, 3000); // The toast message will disappear after 3 seconds
    }

    $('#save_order').click(function() {
        // Set the tendered amount to 0
        $('#tendered').val(total);
        // Trigger the change event on the tendered amount input field
        $('#tendered').trigger('change');
        // Submit the form
        $('#manage-order').submit();

        // Show toast message
        alert_toast("Data saved successfully", 'success');
    });


    $('#manage-order').submit(function(e) {
        e.preventDefault();

        var selectedMethods = $('input[name="payment_method[]"]:checked').map(function() {
            return this.value;
        }).get();

        console.log('Selected Payment Methods:', selectedMethods);

        var formData = $(this).serialize() + '&payment_method=' + selectedMethods.join(',');
        formData += '&discount=' + $('#discount').val();
        formData += '&after_discount=' + $('#after_discount').val();
        console.log('formData:', formData);
        start_load();

        $.ajax({
            url: '../ajax.php?action=save_order',
            method: 'POST',
            data: formData,
            success: function(resp) {
                // resp = orderId; // Assuming the server returns the actual order id in the response
                if (resp > 0) { // Check if the response is a valid order id
                    if ($('[name="total_tendered"]').val() > 0) {
                        alert_toast("Data successfully saved.", 'success');
                        setTimeout(function() {
                            var nw = window.open('../receipt.php?id=' + resp, "_blank", "width=900,height=600");
                            if (nw) {
                                setTimeout(function() {
                                    nw.print();
                                    setTimeout(function() {
                                        nw.close();
                                        location.reload();
                                    }, 500);
                                }, 500);
                            } else {
                                alert_toast("Pop-up blocked! Please allow pop-ups for this website.", 'error');
                            }
                        }, 500);
                    } else {
                        alert_toast("Data successfully saved.", 'success');
                        setTimeout(function() {
                            location.reload();
                        }, 500);
                    }
                } else {
                    alert_toast("Failed to save data." + resp, 'error');
                }
            },
            error: function(xhr, status, error) {
                alert_toast("An error occurred: " + xhr.responseText, 'error');
            }
        });
    });


    // $('#manage-order').submit(function(e) {
    //     e.preventDefault();

    //     var formData = $(this).serialize();

    //     $.ajax({
    //         url: '../ajax.php?action=save_order',
    //         method: 'POST',
    //         data: formData,
    //         success: function(resp) {
    //             resp = 1;
    //             if (resp > 0) {
    //                 if ($('[name="total_tendered"]').val() > 0) {
    //                     alert_toast("Data successfully saved.", 'success');
    //                     setTimeout(function() {
    //                         var nw = window.open('../receipt.php?id=' + resp, "_blank", "width=900,height=600");
    //                         if (nw) {
    //                             setTimeout(function() {
    //                                 nw.print();
    //                                 setTimeout(function() {
    //                                     nw.close();
    //                                     location.reload();
    //                                 }, 500);
    //                             }, 500);
    //                         } else {
    //                             alert_toast("Pop-up blocked! Please allow pop-ups for this website.", 'error');
    //                         }
    //                     }, 500);
    //                 } else {
    //                     alert_toast("Data successfully saved.", 'success');
    //                     setTimeout(function() {
    //                         location.reload();
    //                     }, 500);
    //                 }
    //             } else {
    //                 alert_toast("Failed to save data.", 'error');
    //             }
    //         },
    //         error: function(xhr, status, error) {
    //             alert_toast("An error occurred: " + xhr.responseText, 'error');
    //         }
    //     });
    // });

    function alert_toast(message, type) {
        // Implement the alert_toast function to show a toast message
        // This is a placeholder implementation
        console.log(type.toUpperCase() + ": " + message);
    }
</script>