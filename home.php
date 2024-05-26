<?php
include 'db_connect.php';
$six_months_ago = date('Y-m-d', strtotime('-6 months'));
$sales_data = $conn->query("SELECT DATE_FORMAT(date_created, '%Y-%m') AS month, SUM(after_discount) AS total_sales FROM orders WHERE amount_tendered > 0 AND date_created >= '$six_months_ago' GROUP BY month ORDER BY month ASC");

$sql = "SELECT p.name AS product_name, SUM(o.qty) AS frequency
        FROM order_items o
        INNER JOIN products p ON p.id = o.product_id
        GROUP BY o.product_id
        ORDER BY frequency DESC";

// Execute the query
$result = $conn->query($sql);

// Check if the query was successful
if ($result) {
    // Initialize arrays to store product names and frequencies
    $productNames = [];
    $frequencies = [];

    // Fetch data and store it in arrays
    while ($row = $result->fetch_assoc()) {
        $productNames[] = $row['product_name'];
        $frequencies[] = $row['frequency'];
    }

    // Free result set
    $result->free();
} else {
    // Handle query error
    echo "Error: " . $conn->error;
}

?>


<style>
    span.float-right.summary_icon {
        font-size: 3rem;
        position: absolute;
        right: 1rem;
        top: 0;
    }

    .imgs {
        margin: .5em;
        max-width: calc(100%);
        max-height: calc(100%);
    }

    .imgs img {
        max-width: calc(100%);
        max-height: calc(100%);
        cursor: pointer;
    }

    #imagesCarousel,
    #imagesCarousel .carousel-inner,
    #imagesCarousel .carousel-item {
        height: 60vh !important;
        background: black;
    }

    #imagesCarousel .carousel-item.active {
        display: flex !important;
    }

    #imagesCarousel .carousel-item-next {
        display: flex !important;
    }

    #imagesCarousel .carousel-item img {
        margin: auto;
    }

    #imagesCarousel img {
        width: auto !important;
        height: auto !important;
        max-height: calc(100%) !important;
        max-width: calc(100%) !important;
    }

    /* Updated CSS for the to-do list */
    #todoListContainer {
        width: 100%;
        height: 300rem;
        background: #1F1D2B;
        border-radius: 5px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
        padding: 10px;
        overflow-y: auto;
        max-height: 50vh;
        /* Added maximum height for responsiveness */
    }

    /* #input { */
    /* height: 50px;
        width: calc(100% - 20px); */
    /* Adjusted width */
    /* outline: none;
        border: none;
        border-radius: 5px;
        background: #666;
        color: #fff;
        padding: 0 10px;
        margin: 10px 0; */
    /* } */

    #input::placeholder {
        color: #bbb;
    }

    ul {
        padding: 0;
        /* Removed default padding */
    }

    ul li {
        background: #5E666F;
        color: whitesmoke;
        border-radius: 5px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        padding: 10px;
        margin-bottom: 5px;
        /* Added margin between list items */
        transition: 0.2s;
    }

    ul .icons {
        display: flex;
        gap: 10px;
    }

    ul .icons i {
        cursor: pointer;

        font-size: 20px;
    }

    ul .icons .checkbox {
        color: whitesmoke;
        font-size: 20px;
    }

    ul .icons .fa-trash {
        color: red;
    }

    ul .checked {
        text-decoration: line-through;
        color: #25d366;
        /* color: #FED108; */
    }

    /* .container {
        width: 100%;
        height: 80%;
        background: #1F1D2B;
        border-radius: 5px;
        box-shadow: 0 5px 10px rgba(0, 0, 0, 0.5);
        padding: 10px;
        overflow-y: auto;
    }


    #input {
        height: 50px;
        width: 100%;
        outline: none;
        border: none;
        border-radius: 5px;
        background: #666;
        color: #fff;
        padding: 0 10px;
        margin: 10px 0;
    }

    #input::placeholder {
        color: #bbb;
    }

    ul li {

        background: #5E666F;
        color: whitesmoke;
        border-radius: 50px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        transition: 0.2s;
    }

    .icons {
        display: flex;
        gap: 10px;
    }

    .icons i {
        cursor: pointer;
    }

    .icons .checkbox {
        color: whitesmoke;
        font-size: 20px;
    }

    .icons .fa-trash {
        color: red;
    }

    ul .checked {
        text-decoration: line-through;
        color: #FED108;
    } */

    #shareContainer {
        display: flex;
        flex-direction: row;
        justify-content: space-around;
        align-items: center;
        margin-top: 20px;
    }

    #shareBtn {
        /* background: #FED108;
        color: black;
        border: none;
        border-radius: 5px;
        padding: 10px;
        cursor: pointer;
        margin-top: 10px; */
        background-color: #FED108;
        color: black;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #whatsappBtn {
        background-color: #25D366;
        color: black;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }


    #whatsappInputContainer {
        display: none;
    }

    #whatsappInputContainer input[type="text"] {
        border: 1px solid #E4E4E4;
        border-radius: 5px;
        padding: 10px;
        width: 200px;
        margin-right: 10px;
    }

    #whatsappInputContainer button {
        background-color: #25D366;
        color: black;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #whatsappInputContainer button:hover {
        color: white;
        background-color: #128C7E;
    }

    #printButton {
        background-color: #fed108;
        color: black;
        border: none;
        border-radius: 5px;
        padding: 10px 20px;
        cursor: pointer;
        transition: background-color 0.3s;
    }

    #printButton :hover {
        color: white;
        background-color: #94831a;
    }

    /**.aspect-ratio-container
    
    */
    .aspect-ratio-container {
        position: relative;
        width: 100%;
    }

    .aspect-ratio-container:before {
        content: "";
        display: block;
        padding-top: 50%;
        /* Adjust this value to set the desired aspect ratio (e.g., 75% for 4:3, 56.25% for 16:9) */
    }

    .aspect-ratio-content {
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
    }
