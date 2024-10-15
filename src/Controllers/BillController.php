<?php

class BillController {
    private $billRepository;

    public function __construct($billRepository) {
        $this->billRepository = $billRepository;
    }

    // Fetch all bills
    public function listBills() {
        return $this->billRepository->getAllBills();
    }

    // Fetch a specific bill by its ID
    public function viewBill($bill_id) {
        return $this->billRepository->getBillById($bill_id);
    }

    // Update the status of a bill to 'Voting' (initiate voting)
    public function initiateVoting($bill_id) {
        $bill = $this->billRepository->getBillById($bill_id);
        if ($bill && $bill['status'] === 'Under Review') {
            // Change the status to 'Voting'
            $this->billRepository->updateBillStatus($bill_id, 'Voting');
            echo "Voting initiated for Bill ID $bill_id.";
        } else {
            echo "Invalid action. Only bills under review can be moved to voting.";
        }
    }
}
?>
