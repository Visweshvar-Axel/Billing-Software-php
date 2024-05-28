<!DOCTYPE html>
<html>

<head>
    <title>Product Frequencies</title>
    <style>
        .container-fluid {
            padding-top: 3%;
            margin-left: -100px;
        }

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
            color: white;
        }

        th {
            color: white;
        }

        td {
            color: white;
        }

        table#product-frequencies {
            width: 50%;
            border-collapse: collapse;
            margin: auto;
        }

        table#product-frequencies td,
        table#product-frequencies th {
            border: 1px solid #dddddd;
            text-align: left;
            padding: 8px;
        }

        th {
            background-color: #f2f2f2;
        }
    </style>
</head>

<body>
    <div class="container-fluid">
        <div class="col-lg-12">
            <div class="card">
                <div class="card_body">
                    <table class="table table-bordered table-striped" id='product-frequencies'>
                        <thead>
                            <tr>
                                <th class="text-center">Serial Number</th>
                                <th class="text-center">Product Name</th>
                                <th class="text-center">Quantity</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php
                            include 'db_connect.php';

                            // Check connection
                            if ($conn->connect_error) {
                                die("Connection failed: " . $conn->connect_error);
                            }

                            $start_date = '2024-03-01';
                            $end_date = '2024-03-31';

                            // SQL query to fetch product frequencies for the current month
                            $sql = "SELECT p.name AS product_name, SUM(o.qty) AS frequency
                            FROM order_items o
                            RIGHT JOIN orders ord ON ord.id = o.order_id
                            INNER JOIN products p ON p.id = o.product_id
                            WHERE ord.date_created BETWEEN '$start_date' AND '$end_date'
                            GROUP BY o.product_id
                            ORDER BY frequency DESC";


                            // Execute the query
                            $result = $conn->query($sql);

                            // Check if the query was successful
                            if ($result) {
                                $serialNumber = 1;
                                // Fetch data and display in table
                                while ($row = $result->fetch_assoc()) {
                                    echo "<tr>";
                                    echo "<td class='text-center'>" . $serialNumber++ . "</td>";
                                    echo "<td class='text-center'>" . $row['product_name'] . "</td>";
                                    echo "<td class='text-center'>" . $row['frequency'] . "</td>";
                                    echo "</tr>";
                                }
                                // Free result set
                                $result->free();
                            } else {
                                // Handle query error
                                echo "<tr><td colspan='3' class='text-center'>Error: " . $conn->error . "</td></tr>";
                            }

                            // Close connection
                            $conn->close();
                            ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</body>

</html>