</style>
<style>
</style>
<div class="containe-fluid">
    <div class="row mb-2 mt-3">
        <div class="col-md-12">

        </div>
    </div>
    <div class="row ml-3 mr-3">
        <div class="col-lg-12" style="margin-left:-100px">
            <div class="card">
                <div class="card-body text-white text-center h2 p-0 pt-2" style="background-color: #1F1D2B;">
                    <?php echo "Welcome back " . $_SESSION['login_name'] . "!"  ?>
                    <hr>
                </div>
            </div>
        </div>
    </div>
    <div class="row ml-3 mr-3">
        <div class="col-lg-<?php echo ($_SESSION['login_type'] == 1) ? 6 : 12; ?>
 py-3" style="margin-left: -100px; ">
            <div class="aspect-ratio-container">
                <div class="aspect-ratio-content" style="height: 100%;">
                    <div class="card-header text-white h4" style="background-color: #343a40;">
                        <span>
                            To-Do
                        </span>
                        <span style="<?php echo ($_SESSION['login_type'] == 1) ?  "float: right;" : ""; ?>">
                            <input type="text" id="input" placeholder="Enter to add" style="margin: 0px 10px; padding: 5px; font-size: 18px; width: <?php echo ($_SESSION['login_type'] == 1) ? 400 : 1000; ?>px; border-radius: 5px;">
                            <button id="addButton" style="padding: 5px; font-size: 18px; width: 40px; border-radius: 5px; ">+</button>
                        </span>
                    </div>
                    <div class="card" style="background-color: #1F1D2B; max-height: 100%;">
                        <div id="todoListContainer" class="container">
                            <ul></ul>
                        </div>
                        <div id="shareContainer" style="margin-bottom: 10px;">
                            <button id="shareBtn" <?php echo ($_SESSION['login_type'] == 2) ?  'style="width: 370px;"' : ""; ?>><img src="assets\images\downloads.png" width="15px" height="15px" alt="" style="margin-right: 5px;"> Download</button>
                            <button id="whatsappBtn" <?php echo ($_SESSION['login_type'] == 2) ?  'style="width: 370px;"' : ""; ?>><img src="assets\images\whatsapp.png" alt="" width="15px" height="15px" style="margin-right: 5px;"> Share via WhatsApp</button>
                            <div id="whatsappInputContainer" style="display: none;">
                                <input type="text" id="whatsappNumber" <?php echo ($_SESSION['login_type'] == 2) ?  'style="width: 380px;"' : ""; ?> placeholder="Enter WhatsApp number">
                                <button id="sendToWhatsAppBtn" <?php echo ($_SESSION['login_type'] == 2) ?  'style="width: 80px;"' : ""; ?>> Send</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <?php if ($_SESSION['login_type'] == 1) : ?>

            <div class="col-lg-6 py-3">
                <div class="card-header text-white h4" style="background-color: #343a40; border-radius: 5px;">
                    <span>
                        Product Sales
                    </span>
                    <input type="number" id="productCount" name="productCount" min="1" value="10" style="padding: 5px; font-size: 16px; border-radius: 5px; float: center; width: 60px;">
                    <button id="printButton" style="padding: 5px; font-size: 16px; border-radius: 5px; float: center;"><img src="assets\images\printer.png" width="15px" height="15px" alt="" style="margin-right: 5px;"> Print Chart Data</button>

                    <select id="productChartTypeDropdown" style="padding: 5px; font-size: 16px; border-radius: 5px; float: right;">
                        <option value="line">📈 Line Chart</option>
                        <option value="bar">📊 Bar Chart</option>
                        <option value="radar">🕸️ Radar Chart</option>
                        <option value="doughnut">🍩 Doughnut Chart</option>
                        <option value="pie" selected>🥧 Pie Chart</option>
                        <option value="polarArea">🌀 Polar Area Chart</option>
                        <option value="bubble">🔵 Bubble Chart</option>
                        <option value="scatter">✨ Scatter Chart</option>
                    </select>
                </div>
                <div class="card" style="background-color: #1F1D2B;">
                    <canvas id="topItemsChart" width="400" height="200"></canvas>
                </div>
            </div>


    </div>
    <div class="row ml-3 mr-3">
        <div class="col-lg-6" style="margin-left:-100px">
            <div class="card-header text-white h4" style="background-color: #343a40;">
                <span>
                    Monthly Sales
                </span>
                <select id="chartTypeDropdown" style="padding: 5px; font-size: 16px; border-radius: 5px; float: right;">
                    <option value="line" selected>📈 Line Chart</option>
                    <option value="bar">📊 Bar Chart</option>
                    <option value="radar">🕸️ Radar Chart</option>
                    <option value="doughnut">🍩 Doughnut Chart</option>
                    <option value="pie">🥧 Pie Chart</option>
                    <option value="polarArea">🌀 Polar Area Chart</option>
                    <option value="bubble">🔵 Bubble Chart</option>
                    <option value="scatter">✨ Scatter Chart</option>
                </select>
            </div>
            <div class="card" style="background-color: #1F1D2B;">
                <canvas id="salesChart" width="400" height="200"></canvas>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card" style="background-color: #1F1D2B;">
                <div class="card-header text-white h4" style="background-color: #343a40; border-radius: 5px;">
                    <span>
                        Yearly Sales
                    </span>
                    <select id="yearlySalesChartTypeDropdown" style="padding: 5px; font-size: 16px; border-radius: 5px; float: right;">
                        <option value="line">📈 Line Chart</option>
                        <option value="bar">📊 Bar Chart</option>
                        <option value="radar">🕸️ Radar Chart</option>
                        <option value="doughnut" selected>🍩 Doughnut Chart</option>
                        <option value="pie">🥧 Pie Chart</option>
                        <option value="polarArea">🌀 Polar Area Chart</option>
                        <option value="bubble">🔵 Bubble Chart</option>
                        <option value="scatter">✨ Scatter Chart</option>
                    </select>
                </div>
                <div class="card" style="background-color: #1F1D2B;">
                    <canvas id="yearlySalesChart" width="400" height="200"></canvas>
                </div>
            </div>
        </div>
    <?php endif; ?>
    </div>
