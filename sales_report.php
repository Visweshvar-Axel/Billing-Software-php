<?php
include 'db_connect.php';

if (isset($_GET['start_date']) && isset($_GET['end_date'])) {
    $start_date = $_GET['start_date'];
    $end_date = $_GET['end_date'];
} else {
    $start_date = date('Y-m-01');
    $end_date = date('Y-m-t');
}
?>

<div class="container-fluid">
    <div class="col-lg-12" style="padding-top: 3%;margin-left:-100px;">
        <div class="card">

            <!-- <div class="card-header p-1 m-1">
                <label for="" class="mt-2" style="font-size: 30px; color: whitesmoke; text-align: center; width: 100%;">Date Range</label>
            </div> -->
            <div class="card_body">
                <div class="row justify-content-center pt-2">
                    <div class="col-sm-6">
                        <div class="input-group">
                            <div class="input-group-prepend">
                                <span class="input-group-text mx-3">From</span>
                            </div>
                            <input type="date" name="start_date" id="start_date" value="<?php echo $start_date; ?>" class="form-control">
                            <div class="input-group-prepend">
                                <span class="input-group-text mx-3">To</span>
                            </div>
                            <input type="date" name="end_date" id="end_date" value="<?php echo $end_date; ?>" class="form-control">
                        </div>
                    </div>
                </div>
                <hr>
                <div class="col-md-12">
                    <table class="table table-bordered table-striped" id='report-list'>
                        <thead>
                            <tr>
                                <th class="text-center">S.no.</th>
                                <th class="text-center">Date</th>
                                <th class="text-center">Invoice</th>
                                <th class="text-center">Order Number</th>
                                <th class="text-center">Payment Method</th>
                                <th class="text-center">Amount</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            $i = 1;
                            $total = 0;
                            $sales = $conn->query("SELECT * FROM orders WHERE amount_tendered > 0 AND date_created BETWEEN '$start_date' AND '$end_date' ORDER BY UNIX_TIMESTAMP(date_created) ASC");
                            if ($sales->num_rows > 0) :
                                while ($row = $sales->fetch_array()) :
                                    $total += $row['after_discount'];
                            ?>
                                    <tr>
                                        <td class="text-center"><?php echo $i++ ?></td>
                                        <td class="text-center">
                                            <p><b><?php echo date("M d, Y", strtotime($row['date_created'])) ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <p><b><?php echo $row['amount_tendered'] > 0 ? $row['ref_no'] : 'N/A' ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <p><b><?php echo $row['order_number'] ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <?php
                                            $payment_method_text = '';
                                            if ($row['payment_method'] == 1) {
                                                $payment_method_text = 'Cash';
                                            } elseif ($row['payment_method'] == 2) {
                                                $payment_method_text = 'Online';
                                            }
                                            ?>
                                            <p><b><?php echo $payment_method_text; ?></b></p>
                                        </td>
                                        <td class="text-center">
                                            <p class="text-right"> <b><?php echo number_format($row['after_discount'], 2) ?></b></p>
                                        </td>
                                    </tr>
                                <?php
                                endwhile;
                            else :
                                ?>
                                <tr>
                                    <th class="text-center" colspan="6">No Data.</th>
                                </tr>
                            <?php
                            endif;
                            ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="5" id="tot" class="text-right">Total</th>
                                <th class="text-right text-success" id="tot-amt"><?php echo number_format($total, 2) ?></th>
                            </tr>
                        </tfoot>
                    </table>
                    <hr>
                    <div class="col-md-12 mb-4">
                        <center>
                            <button class="btn btn-success btn-sm col-sm-3 m-1" type="button" id="print"><i class="fa fa-print"></i> Print</button>
                            <button class="btn btn-primary btn-sm col-sm-3 m-2" type="button" id="export_excel"><i class="fa fa-file-excel"></i> Export to Excel</button>
                        </center>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<style>
    .card {
        background-color: #1F1D2B;
    }

    thead tr th {
        background-color: #FED108;
        color: black;
    }

    tbody td,
    tfoot {
        background-color: #252836;
    }

    th {
        color: white;
    }

    td {
        color: white;
    }

    table#report-list {
        width: 100%;
        border-collapse: collapse
    }

    table#report-list td,
    table#report-list th {
        border: 1px solid
    }

    p {
        margin: unset;
        color: white;

    }

    .text-center {
        text-align: center
    }

    .text-right {
        text-align: right
    }
</style>
<noscript>
    <style>
        table#report-list {
            width: 100%;
            border-collapse: collapse
        }

        table#report-list td,
        table#report-list th {
            border: 1px solid
        }

        p {
            margin: unset;
        }

        .text-center {
            text-align: center
        }

        .text-right {
            text-align: right
        }
    </style>
</noscript>

<!-- <script src="https://cdnjs.cloudflare.com/ajax/libs/xlsx/0.17.2/xlsx.full.min.js"></script> -->
<script src="assets\xlsx\xlsx.full.min.js"></script>

<script>
    $('#start_date, #end_date').change(function() {
        var start_date = $('#start_date').val();
        var end_date = $('#end_date').val();
        location.replace('index.php?page=sales_report&start_date=' + start_date + '&end_date=' + end_date);
    })

    $('#print').click(function() {
        var _c = $('#report-list').clone();
        var ns = $('noscript').clone();
        ns.append(_c)
        var nw = window.open('', '_blank', 'width=900,height=600')
        nw.document.write('<p class="text-center"><b>Order Report as of <?php echo date("F, Y", strtotime($start_date)) ?></b></p>')
        nw.document.write(ns.html())
        nw.document.close()
        nw.print()
        setTimeout(() => {
            nw.close()
        }, 500);
    })

    $('#export_excel').click(function() {
        var data = [];
        var headers = [];

        $('#report-list th:not(tfoot th)').each(function() {
            headers.push($(this).text().trim());
        });

        data.push(headers);

        $('#report-list tbody tr').each(function() {
            var row = [];
            $(this).find('td').each(function() {
                row.push($(this).text().trim());
            });
            data.push(row);
        });

        // data.push([]);

        var totalRow = [];
        for (var i = 0; i < headers.length; i++) {
            if (i === 4) {
                totalRow.push('Total');
            } else if (i === 5) {
                totalRow.push($('#tot-amt').text().trim());
            } else {
                totalRow.push('');
            }
        }
        data.push(totalRow);

        var wb = XLSX.utils.book_new();
        var ws = XLSX.utils.aoa_to_sheet(data);

        XLSX.utils.book_append_sheet(wb, ws, "Sales_Report");

        XLSX.writeFile(wb, "Sales_Report.xlsx");
    });
</script>