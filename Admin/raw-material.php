<?php
session_start();

// Redirect to login if no user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: /steelsync/admin/login.php");
    exit();
}
// Verify the user has the correct role
if ($_SESSION["role"] !== 'super_admin') {
    // Redirect to appropriate page or show error

    header("Location: /steelsync/admin/login.php");
    exit();
}

?>
<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Intellitect System</title>

    <link rel="stylesheet" href="assets/css/styles.css">
    <link href="https://cdn.jsdelivr.net/npm/remixicon@2.5.0/fonts/remixicon.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.4/css/all.min.css">
    <link href="css.css/style.css" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            font-family: 'Roboto', sans-serif;
        }

        .main--content {
            position: absolute;
            top: 60px;
            right: 0;
            height: 100%;
            width: calc(100% - 300px);
            padding: 20px;
            overflow-y: auto;
            background-color: #f7f7f7;
            transition: .3s;
        }

        .main--content.active {
            width: calc(100% - 103px);
        }

        .inventory {
            border-radius: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.1);
            background-color: #ffffff;
            padding-bottom: 15px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            background-color: #fff;
        }

        th,
        td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: center;
        }

        td:nth-child(3) {
            white-space: nowrap;
            /* Prevents wrapping of the phone number */
        }

        .control {
            display: flex;
        }

        .control-btn {
            background-color: orange;
            border: none;
            color: white;
            padding: 5px 8px;
            cursor: pointer;
        }

        .title {
            border-bottom: solid 1px rgba(0, 0, 0, 0.1);
            background-color: #fff;
            padding: 24px;
            border-top-left-radius: 10px;
            border-top-right-radius: 10px;
            height: 80px;
            display: flex;
            font-size: 20px;
            gap: 60%;
        }

        .title h1 {
            font-size: 24px;
            font-weight: bold;
            color: #333;
        }

        .title a {
            text-decoration: none;
            color: #000;
        }

        .navi {
            margin-top: 10px;
            align-items: baseline;
            width: 100%;
            gap: 10%;
            display: flex;
            margin-left: -2%;
        }

        .navi {
            display: flex;
            align-items: center;
            gap: 20px;
        }

        .navi a {
            text-decoration: none;
            color: black;
            padding: 10px 15px;
            font-weight: normal;
            display: inline-block;
            white-space: nowrap;
        }

        .navi a.active-tab {
            font-weight: normal;
            position: relative;
        }

        .navi a.active-tab::after {
            content: "";
            position: absolute;
            left: 0;
            bottom: -12px;
            width: 100%;
            height: 3px;
            background-color: orange;
            border-radius: 5px;
        }

        .pagination {
            margin-top: 10px;
            margin-right: 10px;
            text-align: right;
        }

        .pagination button {
            padding: 5px 10px;
            margin: 2px;
            border: none;
            cursor: pointer;
            border-radius: 4px;
            color: #fff;
        }

        .search-container {
            height: 70px;
            background: #fff;
        }

        .search-container input {
            padding: 5px;
            width: 200px;
            height: 40px;
            margin-top: 15px;
            margin-left: 20px;
            border-radius: 5px;
            border: solid 1px rgba(0, 0, 0, 0.2);
        }

        input[type="number"]::-webkit-outer-spin-button,
        input[type="number"]::-webkit-inner-spin-button {
            -webkit-appearance: none;
            margin: 0;
        }

        input[type="number"] {
            -moz-appearance: textfield;
        }

        .controls {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 15px 20px;
        }

        .filter-container {
            display: flex;
            gap: 10px;
        }

        .dropdown {
            padding: 5px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            background-color: #fff;
            min-width: 100px;
            font-size: 14px;
            height: 40px;
            margin-left: 20px;
            margin-top: 15px;
        }

        .search-container {
            display: flex;
            gap: 10px;
        }

        .search-input {
            padding: 8px 15px;
            border: 1px solid #ddd;
            border-radius: 4px;
            width: 250px;
        }

        .new-btn {
            padding: 8px 15px;
            background-color: #e91e63;
            color: white;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: bold;
            display: flex;
            align-items: center;
            font-size: 14px;
            height: 40px;
            margin-left: 36%;
            margin-top: 15px;
        }

        #appointmentModal {
            display: none;
            position: fixed;
            z-index: 101111;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            overflow: auto;
            padding-top: 60px;
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
            max-width: 600px;
            position: relative;
        }

        .close {
            color: #aaa;
            font-size: 28px;
            font-weight: bold;
            position: absolute;
            top: 10px;
            right: 25px;
        }

        .close:hover,
        .close:focus {
            color: black;
            text-decoration: none;
            cursor: pointer;
        }


        .new-btn i {
            margin-right: 5px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
        }

        table tbody td {
            font-size: 13px;
        }

        table tbody td:first-child {
            white-space: nowrap;
            font-size: 14px;
        }

        th {
            background-color: #f8f9fa;
            text-align: center;
            padding: 12px 20px;
            font-weight: bold;
            color: #333;
            border-bottom: 1px solid #ddd;
        }

        td {
            padding: 15px 20px;
            border-bottom: 1px solid #eee;
            color: #333;
        }

        table tbody td:nth-child(4) {
            width: 20px;
            white-space: nowrap;
            overflow: hidden;
            text-overflow: ellipsis;
        }


        .status {
            white-space: nowrap;
            display: flex;
            align-items: center;
            padding: 5px 10px;
            border-radius: 4px;
            font-size: 13px;
        }

        .action-btn {
            background: none;
            border: none;
            cursor: pointer;
            font-size: 20px;
            color: #666;
        }

        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            background-color: rgba(0, 0, 0, 0.5);
            align-items: center;
            justify-content: center;
        }

        .modal .form-group input,
        .modal .form-group select {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
            height: 40px;
        }

        .modal .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
            justify-content: center;
        }

        .modal-content {
            background: white;
            padding: 25px;
            border-radius: 8px;
            width: 600px;
            box-shadow: 0px 4px 12px rgba(0, 0, 0, 0.15);
            position: relative;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            cursor: pointer;
            color: #666;
        }

        .modal-content h2 {
            margin-top: 0;
            margin-bottom: 20px;
            font-size: 20px;
            color: #333;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 16px;
        }

        .form-group {
            flex: 1;
            display: flex;
            flex-direction: column;
        }

        .form-group label {
            font-size: 14px;
            margin-bottom: 6px;
            color: #333;
        }

        .form-group input {
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            font-size: 14px;
        }

        .form-actions {
            display: flex;
            justify-content: flex-end;
            margin-top: 20px;
        }

        .submit-btn {
            background-color:rgb(238, 151, 21);
            color: white;
            border: none;
            border-radius: 4px;
            padding: 10px 20px;
            font-size: 14px;
            cursor: pointer;
            display: flex;
            align-items: center;
        }

        .arrow {
            margin-left: 5px;
        }
    </style>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/sidebar.php"; ?>


    <div class="main--content">
        <div class="inventory">
            <div class="title">
                <h1>INVENTORY</h1>
                <div class="navi">
                    <a href="#sales" id="salesOrderTab" class="active-tab" onclick="showSalesOrder()">Sales Order</a>
                    <a href="#purchase" id="purchaseOrderTab" onclick="showPurchaseOrder()">Purchase Order</a>
                </div>
            </div>

            <div class="control">
                <div class="search-container">
                    <input class="searchBar" type="text" id="search" placeholder="Search..." onkeyup="filterTable()">
                </div>
                <div class="filter-container">
                    <select id="statusFilter" class="dropdown" onchange="filterStatus()">
                        <option value="">All</option>
                        <option value="scheduled">Completed</option>
                        <option value="not-confirmed">Ongoing</option>
                    </select>
                </div>
                <button class="new-btn" id="newOrderBtn">
                    <span style="font-size: 18px;">+</span> New Order
                </button>
            </div>

            <table id="inventoryTable">
                <thead>
                    <tr>
                        <th>Item Name</th>
                        <th>Incoming Stocks</th>
                        <th>Reserve Stocks</th>
                        <th>Remaining Stock</th>
                        <th>Status</th>
                    </tr>
                </thead>
                <tbody id="tableBody">
                </tbody>
            </table>

            <div id="purchaseOrderSection" style="display: none;">
                <table>
                    <thead>
                        <tr>
                            <th>Supplier Name</th>
                            <th>Item Name</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr>
                            <td>Jennylyn Vinuya</td>
                            <td>Gate Funirization</td>
                            <td>Paid</td>
                            <td>Completed</td>
                            <td><button class="action-btn">⋮</button></td>
                        </tr>
                        <tr>
                            <td>Mike Dela Cruz</td>
                            <td>Gate Fabrication</td>
                            <td>Unpaid</td>
                            <td>Ongoing</td>
                            <td><button class="action-btn">⋮</button></td>
                        </tr>
                        <tr>
                            <td>Ozzy Hernandez</td>
                            <td>Gate Fabrication</td>
                            <td>Paid</td>
                            <td>Completed</td>
                            <td><button class="action-btn">⋮</button></td>
                        </tr>
                        <tr>
                            <td>Angelo Agcuitan</td>
                            <td>Gate Fabrication</td>
                            <td>Unpaid</td>
                            <td>Ongoing</td>
                            <td><button class="action-btn">⋮</button></td>
                        </tr>
                        <tr>
                            <td>Alexis Paira</td>
                            <td>Gate Fabrication</td>
                            <td>Unpaid</td>
                            <td>Ongoing</td>
                            <td><button class="action-btn">⋮</button></td>
                        </tr>
                        <tr>
                            <td>Rossel Tiqua</td>
                            <td>Gate Fabrication</td>
                            <td>Paid</td>
                            <td>Completed</td>
                            <td><button class="action-btn">⋮</button></td>
                        </tr>
                    </tbody>
                </table>
            </div>



            <div class="pagination">
                <button style="color: #000;" onclick="prevPage()">Prev</button>
                <span id="pageInfo">Page 1</span>
                <button style="background-color:darkorange" onclick="nextPage()">Next</button>
            </div>
        </div>
    </div>
    <div id="orderModal-purchase" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Order Form</h2>
            <form id="orderForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fullName">Supplier Name</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Company</label>
                        <input type="text" id="company" name="company" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="item">Item</label>
                        <input type="text" id="item" name="item" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="tel" id="quantity" name="quantity" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit <span class="arrow">→</span></button>
                </div>
            </form>
        </div>
    </div>
    <div id="orderModal-sales" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2>Add New Materials</h2>
            <form id="salesOrderForm">
                <div class="form-row">
                    <div class="form-group">
                        <label for="fullName">Item Name</label>
                        <input type="text" id="fullName" name="fullName" required>
                    </div>
                    <div class="form-group">
                        <label for="company">Item Code</label>
                        <input type="text" id="company" name="company" required>
                    </div>
                </div>

                <div class="form-row">
                    <div class="form-group">
                        <label for="item">Color Code</label>
                        <input type="text" id="item" name="item" required>
                    </div>
                    <div class="form-group">
                        <label for="quantity">Quantity</label>
                        <input type="tel" id="quantity" name="quantity" required>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="submit-btn">Submit <span class="arrow">→</span></button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const items = [{
                name: "Flap disc",
                incoming: 100,
                reserve: 1
            },
            {
                name: "Cutting wheel",
                incoming: 100,
                reserve: 1
            },
            {
                name: "Grinding wheel",
                incoming: 100,
                reserve: 1
            },
            {
                name: "Electrical tape",
                incoming: 100,
                reserve: 1
            },
            {
                name: "2gang",
                incoming: 100,
                reserve: 1
            },
            {
                name: "Outlet (2gang)",
                incoming: 100,
                reserve: 1
            },
            {
                name: "Blank plate",
                incoming: 100,
                reserve: 0
            },
            {
                name: "Amon/ciyo box",
                incoming: 100,
                reserve: 0
            },
            {
                name: "1gang switch",
                incoming: 100,
                reserve: 0
            },
            {
                name: "Utility box",
                incoming: 100,
                reserve: 0
            },
            {
                name: "Flux cored",
                incoming: 100,
                reserve: 0
            },
            {
                name: "Welding rod",
                incoming: 100,
                reserve: 0
            },
            {
                name: "Cylindrical",
                incoming: 100,
                reserve: 0
            },
            {
                name: "Acrylic sealant (black)",
                incoming: 100,
                reserve: 0
            },
            {
                name: "Acrylic sealant (brown)",
                incoming: 100,
                reserve: 0,
                status: "Call Supplier"
            }
        ];

        let currentPage = 1;
        const rowsPerPage = 5;

        function renderTable() {
            const tableBody = document.getElementById("tableBody");
            tableBody.innerHTML = "";
            let start = (currentPage - 1) * rowsPerPage;
            let end = start + rowsPerPage;
            let paginatedItems = items.slice(start, end);

            paginatedItems.forEach((item, index) => {
                let remainingStock = item.incoming - item.reserve;
                let row = `<tr>
            <td>${item.name}</td>
            <td>${item.incoming}</td>
            <td>
                <button class="control-btn" onclick="updateStock(${start + index}, -1)">-</button>
                <input type="number" value="${item.reserve}" min="0" max="${item.incoming}" oninput="manualUpdateStock(${start + index}, this.value)">
                <button class="control-btn" onclick="updateStock(${start + index}, 1)">+</button>
            </td>
            <td>${remainingStock}</td>
            <td>${item.status ? item.status : ""}</td>
        </tr>`;
                tableBody.innerHTML += row;
            });

            document.getElementById("pageInfo").innerText = `Page ${currentPage} of ${Math.ceil(items.length / rowsPerPage)}`;
        }

        function updateStock(index, change) {
            let newReserve = items[index].reserve + change;
            if (newReserve >= 0 && newReserve <= items[index].incoming) {
                items[index].reserve = newReserve;
                renderTable();
            }
        }

        function manualUpdateStock(index, value) {
            let newReserve = parseInt(value);
            if (!isNaN(newReserve) && newReserve >= 0 && newReserve <= items[index].incoming) {
                items[index].reserve = newReserve;
                renderTable();
            }
        }

        function prevPage() {
            if (currentPage > 1) {
                currentPage--;
                renderTable();
            }
        }

        function nextPage() {
            if (currentPage < Math.ceil(items.length / rowsPerPage)) {
                currentPage++;
                renderTable();
            }
        }

        function filterTable() {
            let searchInput = document.getElementById("search").value.toLowerCase();
            let filteredItems = items.filter(item => item.name.toLowerCase().includes(searchInput));

            if (filteredItems.length > 0) {
                document.getElementById("tableBody").innerHTML = "";
                filteredItems.forEach(item => {
                    let remainingStock = item.incoming - item.reserve;
                    let row = `<tr>
                <td>${item.name}</td>
                <td>${item.incoming}</td>
                <td>
                    <button class="control-btn">-</button>
                    <input type="number" value="${item.reserve}" min="0" max="${item.incoming}">
                    <button class="control-btn">+</button>
                </td>
                <td>${remainingStock}</td>
                <td>${item.status ? item.status : ""}</td>
            </tr>`;
                    document.getElementById("tableBody").innerHTML += row;
                });
            } else {
                document.getElementById("tableBody").innerHTML = "<tr><td colspan='5'>No items found</td></tr>";
            }
        }

        renderTable();

        document.addEventListener('DOMContentLoaded', function() {

            showSalesOrder();
        });

        function showSalesOrder() {
            document.getElementById("inventoryTable").style.display = "table";
            document.getElementById("purchaseOrderSection").style.display = "none";

            document.getElementById("salesOrderTab").classList.add("active-tab");
            document.getElementById("purchaseOrderTab").classList.remove("active-tab");
        }

        function showPurchaseOrder() {
            document.getElementById("inventoryTable").style.display = "none";
            document.getElementById("purchaseOrderSection").style.display = "block";

            document.getElementById("statusFilter").style.display = "block";

            document.getElementById("salesOrderTab").classList.remove("active-tab");
            document.getElementById("purchaseOrderTab").classList.add("active-tab");
        }

        // Get modal elements
        const salesModal = document.getElementById("orderModal-sales");
        const purchaseModal = document.getElementById("orderModal-purchase");

        // Get close button for each modal
        const closeSalesModal = salesModal.querySelector(".close");
        const closePurchaseModal = purchaseModal.querySelector(".close");

        // Show the modal based on the active tab
        document.getElementById("newOrderBtn").addEventListener("click", function() {
            if (document.getElementById("salesOrderTab").classList.contains("active-tab")) {
                // Show Sales Order modal
                salesModal.style.display = "block";
            } else if (document.getElementById("purchaseOrderTab").classList.contains("active-tab")) {
                // Show Purchase Order modal
                purchaseModal.style.display = "block";
            }
        });

        // Close modals when the close button is clicked
        closeSalesModal.addEventListener("click", function() {
            salesModal.style.display = "none";
        });

        closePurchaseModal.addEventListener("click", function() {
            purchaseModal.style.display = "none";
        });

        // Close modals if the user clicks outside of the modal
        window.addEventListener("click", function(event) {
            if (event.target === salesModal) {
                salesModal.style.display = "none";
            } else if (event.target === purchaseModal) {
                purchaseModal.style.display = "none";
            }
        });

        // Function to update the button text based on the active tab
        function updateButtonText() {
            const newOrderBtn = document.getElementById("newOrderBtn");

            // Check if Sales Order tab is active
            if (document.getElementById("salesOrderTab").classList.contains("active-tab")) {
                newOrderBtn.innerHTML = '<span style="font-size: 18px;">+ </span> New Materials'; // Change text to "New Item"
            } else if (document.getElementById("purchaseOrderTab").classList.contains("active-tab")) {
                newOrderBtn.innerHTML = '<span style="font-size: 18px;">+</span> New Order'; // Keep it as "New Order"
            }
        }

        // Update the button text when the page loads
        document.addEventListener('DOMContentLoaded', function() {
            updateButtonText();
        });

        // Also call this function when switching tabs to ensure the button text updates dynamically
        document.getElementById("salesOrderTab").addEventListener("click", function() {
            updateButtonText();
        });

        document.getElementById("purchaseOrderTab").addEventListener("click", function() {
            updateButtonText();
        });
    </script>
    <?php include "includes/script.php"; ?>
</body>

</html>