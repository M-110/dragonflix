<?php

use includes\models\Copy;
use includes\models\Customer;
use includes\models\Rental;
use includes\models\Transaction;

function createRental(PDO $conn, int $movieId, float $rentalFee): string
{
    $customerId = $_REQUEST['customerId'];
    $conn->beginTransaction();
    $error = "";
    try {
        $status = Customer::getMovieStatusesByMovieAndCustomerId($customerId, $movieId, $conn);
        if ($status['IS_RENTED']) {
            throw new Exception("You already have a copy of this movie rented.");
        }
        $copyId = Copy::getAvailableCopyByMovieId($movieId, $conn);
        $rentalId = Rental::createRental($copyId, $customerId, $conn);
        Transaction::createTransaction($rentalId, $rentalFee, 0, $conn);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        $error = $e->getMessage();
    }
    return $error;
}

function updateRentalReturn(int $rentalId, PDO $conn): string
{
    $conn->beginTransaction();
    $error = "";
    try {
        Rental::updateRentalReturnDate($rentalId, $conn);
        $conn->commit();
    } catch (Exception $e) {
        $conn->rollBack();
        $error = "Return Failed: {$e->getMessage()}";
    }
    return $error;
}