</div>
<!-- <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script> -->
<script>
    $(document).ready(function() {
        // Load todo list from local storage on page load
        var todoList = JSON.parse(localStorage.getItem('todoList')) || [];

        // Function to populate todo list items
        function populateTodoList() {
            $('ul').empty(); // Clear existing list
            todoList.forEach(function(item) {
                $('ul').append(item);
            });
        }

        // Call function to populate todo list
        populateTodoList();

        $('#input').on('keyup', function(event) {
            if (event.key === "Enter") {
                addItem();
            }
        });
        $('#addButton').on('click', function() {
            addItem();
        });

        $('#addItemBtn').on('click', function() {
            addItem();
        });

        function addItem() {
            var input = $('#input').val();

            if (input.trim() !== '') {
                // Use a checkbox instead of a checkmark icon
                var listItem = '<li><div class="icons"><i class="fas checkbox fa-square"></i></div><span >' + input + '</span><div class="icons"></i><i class="fas fa-trash"></i></div></li>';
                $('ul').append(listItem);
                $('#input').val('');
                todoList.push(listItem); // Add new item to the todoList array
                saveTodoList(); // Save todo list to local storage
            }
        }

        $('ul').on('click', '.fa-trash', function() {
            $(this).closest('li').fadeOut(200, function() {
                $(this).remove();
                updateTodoList(); // Update todo list array after removing item
            });
        });

        $('ul').on('click', '.checkbox', function() {
            $(this).toggleClass('fa-check-square fa-square');
            $(this).closest('li').toggleClass('checked');
            updateTodoList(); // Update todo list array after toggling checkbox
        });

        $('#shareBtn').on('click', function() {
            shareToWhatsApp();
        });

        function shareToWhatsApp() {
            // Get today's date for the file name
            var today = new Date();
            var dd = String(today.getDate()).padStart(2, '0');
            var mm = String(today.getMonth() + 1).padStart(2, '0');
            var yyyy = today.getFullYear();
            var currentDate = dd + '-' + mm + '-' + yyyy;

            // Create a string representing the to-do list
            var pendingList = '';
            var doneList = '';
            $('ul li').each(function() {
                var task = $(this).text().trim();
                if ($(this).hasClass('checked')) {
                    doneList += task + '\n';
                } else {
                    pendingList += task + '\n';
                }
            });

            var todoListText = 'Pending:\n' + pendingList + '\nDone:\n' + doneList;

            // Create a Blob from the to-do list text
            var blob = new Blob([todoListText], {
                type: 'text/plain'
            });

            // Create a temporary link with a blob URL
            var link = document.createElement('a');
            link.href = URL.createObjectURL(blob);
            link.download = currentDate + ' To Do List.txt';

            // Simulate a click to trigger the download
            document.body.appendChild(link);
            link.click();
            document.body.removeChild(link);
        }

        // Function to save todo list to local storage
        function saveTodoList() {
            localStorage.setItem('todoList', JSON.stringify(todoList));
        }

        // Function to update todo list array in local storage
        function updateTodoList() {
            todoList = [];
            $('ul li').each(function() {
                var listItem = $(this).prop('outerHTML');
                todoList.push(listItem);
            });
            saveTodoList(); // Save updated todo list to local storage
        }
    });
    $('#whatsappBtn').on('click', function() {
        $('#whatsappInputContainer').toggle();
    });

    $('#sendToWhatsAppBtn').on('click', function() {
        var phoneNumber = $('#whatsappNumber').val();
        var todoListText = getTodoListText();
        sendTodoListToWhatsApp(phoneNumber, todoListText);
    });

    function getTodoListText() {
        var todoListText = '';
        $('ul li').each(function() {
            var task = $(this).text().trim();
            todoListText += task + '\n';
        });
        return todoListText;
    }

    function sendTodoListToWhatsApp(phoneNumber, todoListText) {
        // Open WhatsApp with prefilled message
        var whatsappLink = 'https://api.whatsapp.com/send?phone=' + encodeURIComponent(phoneNumber) + '&text=' + encodeURIComponent(todoListText);
        window.open(whatsappLink, '_blank');
    }
    // $(document).ready(function() {
    //     function updateContainerHeight() {
    //         var containerHeight = $('#todoListContainer').height();
    //         var listHeight = $('ul').height();
    //         var newHeight = listHeight > containerHeight ? listHeight : containerHeight;
    //         $('#todoListContainer').height(newHeight);
    //     }

    //     $('#input').on('keyup', function(event) {
    //         console.log("Key pressed!");
    //         if (event.key === "Enter") {
    //             var input = $(this).val();

    //             if (input.trim() !== '') {
    //                 $('ul').append('<li><div class="icons"><i class="fas checkbox fa-square"></i></div><span style="font-size: 20px; float: left; ">' + input + '</span><div class="icons"></i><i class="fas fa-trash"></i></div></li>');
    //                 $(this).val('');


    //                 updateContainerHeight();
    //             }
    //         }
    //     });

    //     $(document).on('click', 'ul .fa-trash', function() {
    //         $(this).closest('li').fadeOut(200, function() {
    //             $(this).remove();
    //             updateContainerHeight();
    //         });
    //     });

    //     $(document).on('click', 'ul .checkbox', function() {
    //         $(this).toggleClass('fa-check-square fa-square');
    //         $(this).closest('li').toggleClass('checked');
    //     });

    //     updateContainerHeight();
    // });
    //********************************************* */
    // $(document).ready(function() {
    //     $('#input').on('keyup', function(event) {
    //         console.log("Key pressed!"); // For debugging
    //         if (event.key === "Enter") {
    //             var input = $(this).val();

    //             if (input.trim() !== '') {
    //                 // Use a checkbox instead of a checkmark icon
    //                 $('ul').append('<li><div class="icons"><i class="fas checkbox fa-square"></i></div>' + input + '<div class="icons"></i><i class="fas fa-trash"></i></div></li>');
    //                 $(this).val('');
    //             }
    //         }
    //     });
    //     // Delegate event handling for dynamically added elements
    //     $(document).on('click', 'ul .fa-trash', function() {
    //         $(this).closest('li').fadeOut(200, function() {
    //             $(this).remove();
    //         });
    //     });

    //     $(document).on('click', 'ul .checkbox', function() {
    //         $(this).toggleClass('fa-check-square fa-square');
    //         $(this).closest('li').toggleClass('checked');
    //     });

    // $('#shareBtn').on('click', function() {
    //     shareToWhatsApp();
    // });
    // });
