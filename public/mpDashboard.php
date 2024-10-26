<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member of Parliament') {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);
$bills = $billController->getBillsByAuthor($_SESSION['user_id']);
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>MP Dashboard</title>
    <!-- Tailwind CSS CDN -->
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
</head>

<body class="bg-gray-100 flex flex-col items-center p-8 h-screen w-full">

    <div class="flex items-center justify-between w-full mb-6">
        <h1 class="text-3xl font-bold">MP Dashboard</h1>
        <div class="flex items-center gap-4">
            <p class="text-lg">Welcome, <?php echo htmlspecialchars($_SESSION['username']); ?>!</p>
            <a href="process_logout.php" class="text-red-500 hover:text-red-700">Logout</a>
        </div>
    </div>

    <h2 class="text-2xl font-semibold mt-6 mb-4">Your Bills</h2>
    <div class="mb-4">
        <a href="view_amendments.php" class="text-blue-500 hover:text-blue-700 mr-4">View Pending Amendments</a>
        <a href="create_bill.php" class="text-blue-500 hover:text-blue-700">Create New Bill</a>
    </div>

    <table class="min-w-full border border-gray-300">
        <thead>
            <tr class="bg-gray-200">
                <th class="py-2 px-4 border border-gray-300">Title</th>
                <th class="py-2 px-4 border border-gray-300">Description</th>
                <th class="py-2 px-4 border border-gray-300">Status</th>
                <th class="py-2 px-4 border border-gray-300">Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php foreach ($bills as $bill): ?>
                <tr class="bg-white hover:bg-gray-100">
                    <td class="py-2 px-4 border border-gray-300"><?php echo htmlspecialchars($bill['title']); ?></td>
                    <td class="py-2 px-4 border border-gray-300"><?php echo htmlspecialchars($bill['description']); ?></td>
                    <td class="py-2 px-4 border border-gray-300"><?php echo htmlspecialchars($bill['status']); ?></td>
                    <td class="py-2 px-4 border border-gray-300">
                        <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>" class="text-blue-500 hover:text-blue-700">Edit</a>
                        <a href="view_bill.php?id=<?php echo $bill['bill_id']; ?>" class="text-blue-500 hover:text-blue-700">View</a>
                    </td>
                </tr>
            <?php endforeach; ?>
        </tbody>
    </table>
</body>

</html>