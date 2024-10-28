<?php
session_start();

// Include the database and controller files with the correct paths
require_once __DIR__ . '/../src/Config/database.php';
require_once __DIR__ . '/../src/Controllers/BillController.php';

use Src\Controllers\BillController; // Use the correct namespace

// Check if the user is logged in and if the user has the Reviewer role
if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Reviewer') {
    header('Location: login.php');
    exit();
}

// Create a connection to the BillController
$billController = new BillController($connection);
$bills = $billController->getAllBills(); // Fetch all bills for the reviewer to review
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reviewer Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col items-center p-8 h-screen w-full">

    <div class="flex items-center justify-between w-full mb-6">
        <h1 class="text-3xl font-bold">Reviewer Dashboard</h1>
        <div class="flex items-center gap-4">
            <p class="text-lg">Welcome, <span class="font-semibold"><?php echo htmlspecialchars($_SESSION['username']); ?></span>!</p>
            <a href="process_logout.php" class="text-red-500 hover:text-red-700">Logout</a>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mb-4">All Bills</h2>
    <div class="overflow-x-auto">
        <table class="min-w-full bg-white border border-gray-300">
            <thead>
                <tr class="bg-gray-200 text-gray-600">
                    <th class="py-2 px-4 border">Title</th>
                    <th class="py-2 px-4 border">Description</th>
                    <th class="py-2 px-4 border">Status</th>
                    <th class="py-2 px-4 border">Actions</th>
                </tr>
            </thead>
            <tbody>
    <?php foreach ($bills as $bill): ?>
        <tr class="border-b hover:bg-gray-100">
            <td class="py-2 px-4 border"><?php echo htmlspecialchars($bill['title']); ?></td>
            <td class="py-2 px-4 border"><?php echo htmlspecialchars($bill['description']); ?></td>
            <td class="py-2 px-4 border"><?php echo htmlspecialchars($bill['status']); ?></td>
            <td class="py-2 px-4 border">
    <a href="suggest_amendment.php?id=<?php echo $bill['bill_id']; ?>" class="text-green-500 hover:text-green-700 mr-2 font-semibold">Amend</a>
    <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>" class="text-blue-500 hover:text-blue-700 mr-2 font-semibold">View</a>
   
    <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>" style="color: #f97316;" class="hover:text-orange-700 font-semibold mr-2">Edit</a>
</td>

        </tr>
    <?php endforeach; ?>
</tbody>


        </table>
    </div>

</body>

</html>