</script>
<script>
    // $('#whatsappBtn').on('click', function() {
    //     $('#whatsappInputContainer').toggle();
    // });

    // $('#sendToWhatsAppBtn').on('click', function() {
    //     var phoneNumber = $('#whatsappNumber').val();
    //     var todoList = getTodoList();
    //     sendTodoListToWhatsApp(phoneNumber, todoList);
    // });

    // function getTodoList() {
    //     var todoList = {
    //         pending: [],
    //         done: []
    //     };
    //     $('ul li').each(function() {
    //         var task = $(this).text().trim();
    //         if ($(this).hasClass('checked')) {
    //             todoList.done.push(task);
    //         } else {
    //             todoList.pending.push(task);
    //         }
    //     });
    //     return todoList;
    // }

    // function sendTodoListToWhatsApp(phoneNumber, todoList) {
    //     var message = "Pending:\n";
    //     message += todoList.pending.map((task, index) => (index + 1) + ". " + task).join("\n");
    //     message += "\n\nDone:\n";
    //     message += todoList.done.map((task, index) => (index + 1) + ". " + task).join("\n");

    //     // Open WhatsApp with prefilled message
    //     var whatsappLink = 'https://api.whatsapp.com/send?phone=' + encodeURIComponent(phoneNumber) + '&text=' + encodeURIComponent(message);
    //     window.open(whatsappLink, '_blank');
    // }
