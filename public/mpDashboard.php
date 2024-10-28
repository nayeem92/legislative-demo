<?php
session_start();
require_once '../src/Config/database.php';
require_once '../src/Controllers/BillController.php';

if (!isset($_SESSION['user_id']) || $_SESSION['role'] !== 'Member of Parliament') {
    header('Location: login.php');
    exit();
}

$billController = new \Src\Controllers\BillController($connection);
$bills = $billController->getAllBills(); // Retrieve all bills to display in the dashboard

include 'header.php';
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

<body class="bg-gray-100 min-h-screen">

    <div class="container mx-auto px-6 py-8">
        <h2 class="text-3xl font-semibold text-center mb-6">All Bills for Voting</h2>
        <div class="flex justify-center space-x-6 mb-8">
            <a href="view_amendments.php" class="text-blue-600 hover:text-blue-800 text-lg">View Pending Amendments</a>
            <a href="create_bill.php" class="text-blue-600 hover:text-blue-800 text-lg">Create New Bill</a>
        </div>

        <div class="bg-white rounded-lg shadow-lg overflow-hidden">
            <table class="min-w-full bg-white">
                <thead class="bg-gray-800 text-white">
                    <tr>
                        <th class="text-left py-3 px-6 uppercase font-semibold text-sm">Title</th>
                        <th class="text-left py-3 px-6 uppercase font-semibold text-sm">Description</th>
                        <th class="text-center py-3 px-6 uppercase font-semibold text-sm">Status</th>
                        <th class="text-center py-3 px-6 uppercase font-semibold text-sm">Actions</th>
                        <th class="text-center py-3 px-6 uppercase font-semibold text-sm">Export</th>
                    </tr>
                </thead>
                <tbody class="text-gray-700">
                    <?php foreach ($bills as $bill): ?>
                        <tr class="hover:bg-gray-100">
                            <td class="text-left py-4 px-6"><?php echo htmlspecialchars($bill['title']); ?></td>
                            <td class="text-left py-4 px-6"><?php echo htmlspecialchars($bill['description']); ?></td>
                            <td class="text-center py-4 px-6">
                                <span class="inline-block px-3 py-1 rounded-full text-sm font-medium
                                    <?php echo ($bill['status'] === 'Draft' || $bill['status'] === 'Under Review') ? 'bg-yellow-200 text-yellow-800' : 'bg-green-200 text-green-800'; ?>">
                                    <?php echo htmlspecialchars($bill['status']); ?>
                                </span>
                            </td>
                            <td class="text-center py-4 px-6">
                                <?php if ($bill['status'] === 'Draft' || $bill['status'] === 'Under Review'): ?>
                                    <?php if ($bill['author_id'] == $_SESSION['user_id']): ?>
                                        <a href="edit_bill.php?id=<?php echo $bill['bill_id']; ?>" 
                                           class="inline-block bg-yellow-500 text-white px-4 py-2 rounded hover:bg-yellow-600 transition">
                                           Edit
                                        </a>
                                    <?php endif; ?>
                                <?php endif; ?>
                                <?php if ($bill['status'] === 'Voting' || $bill['status'] === 'Voting in Progress'): ?>
                                    <a href="vote_bill.php?id=<?php echo $bill['bill_id']; ?>" 
                                    class="inline-block bg-green-500 text-white px-4 py-2 rounded hover:bg-green-600 transition whitespace-nowrap">
                                       Vote Now
                                    </a>
                                <?php endif; ?>
                            </td>
                            <td class="text-center py-4 px-6">
                                <a href="export_to_xml.php?bill_id=<?php echo $bill['bill_id']; ?>" 
                                class="bg-indigo-500 text-white px-3 py-1 rounded-md hover:bg-indigo-600 transition duration-200">
                                   Download
                                </a>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
    </div>

</body>
</html>
<?php
// Include the footer
include 'footer.php';
?>
