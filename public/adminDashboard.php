<?php
session_start();

// Include the database and controller files with the correct paths
require_once __DIR__ . '/../src/Config/database.php';
require_once __DIR__ . '/../src/Controllers/BillController.php';

use Src\Controllers\BillController; // Make sure the namespace is correct

// Check if the user is logged in and if the user has the Administrator role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Administrator') {
    header('Location: login.php');
    exit();
}

// Create a connection to the BillController
$billController = new BillController($connection);
$bills = $billController->getAllBills(); // Fetch all bills for the admin to review

// Handle delete request
if (isset($_GET['delete_id'])) {
    $billController->deleteBill($_GET['delete_id']);
    header('Location: adminDashboard.php'); // Redirect to refresh the page after deletion
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col items-center p-8 h-screen w-full">

    <div class="flex items-center justify-between w-full mb-6">
        <h1 class="text-3xl font-bold">Admin Dashboard</h1>
        <div class="flex items-center gap-4">
            <p class="text-lg">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <a href="process_logout.php" class="text-red-500 hover:text-red-700">Logout</a>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mt-6">All Bills</h2>
    <a href="view_amendments.php" class="text-blue-500 hover:text-blue-700">View Pending Amendments</a>

    <table class="min-w-full mt-4 border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-2 px-4 border-b">Title</th>
                <th class="py-2 px-4 border-b">Description</th>
                <th class="py-2 px-4 border-b">Status</th>
                <th class="py-2 px-4 border-b">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bills as $bill): ?>
                <tr class="hover:bg-gray-100">
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($bill['title']); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($bill['description']); ?></td>
                    <td class="py-2 px-4 border-b"><?php echo htmlspecialchars($bill['status']); ?></td>
                    <td class="py-2 px-4 border-b">
                        <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>" class="text-blue-500 hover:text-blue-700">View</a>
                        <a href="adminDashboard.php?delete_id=<?php echo $bill['bill_id']; ?>"
                            class="text-red-500 hover:text-red-700"
                            onclick="return confirm('Are you sure you want to delete this bill?');">Delete</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>

</body>

</html>