</script>
<script>
    // $(document).ready(function() {
    //     $('#input').on('keyup', function(event) {
    //         console.log("Key pressed!"); // For debugging
    //         if (event.key === "Enter") {
    //             var input = $(this).val();

    //             if (input.trim() !== '') {
    //                 // Use a checkbox instead of a checkmark icon
    //                 $('ul').append('<li><div class="icons"><i class="fas checkbox fa-square"></i></div>' + input + '<div class="icons"></i><i class="fas fa-trash"></i></div></li>');
    //                 $(this).val('');
    //             }
    //         }
    //     });

    //     // Delegate event handling for dynamically added elements
    //     $(document).on('click', 'ul .fa-trash', function() {
    //         $(this).closest('li').fadeOut(200, function() {
    //             $(this).remove();
    //         });
    //     });

    //     $(document).on('click', 'ul .checkbox', function() {
    //         $(this).toggleClass('fa-check-square fa-square');
    //         $(this).closest('li').toggleClass('checked');
    //     });

    //     $('#shareBtn').on('click', function() {
    //         shareToWhatsApp();
    //     });

    // });

    // function shareToWhatsApp() {
    //     // Get today's date for the file name
    //     var today = new Date();
    //     var dd = String(today.getDate()).padStart(2, '0');
    //     var mm = String(today.getMonth() + 1).padStart(2, '0');
    //     var yyyy = today.getFullYear();
    //     var currentDate = dd + '-' + mm + '-' + yyyy;

    //     // Create a string representing the to-do list
    //     var todoList = '';
    //     $('ul li').each(function() {
    //         var task = $(this).text().trim();
    //         todoList += task + '\n';
    //     });

    //     // Create a Blob from the to-do list text
    //     var blob = new Blob([todoList], {
    //         type: 'text/plain'
    //     });

    //     // Create a temporary link with a blob URL
    //     var link = document.createElement('a');
    //     link.href = URL.createObjectURL(blob);
    //     link.download = currentDate + ' To Do List.txt';

    //     // Simulate a click to trigger the download
    //     document.body.appendChild(link);
    //     link.click();
    //     document.body.removeChild(link);
    // }

    // // Convert PHP array to JavaScript array
    // var topItemsData = <?php #echo json_encode($top_items); 
                            ?>;

    // // Extract product IDs and quantities into separate arrays
    // var productIDs = Object.keys(topItemsData);
    // var quantities = Object.values(topItemsData);

    // // Fetch product names corresponding to the product IDs
    // var productNames = [];
    // productIDs.forEach(function(productID) {
    //     // Assuming you have a JavaScript array named 'products' containing product information
    //     // Iterate over 'products' array to find the product name based on 'productID'
    //     products.forEach(function(product) {
    //         if (product.id == productID) {
    //             productNames.push(product.name);
    //         }
    //     });
    // });
</script>

<script>
    $('#manage-records').submit(function(e) {
        e.preventDefault()
        start_load()
        $.ajax({
            url: 'ajax.php?action=save_track',
            data: new FormData($(this)[0]),
            cache: false,
            contentType: false,
            processData: false,
            method: 'POST',
            type: 'POST',
            success: function(resp) {
                resp = JSON.parse(resp)
                if (resp.status == 2) {
                    alert_toast("Data successfully saved", 'success')
                    setTimeout(function() {
                        location.reload()
                    }, 800)

                }

            }
        })
    })
    $('#tracking_id').on('keypress', function(e) {
        if (e.which == 13) {
            get_person()
        }
    })
    $('#check').on('click', function(e) {
        get_person()
    })

    function get_person() {
        start_load()
        $.ajax({
            url: 'ajax.php?action=get_pdetails',
            method: "POST",
            data: {
                tracking_id: $('#tracking_id').val()
            },
            success: function(resp) {
                if (resp) {
                    resp = JSON.parse(resp)
                    if (resp.status == 1) {
                        $('#name').html(resp.name)
                        $('#address').html(resp.address)
                        $('[name="person_id"]').val(resp.id)
                        $('#details').show()
                        end_load()

                    } else if (resp.status == 2) {
                        alert_toast("Unknow tracking id.", 'danger');
                        end_load();
                    }
                }
            }
        })
    }
