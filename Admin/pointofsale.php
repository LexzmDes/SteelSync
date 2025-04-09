<?php
session_start();

// Redirect to login if no user is logged in
if (!isset($_SESSION["user"])) {
    header("Location: /steelsync/admin/login.php");
    exit();
}
// Verify the user has the correct role
if ($_SESSION["role"] !== 'super_admin') {
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

        .orders-table th {
            text-align: center !important;
            /* Center all headers */
            vertical-align: middle;
            /* Vertically center content if needed */
        }

        .orders-table td {
            text-align: center !important;
            /* Center all cell content */
        }

        /* Specific fix for action buttons */
        .action-buttons {
            justify-content: center !important;
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

        /* Orders Management Container */
        .orders-manager-container {
            background-color: white;
            border-radius: 8px;
            padding: 20px;
            box-shadow: 0 2px 10px rgba(0, 0, 0, 0.05);
        }

        .orders-manager-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            margin-bottom: 20px;
            padding-bottom: 15px;
            border-bottom: 1px solid #eee;
        }

        .orders-manager-header h2 {
            font-size: 24px;
            color: #333;
        }

        /* Filter and Search */
        .orders-manager-tools {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
            flex-wrap: wrap;
            gap: 15px;
        }

        .filter-search {
            display: flex;
            gap: 15px;
            flex-wrap: wrap;
            flex: 1;
        }

        .dropdown select {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px;
            background-color: #fff;
            min-width: 200px;
        }

        .search-container {
            display: flex;
            align-items: center;
            flex: 1;
            max-width: 400px;
        }

        .search-container input {
            padding: 10px 15px;
            border: 1px solid #e0e0e0;
            border-radius: 5px 0 0 5px;
            flex: 1;
        }

        .search-btn {
            padding: 10px 15px;
            background-color: #f57c00;
            color: white;
            border: none;
            border-radius: 0 5px 5px 0;
            cursor: pointer;
        }

        .add-order-btn {
            background-color: #f57c00;
            color: white;
            border: none;
            border-radius: 5px;
            padding: 10px 20px;
            font-weight: 500;
            cursor: pointer;
            display: flex;
            align-items: center;
            gap: 5px;
            transition: background-color 0.3s;
        }

        .add-order-btn:hover {
            background-color: #e65100;
        }

        /* Orders Table */
        .orders-table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }

        .orders-table th {
            background-color: #f5f5f5;
            padding: 12px 15px;
            text-align: left;
            font-weight: 500;
            color: #555;
            border-bottom: 2px solid #eee;
        }

        .orders-table td {
            padding: 12px 15px;
            border-bottom: 1px solid #eee;
            color: #555;
        }

        .orders-table tr:hover {
            background-color: #f9f9f9;
        }

        /* Status Badges */
        .status-badge {
            padding: 6px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: 500;
            display: inline-block;
        }

        .paid {
            background-color: #e0edff;
            color: #4a7eff;
        }

        .unpaid {
            background-color: #ffe0e0;
            color: #ff4a4a;
        }

        .ongoing {
            background-color: #e0f7ff;
            color: #4ac4ff;
        }

        .complete {
            background-color: #e0ffe0;
            color: #2ecc71;
        }

        /* Action Buttons */
        .action-buttons {
            display: flex;
            gap: 8px;
        }

        .action-btn {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
            font-size: 14px;
            font-weight: 500;
            transition: all 0.3s;
            display: flex;
            align-items: center;
            gap: 5px;
        }

        .view-btn {
            background-color: #e0f7ff;
            color: #4ac4ff;
        }

        .view-btn:hover {
            background-color: #d0e7ff;
        }

        .complete-btn {
            background-color: #e0ffe0;
            color: #2ecc71;
        }

        .complete-btn:hover {
            background-color: #d0f0d0;
        }

        .archive-btn {
            color: black;
        }

        .archive-btn:hover {
            background-color: #555;
        }

        .restore-btn {
            background-color: #2ecc71;
            color: white;
        }

        .restore-btn:hover {
            background-color: #27ae60;
        }

        /* Archived row styling */
        .archived {
            opacity: 0.7;
            background-color: #f9f9f9;
        }

        .archived td {
            color: #999;
        }

        .archived-badge {
            background-color: #f39c12;
            color: white;
            font-size: 10px;
            padding: 2px 5px;
            border-radius: 3px;
            margin-left: 5px;
        }

        /* Pagination */
        .pagination {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-top: 20px;
        }

        .page-buttons {
            display: flex;
            gap: 8px;
        }

        .page-btn {
            padding: 8px 15px;
            border: 1px solid #e0e0e0;
            background-color: #fff;
            border-radius: 4px;
            cursor: pointer;
            transition: all 0.3s;
        }

        .page-btn:hover:not(:disabled) {
            background-color: #f0f0f0;
        }

        .page-btn:disabled {
            opacity: 0.5;
            cursor: not-allowed;
        }

        .page-numbers {
            display: flex;
            gap: 5px;
        }

        .page-number {
            padding: 8px 12px;
            border: 1px solid #e0e0e0;
            background-color: #fff;
            border-radius: 4px;
            cursor: pointer;
        }

        .page-number.active {
            background-color: #f57c00;
            color: white;
            border-color: #f57c00;
        }

        .page-info {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .page-info input {
            width: 40px;
            padding: 5px;
            text-align: center;
            border: 1px solid #e0e0e0;
            border-radius: 4px;
        }

        /* Modal Styles */
        .modal {
            display: none;
            position: fixed;
            z-index: 1000;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0, 0, 0, 0.5);
        }

        .modal-content {
            background-color: #fff;
            margin: 5% auto;
            padding: 25px;
            border-radius: 8px;
            width: 90%;
            max-width: 800px;
            position: relative;
        }

        .close {
            position: absolute;
            top: 15px;
            right: 20px;
            font-size: 24px;
            color: #aaa;
            cursor: pointer;
        }

        .close:hover {
            color: #333;
        }

        /* Form Styles */
        .form-section {
            margin-bottom: 20px;
        }

        .form-section h3 {
            margin-bottom: 15px;
            color: #333;
            font-size: 18px;
        }

        .form-row {
            display: flex;
            gap: 20px;
            margin-bottom: 15px;
        }

        .form-group {
            flex: 1;
        }

        .form-group label {
            display: block;
            margin-bottom: 5px;
            color: #555;
        }

        .form-group input,
        .form-group select,
        .form-group textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
        }

        .form-buttons {
            display: flex;
            justify-content: flex-end;
            gap: 10px;
            margin-top: 20px;
        }

        .form-buttons button {
            padding: 10px 20px;
            border: none;
            border-radius: 4px;
            cursor: pointer;
            font-weight: 500;
        }

        .cancel-btn {
            background-color: #f5f5f5;
            color: #555;
        }

        .save-btn {
            background-color: #f57c00;
            color: white;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .form-row {
                flex-direction: column;
                gap: 15px;
            }

            .orders-manager-tools {
                flex-direction: column;
            }

            .filter-search {
                width: 100%;
            }

            .search-container {
                max-width: 100%;
            }

            .add-order-btn {
                width: 100%;
                justify-content: center;
            }

            .action-buttons {
                flex-direction: column;
            }
        }
    </style>