</script>
<!-- <script src="https://cdn.jsdelivr.net/npm/date-fns@2"></script> -->
<!-- <script src="https://cdn.jsdelivr.net/npm/chart.js@3"></script>
<script src="https://cdn.jsdelivr.net/npm/chartjs-adapter-date-fns@3"></script> -->
<!-- <script src="assets\vendor\chart.js\cahrt.js"></script>
<script src="assets\vendor\chart.js\chartjs-adapter-date-fns@3.js"></script> -->
<!-- ------------------------------ -->
<script src="assets\chart.js\dist\chart.min.js"></script>
<script src="assets\chartjs-adapter-date-fns\dist\chartjs-adapter-date-fns.bundle.min.js"></script>
<script>
    /******************************************
     * Months
     ******************************************/
    <?php
    $months = [];
    $totalSales = [];

    while ($row = $sales_data->fetch_array()) {
        $months[] = $row['month'];
        $totalSales[] = $row['total_sales'];
    }
    ?>

    console.log("Months: ", <?php echo json_encode($months); ?>);
    console.log("Total Sales: ", <?php echo json_encode($totalSales); ?>);

    document.addEventListener('DOMContentLoaded', function() {
        var ctx = document.getElementById('salesChart').getContext('2d');
        var salesChart = new Chart(ctx, {
            type: 'line',
            data: {
                labels: <?php echo json_encode($months); ?>,
                datasets: [{
                    label: 'Total Sales',
                    data: <?php echo json_encode($totalSales); ?>,
                    // backgroundColor: 'rgba(75, 192, 192, 0.2)', // Background color
                    backgroundColor: [
                        'rgb(255, 245, 80)',
                        'rgb(48, 204, 108)',
                        'rgb(255, 222, 80)',
                        'rgb(52, 215, 52)',
                        'rgb(204, 173, 37)',
                        'rgb(39, 163, 39)',
                        'rgb(26, 111, 26)',
                        'rgb(25, 90, 25)'
                    ],
                    borderColor: 'rgb(52, 215, 52)', // Line color
                    borderWidth: 2,
                    tension: 0.4, // Controls the curvature of the lines
                    pointRadius: 4, // Size of data points
                    pointBackgroundColor: 'rgb(52, 215, 52)', // Color of data points
                }]
            },
            options: {
                animation: {
                    duration: 1500, // Animation duration in milliseconds
                    easing: 'easeInOutQuart', // Easing function for animation
                },
                scales: {
                    x: {
                        type: 'time',
                        time: {
                            unit: 'month'
                        }
                    },
                    y: {
                        beginAtZero: true
                    }
                },
                plugins: {
                    legend: {
                        display: true,
                        position: 'right',
                        align: 'start',
                        labels: {
                            boxWidth: 10,
                            padding: 10,
                        }
                    }
                },
                layout: {
                    padding: {
                        top: 10,
                        bottom: 10,
                        left: 10,
                        right: 10
                    }
                },
                aspectRatio: 2, // Set aspect ratio to maintain a square chart
                responsive: true // Make the chart responsive
            }
        });

        // Add event listener to the dropdown list
        var dropdown = document.getElementById('chartTypeDropdown');
        dropdown.addEventListener('change', function() {
            // Update the chart type based on the selected option
            salesChart.config.type = this.value;
            salesChart.update();
        });
    });
</script>
<script>
    /******************************************
     * yearly
     ******************************************/
    <?php
    $yearly_sales_data = $conn->query("SELECT YEAR(date_created) AS year, SUM(after_discount) AS total_sales FROM orders WHERE amount_tendered > 0 GROUP BY year ORDER BY year ASC");

    $yearlyMonths = [];
    $yearlyTotalSales = [];

    while ($row = $yearly_sales_data->fetch_array()) {
        $yearlyMonths[] = $row['year'];
        $yearlyTotalSales[] = $row['total_sales'];
    }
    ?>

    var yearlyCtx = document.getElementById('yearlySalesChart').getContext('2d');
    var yearlySalesChart = new Chart(yearlyCtx, {
        type: 'doughnut',
        data: {
            labels: <?php echo json_encode($yearlyMonths); ?>,
            datasets: [{
                label: 'Yearly Sales',
                data: <?php echo json_encode($yearlyTotalSales); ?>,
                backgroundColor: [
                    'rgb(255, 99, 132)',
                    'rgb(255, 205, 86)',
                    'rgb(54, 162, 235)',
                    'rgb(75, 192, 192)',
                    'rgb(153, 102, 255)',
                    'rgb(255, 159, 64)',
                ],
                borderColor: 'rgb(255, 255, 255)', // Line color
                borderWidth: 2,
                tension: 0.4, // Controls the curvature of the lines
                pointRadius: 5, // Size of data points
                pointBackgroundColor: 'rgb(52, 215, 52)', // Color of data points
            }]
        },
        options: {
            aspectRatio: 1,
            responsive: true,
            maintainAspectRatio: false,
            cutoutPercentage: 50,
            animation: {
                duration: 1500, // Animation duration in milliseconds
                easing: 'easeInOutQuart', // Easing function for animation
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'right', // Placing legend on the right side
                    align: 'start',
                    labels: {
                        boxWidth: 10,
                        padding: 10,
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            },
            aspectRatio: 2, // Set aspect ratio to maintain a square chart
            responsive: true // Make the chart responsive
        }
    });

    // Add event listener to the dropdown list
    var dropdown = document.getElementById('yearlySalesChartTypeDropdown');
    dropdown.addEventListener('change', function() {
        // Update the chart type based on the selected option
        yearlySalesChart.config.type = this.value;
        yearlySalesChart.update();
    });
</script>
<script>
    // Function to update the chart with new product count
    function updateChartWithProductCount(productCount) {
        // Get the top products and frequencies from PHP (already fetched)
        var productNames = <?php echo json_encode($productNames); ?>;
        var frequencies = <?php echo json_encode($frequencies); ?>;

        // Slice the arrays to the specified product count
        var slicedProductNames = productNames.slice(0, productCount);
        var slicedFrequencies = frequencies.slice(0, productCount);

        // Generate background colors for the chart
        var backgroundColors = [];
        for (var i = 0; i < productCount; i++) {
            backgroundColors.push(generateRandomColor());
        }

        // Update the chart data
        topItemsChart.data.labels = slicedProductNames;
        topItemsChart.data.datasets[0].data = slicedFrequencies;
        topItemsChart.data.datasets[0].backgroundColor = backgroundColors;

        // Update the chart
        topItemsChart.update();
    }

    // Function to generate random colors
    function generateRandomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }

    // Add event listener to the input field for product count
    var productCountInput = document.getElementById('productCount');
    productCountInput.addEventListener('change', function() {
        var productCount = parseInt(this.value); // Get the entered product count
        updateChartWithProductCount(productCount); // Update the chart with the new product count
    });

    // Generate an array of 50 unique colors
    var backgroundColors = [];
    for (var i = 0; i < 50; i++) {
        backgroundColors.push(generateRandomColor());
    }

    // Create Chart.js chart
    var ctx = document.getElementById('topItemsChart').getContext('2d');
    var topItemsChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php echo json_encode($productNames); ?>, // Use product names as labels
            datasets: [{
                label: 'Frequency',
                data: <?php echo json_encode($frequencies); ?>, // Use product frequencies as data
                backgroundColor: backgroundColors, // Use generated colors
                borderColor: 'rgb(255, 255, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            animation: {
                duration: 1500, // Animation duration in milliseconds
                easing: 'easeInOutQuart', // Easing function for animation
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'right', // Placing legend at the bottom
                    align: 'center', // Center align the legend
                    labels: {
                        boxWidth: 10,
                        padding: 10,
                    }
                }
            },
            layout: {
                padding: {
                    top: 10,
                    bottom: 10,
                    left: 10,
                    right: 10
                }
            },
            aspectRatio: 2, // Set aspect ratio to maintain a square chart
            responsive: true // Make the chart responsive
        }
    });

    // Add event listener to the dropdown list
    var dropdown = document.getElementById('productChartTypeDropdown');
    dropdown.addEventListener('change', function() {
        // Update the chart type based on the selected option
        topItemsChart.config.type = this.value;
        topItemsChart.update();
    });

    function generateTableContent() {
        // Define CSS styles for the table
        var tableStyle = 'border-collapse: collapse; width: auto;'; // Set width to auto to fit content
        var thStyle = 'border: 1px solid #ddd; padding: 10px; text-align: left; background-color: #007bff; color: #fff; font-weight: bold; border-radius: 5px; box-shadow: 0 2px 4px rgba(0,0,0,0.1);'; // Eye-catching style for table header
        var tdStyle = 'border: 1px solid #dddddd; padding: 8px;';

        var tableContent = '<div style="display: flex; justify-content: center;">'; // Center the table
        tableContent += '<table style="' + tableStyle + '"><thead><tr>';
        tableContent += '<th style="' + thStyle + '">Serial Number</th>';
        tableContent += '<th style="' + thStyle + '">Product Name</th>';
        tableContent += '<th style="' + thStyle + '">Quantity</th>';
        tableContent += '</tr></thead><tbody>';

        var productNames = <?php echo json_encode($productNames); ?>;
        var frequencies = <?php echo json_encode($frequencies); ?>;
        var productCount = parseInt(document.getElementById('productCount').value);
        var minCount = Math.min(productCount, productNames.length, frequencies.length);

        for (var i = 0; i < minCount; i++) {
            tableContent += '<tr>';
            tableContent += '<td style="' + tdStyle + '">' + (i + 1) + '</td>'; // Serial number
            tableContent += '<td style="' + tdStyle + '">' + productNames[i] + '</td>'; // Product name
            tableContent += '<td style="' + tdStyle + '">' + frequencies[i] + '</td>'; // Quantity
            tableContent += '</tr>';
        }

        tableContent += '</tbody></table>';
        tableContent += '</div>'; // Close the div
        return tableContent;
    }



    // Attach event listener to the print button
    var printButton = document.getElementById('printButton');
    printButton.addEventListener('click', function() {
        var tableContent = generateTableContent();
        var printWindow = window.open('', '_blank', 'height=400,width=600');
        printWindow.document.write('<html><head><title>Print</title></head><body>' + tableContent + '</body></html>');
        printWindow.document.close();
        printWindow.print();

        // Close the window after printing or canceling
        printWindow.onafterprint = function() {
            printWindow.close();
        };

        // For older browsers that don't support onafterprint event, use setTimeout as a fallback
        // setTimeout(function() {
        //     printWindow.close();
        // }, 1000); // Adjust the timeout as needed
    });


    // Initialize the chart with default product count
    updateChartWithProductCount(10); // You can specify any default number here