</head>

<body>
    <?php include "includes/header.php"; ?>
    <?php include "includes/sidebar.php"; ?>

    <div class="main--content">
        <div class="orders-manager-container">
            <div class="orders-manager-header">
                <h2>ORDERS MANAGER</h2>
            </div>

            <div class="orders-manager-tools">
                <div class="filter-search">
                    <div class="dropdown">
                        <select id="filterDropdown">
                            <option value="all">All Orders</option>
                            <option value="archived">Archived Orders</option>
                            <option value="ongoing">Ongoing</option>
                            <option value="complete">Complete</option>
                            <option value="paid">Paid</option>
                            <option value="unpaid">Unpaid</option>
                        </select>
                    </div>
                    <div class="search-container">
                        <input type="text" id="searchInput" placeholder="Search by client name">
                        <button class="search-btn">Search</button>
                    </div>
                </div>
                <button class="add-order-btn" id="openOrderModalBtn">
                    <i class="fas fa-plus"></i> Add Order
                </button>
            </div>

            <table class="orders-table">
                <thead>
                    <tr>
                        <th>Client Name</th>
                        <th>Service Availed</th>
                        <th>Payment Status</th>
                        <th>Order Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody id="ordersTableBody">
                    <!-- Orders will be populated via JavaScript -->
                </tbody>
            </table>

            <div class="pagination">
                <div class="page-buttons">
                    <button class="page-btn" id="prevBtn" disabled><i class="fas fa-chevron-left"></i> Prev</button>
                    <div class="page-numbers" id="pageNumbers">
                        <button class="page-number active">1</button>
                    </div>
                    <button class="page-btn" id="nextBtn">Next <i class="fas fa-chevron-right"></i></button>
                </div>
                <div class="page-info">
                    Page:
                    <input type="text" class="page-input" id="pageInput" value="1">
                    of <span id="totalPages">1</span>
                </div>
            </div>
        </div>
    </div>

    <!-- Order Modal -->
    <div id="orderModal" class="modal">
        <div class="modal-content">
            <span class="close">&times;</span>
            <h2 id="modalTitle">Add New Order</h2>
            <form id="orderForm">
                <input type="hidden" id="orderId" value="">

                <div class="form-section">
                    <h3>Client Information</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="clientName">Client Name</label>
                            <input type="text" id="clientName" required>
                        </div>
                        <div class="form-group">
                            <label for="contactNumber">Contact Number</label>
                            <input type="text" id="contactNumber">
                        </div>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Order Details</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="service">Service</label>
                            <select id="service" required>
                                <option value="Gate Automation">Gate Automation</option>
                                <option value="Sectional Garage Door">Sectional Garage Door</option>
                                <option value="Roll Up Door">Roll Up Door</option>
                                <option value="Smart Electric Fence">Smart Electric Fence</option>
                                <option value="Gate Fabrication">Gate Fabrication</option>
                                <option value="Smart Curtain">Smart Curtain</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="quantity">Quantity</label>
                            <input type="number" id="quantity" value="1" min="1" required>
                        </div>
                    </div>
                    <div class="form-group">
                        <label for="notes">Notes</label>
                        <textarea id="notes" rows="3"></textarea>
                    </div>
                </div>

                <div class="form-section">
                    <h3>Status</h3>
                    <div class="form-row">
                        <div class="form-group">
                            <label for="paymentStatus">Payment Status</label>
                            <select id="paymentStatus" required>
                                <option value="paid">Paid</option>
                                <option value="unpaid">Unpaid</option>
                            </select>
                        </div>
                        <div class="form-group">
                            <label for="orderStatus">Order Status</label>
                            <select id="orderStatus" required>
                                <option value="ongoing">Ongoing</option>
                                <option value="complete">Complete</option>
                            </select>
                        </div>
                    </div>
                </div>

                <div class="form-buttons">
                    <button type="button" id="cancelBtn">Cancel</button>
                    <button type="submit" id="saveBtn">Save</button>
                </div>
            </form>
        </div>
    </div>

    <!-- Archive Confirmation Modal -->
    <div id="confirmModal" class="modal">
        <div class="modal-content confirmation-modal">
            <h2 id="archiveModalTitle">Confirm Archive</h2>
            <p id="archiveConfirmText">Are you sure you want to archive this order? Archived orders can be restored later.</p>
            <div class="form-buttons">
                <button type="button" id="cancelArchiveBtn">Cancel</button>
                <button type="button" id="confirmArchiveBtn">Archive</button>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Sample order data with archived status
            const orderData = [{
                    id: 1,
                    clientName: "Jennylyn Vinuya",
                    service: "Gate Automation",
                    paymentStatus: "unpaid",
                    orderStatus: "ongoing",
                    archived: false
                },
                {
                    id: 2,
                    clientName: "Mike Dela Cruz",
                    service: "Sectional Garage Door",
                    paymentStatus: "paid",
                    orderStatus: "complete",
                    archived: false
                },
                {
                    id: 3,
                    clientName: "Ozzy Hernandez",
                    service: "Roll Up Door",
                    paymentStatus: "unpaid",
                    orderStatus: "complete",
                    archived: false
                },
                {
                    id: 4,
                    clientName: "Angelo Aqcuitan",
                    service: "Smart Electric Fence",
                    paymentStatus: "paid",
                    orderStatus: "ongoing",
                    archived: false
                },
                {
                    id: 5,
                    clientName: "Alexis Paira",
                    service: "Gate Fabrication",
                    paymentStatus: "paid",
                    orderStatus: "complete",
                    archived: false
                },
                {
                    id: 6,
                    clientName: "Rossel Tiqua",
                    service: "Smart Curtain",
                    paymentStatus: "unpaid",
                    orderStatus: "ongoing",
                    archived: false
                },
                {
                    id: 7,
                    clientName: "Alfred Io",
                    service: "Smart Electric Fence",
                    paymentStatus: "paid",
                    orderStatus: "ongoing",
                    archived: false
                },
                {
                    id: 8,
                    clientName: "Rossel Tiqua",
                    service: "Smart Curtain",
                    paymentStatus: "unpaid",
                    orderStatus: "complete",
                    archived: true
                }
            ];

            // Variables for pagination
            let currentPage = 1;
            const recordsPerPage = 5;
            let filteredData = [...orderData];

            // Elements
            const filterDropdown = document.getElementById('filterDropdown');
            const searchInput = document.getElementById('searchInput');
            const tableBody = document.getElementById('ordersTableBody');
            const prevBtn = document.getElementById('prevBtn');
            const nextBtn = document.getElementById('nextBtn');
            const pageInput = document.getElementById('pageInput');
            const totalPagesSpan = document.getElementById('totalPages');
            const pageNumbersDiv = document.getElementById('pageNumbers');
            const addOrderBtn = document.getElementById('openOrderModalBtn');
            const orderModal = document.getElementById('orderModal');
            const confirmModal = document.getElementById('confirmModal');
            const modalCloseBtns = document.querySelectorAll('.close');
            const orderForm = document.getElementById('orderForm');
            const modalTitle = document.getElementById('modalTitle');
            const orderIdInput = document.getElementById('orderId');
            const cancelBtn = document.getElementById('cancelBtn');
            const cancelArchiveBtn = document.getElementById('cancelArchiveBtn');
            const confirmArchiveBtn = document.getElementById('confirmArchiveBtn');
            const archiveModalTitle = document.getElementById('archiveModalTitle');
            const archiveConfirmText = document.getElementById('archiveConfirmText');

            // Initialize the page
            function init() {
                displayData();
                setupEventListeners();
            }

            // Filter and display data
            function displayData() {
                // Filter data based on dropdown and search input
                const filterValue = filterDropdown.value;
                const searchValue = searchInput.value.trim().toLowerCase();

                filteredData = orderData.filter(order => {
                    // Filter by dropdown selection
                    const matchesFilter = filterValue === 'all' ||
                        (filterValue === 'archived' && order.archived) ||
                        order.paymentStatus === filterValue ||
                        order.orderStatus === filterValue;

                    // Filter by search text
                    const matchesSearch = order.clientName.toLowerCase().includes(searchValue) ||
                        order.service.toLowerCase().includes(searchValue);

                    return matchesFilter && matchesSearch;
                });

                // Update pagination
                const totalPages = Math.ceil(filteredData.length / recordsPerPage);
                totalPagesSpan.textContent = totalPages;
                if (currentPage > totalPages && totalPages > 0) {
                    currentPage = totalPages;
                }

                updatePageNumbers(totalPages);
                updatePaginationButtons(totalPages);

                // Display current page
                const startIndex = (currentPage - 1) * recordsPerPage;
                const endIndex = startIndex + recordsPerPage;
                const currentPageData = filteredData.slice(startIndex, endIndex);

                // Clear table
                tableBody.innerHTML = '';

                if (currentPageData.length === 0) {
                    const emptyRow = document.createElement('tr');
                    emptyRow.innerHTML = '<td colspan="5" style="text-align: center; padding: 20px;">No orders found</td>';
                    tableBody.appendChild(emptyRow);
                } else {
                    // Create rows for current page data
                    currentPageData.forEach(order => {
                        const row = document.createElement('tr');
                        if (order.archived) {
                            row.classList.add('archived');
                        }

                        row.innerHTML = `
                            <td>${order.clientName} ${order.archived ? '<span class="archived-badge">Archived</span>' : ''}</td>
                            <td>${order.service}</td>
                            <td><span class="status-badge ${order.paymentStatus}">${order.paymentStatus.toUpperCase()}</span></td>
                            <td><span class="status-badge ${order.orderStatus}">${order.orderStatus.toUpperCase()}</span></td>
                            <td>
                                <div class="action-buttons">
                                    <button class="action-btn view-btn" data-id="${order.id}">
                                        <i class="fas fa-eye"></i> View
                                    </button>
                                    <button class="action-btn complete-btn" data-id="${order.id}" ${order.orderStatus === 'complete' || order.archived ? 'disabled' : ''}>
                                        <i class="fas fa-check"></i> Complete
                                    </button>
                                    <button class="action-btn archive-btn ${order.archived ? 'restore-btn' : ''}" data-id="${order.id}">
                                        <i class="fas fa-${order.archived ? 'undo' : 'archive'}"></i> ${order.archived ? 'Restore' : 'Archive'}
                                    </button>
                                </div>
                            </td>
                        `;

                        tableBody.appendChild(row);
                    });

                    // Add event listeners for action buttons
                    addActionButtonEventListeners();
                }
            }

            // Update page number buttons
            function updatePageNumbers(totalPages) {
                pageNumbersDiv.innerHTML = '';

                // Determine range of page numbers to show
                let startPage = Math.max(1, currentPage - 2);
                let endPage = Math.min(totalPages, startPage + 4);

                if (endPage - startPage < 4) {
                    startPage = Math.max(1, endPage - 4);
                }

                // Create page number buttons
                for (let i = startPage; i <= endPage; i++) {
                    const pageBtn = document.createElement('button');
                    pageBtn.className = 'page-number' + (i === currentPage ? ' active' : '');
                    pageBtn.textContent = i;
                    pageBtn.addEventListener('click', () => {
                        currentPage = i;
                        displayData();
                    });
                    pageNumbersDiv.appendChild(pageBtn);
                }
            }

            // Update pagination buttons state
            function updatePaginationButtons(totalPages) {
                prevBtn.disabled = currentPage === 1;
                nextBtn.disabled = currentPage === totalPages || totalPages === 0;
                pageInput.value = currentPage;
            }

            // Add event listeners to action buttons
            function addActionButtonEventListeners() {
                document.querySelectorAll('.view-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const orderId = btn.getAttribute('data-id');
                        openEditModal(parseInt(orderId));
                    });
                });

                document.querySelectorAll('.complete-btn:not([disabled])').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const orderId = btn.getAttribute('data-id');
                        markOrderComplete(parseInt(orderId));
                    });
                });

                document.querySelectorAll('.archive-btn').forEach(btn => {
                    btn.addEventListener('click', () => {
                        const orderId = btn.getAttribute('data-id');
                        openArchiveConfirmation(parseInt(orderId));
                    });
                });
            }

            // Mark order as complete
            function markOrderComplete(orderId) {
                const order = orderData.find(o => o.id === orderId);
                if (order) {
                    order.orderStatus = 'complete';
                    displayData();
                    alert(`Order #${orderId} marked as complete`);
                }
            }

            // Open edit modal with order data
            function openEditModal(orderId) {
                const order = orderData.find(o => o.id === orderId);
                if (order) {
                    modalTitle.textContent = 'Edit Order';
                    orderIdInput.value = order.id;
                    document.getElementById('clientName').value = order.clientName;
                    document.getElementById('contactNumber').value = order.contactNumber || '';
                    document.getElementById('service').value = order.service;
                    document.getElementById('quantity').value = order.quantity || 1;
                    document.getElementById('notes').value = order.notes || '';
                    document.getElementById('paymentStatus').value = order.paymentStatus;
                    document.getElementById('orderStatus').value = order.orderStatus;

                    orderModal.style.display = 'flex';
                }
            }

            // Open add order modal
            function openAddModal() {
                modalTitle.textContent = 'Add New Order';
                orderForm.reset();
                orderIdInput.value = '';
                orderModal.style.display = 'flex';
            }

            // Open archive confirmation modal
            function openArchiveConfirmation(orderId) {
                const order = orderData.find(o => o.id === orderId);
                if (order) {
                    if (order.archived) {
                        archiveModalTitle.textContent = 'Confirm Restore';
                        archiveConfirmText.textContent = 'Are you sure you want to restore this order?';
                        confirmArchiveBtn.textContent = 'Restore';
                    } else {
                        archiveModalTitle.textContent = 'Confirm Archive';
                        archiveConfirmText.textContent = 'Are you sure you want to archive this order? Archived orders can be restored later.';
                        confirmArchiveBtn.textContent = 'Archive';
                    }

                    confirmArchiveBtn.setAttribute('data-id', orderId);
                    confirmModal.style.display = 'flex';
                }
            }

            // Toggle archive status
            function toggleArchiveStatus() {
                const orderId = parseInt(confirmArchiveBtn.getAttribute('data-id'));
                const order = orderData.find(o => o.id === orderId);

                if (order) {
                    order.archived = !order.archived;
                    displayData();
                    closeModals();
                }
            }

            // Close modals
            function closeModals() {
                orderModal.style.display = 'none';
                confirmModal.style.display = 'none';
            }

            // Save order data
            function saveOrder(event) {
                event.preventDefault();

                // Get form values
                const orderId = orderIdInput.value;
                const isEditMode = orderId !== '';

                const newOrder = {
                    id: isEditMode ? parseInt(orderId) : orderData.length + 1,
                    clientName: document.getElementById('clientName').value,
                    contactNumber: document.getElementById('contactNumber').value,
                    service: document.getElementById('service').value,
                    quantity: document.getElementById('quantity').value,
                    notes: document.getElementById('notes').value,
                    paymentStatus: document.getElementById('paymentStatus').value,
                    orderStatus: document.getElementById('orderStatus').value,
                    archived: false
                };

                if (isEditMode) {
                    // Update existing order
                    const index = orderData.findIndex(o => o.id === parseInt(orderId));
                    if (index !== -1) {
                        // Preserve the archived status when editing
                        newOrder.archived = orderData[index].archived;
                        orderData[index] = newOrder;
                    }
                } else {
                    // Add new order
                    orderData.push(newOrder);
                }

                closeModals();
                displayData();
            }

            // Set up all event listeners
            function setupEventListeners() {
                // Filter and search
                filterDropdown.addEventListener('change', () => {
                    currentPage = 1;
                    displayData();
                });

                searchInput.addEventListener('input', () => {
                    currentPage = 1;
                    displayData();
                });

                // Pagination buttons
                prevBtn.addEventListener('click', () => {
                    if (currentPage > 1) {
                        currentPage--;
                        displayData();
                    }
                });

                nextBtn.addEventListener('click', () => {
                    const totalPages = Math.ceil(filteredData.length / recordsPerPage);
                    if (currentPage < totalPages) {
                        currentPage++;
                        displayData();
                    }
                });

                pageInput.addEventListener('change', () => {
                    const totalPages = Math.ceil(filteredData.length / recordsPerPage);
                    const pageNum = parseInt(pageInput.value);

                    if (!isNaN(pageNum) && pageNum >= 1 && pageNum <= totalPages) {
                        currentPage = pageNum;
                        displayData();
                    } else {
                        pageInput.value = currentPage;
                    }
                });

                // Modal controls
                addOrderBtn.addEventListener('click', openAddModal);
                modalCloseBtns.forEach(btn => {
                    btn.addEventListener('click', closeModals);
                });
                cancelBtn.addEventListener('click', closeModals);
                cancelArchiveBtn.addEventListener('click', closeModals);
                confirmArchiveBtn.addEventListener('click', toggleArchiveStatus);
                orderForm.addEventListener('submit', saveOrder);

                // Close modal when clicking outside
                window.addEventListener('click', function(event) {
                    if (event.target === orderModal || event.target === confirmModal) {
                        closeModals();
                    }
                });
            }

            // Initialize the application
            init();
        });
    </script>
    <?php include "includes/script.php"; ?>
</body>

</html>