</script>

<!-- <script>
    /******************************************
     * Product
     ******************************************/

    // Function to update the chart with new product count
    function updateChartWithProductCount(productCount) {
        // Get the top products and frequencies from PHP (already fetched)
        var productNames = <?php #echo json_encode($productNames); 
                            ?>;
        var frequencies = <?php #echo json_encode($frequencies); 
                            ?>;

        // Slice the arrays to the specified product count
        var slicedProductNames = productNames.slice(0, productCount);
        var slicedFrequencies = frequencies.slice(0, productCount);

        // Generate background colors for the chart
        var backgroundColors = [];
        for (var i = 0; i < productCount; i++) {
            backgroundColors.push(generateRandomColor());
        }

        // Update the chart data
        topItemsChart.data.labels = slicedProductNames;
        topItemsChart.data.datasets[0].data = slicedFrequencies;
        topItemsChart.data.datasets[0].backgroundColor = backgroundColors;

        // Update the chart
        topItemsChart.update();
    }


    // Function to generate random colors
    function generateRandomColor() {
        return '#' + Math.floor(Math.random() * 16777215).toString(16);
    }
    // Generate an array of 50 unique colors
    var backgroundColors = [];
    for (var i = 0; i < 50; i++) {
        backgroundColors.push(generateRandomColor());
    }

    // Create Chart.js chart
    var ctx = document.getElementById('topItemsChart').getContext('2d');
    var topItemsChart = new Chart(ctx, {
        type: 'pie',
        data: {
            labels: <?php #echo json_encode($productNames); 
                    ?>, // Use product names as labels
            datasets: [{
                label: 'Frequency',
                data: <?php #echo json_encode($frequencies); 
                        ?>, // Use product frequencies as data
                backgroundColor: backgroundColors, // Use generated colors
                borderColor: 'rgb(255, 255, 255, 1)',
                borderWidth: 1
            }]
        },
        options: {
            animation: {
                duration: 1500, // Animation duration in milliseconds
                easing: 'easeInOutQuart', // Easing function for animation
            },
            plugins: {
                legend: {
                    display: true,
                    position: 'right', // Placing legend on the right side
                    align: 'start',
                    labels: {
                        boxWidth: 10,
                        padding: 10,
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true
                }
            }
        }
    });

    // Add event listener to the dropdown list
    var dropdown = document.getElementById('productChartTypeDropdown');
    dropdown.addEventListener('change', function() {
        // Update the chart type based on the selected option
        topItemsChart.config.type = this.value;
        topItemsChart.update();
    });
    updateChartWithProductCount(15); // You can specify any default number here
</script> -->