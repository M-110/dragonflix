<?php

namespace includes\models;

use PDO;

class Transaction
{
    /**
     * Returns all transactions including the date, movie title, customer name, amount, and whether the transaction
     * was for a late fee.
     *
     * @param PDO $conn
     * @return array[] 'TRANSACTION_DATE', 'MOVIE_TITLE', 'CUSTOMER_FIRST_NAME', 'CUSTOMER_LAST_NAME', 'TRANSACTION_AMOUNT',
     *                  'TRANSACTION_LATE_FEE'
     */
    public static function getTransactionHistory(PDO $conn): array
    {
        $query = "SELECT 
                    TRANSACTION_DATE,
                    MOVIE_TITLE,
                    CUSTOMER_FIRST_NAME,
                    CUSTOMER_LAST_NAME,
                    TRANSACTION_AMOUNT,
                    TRANSACTION_LATE_FEE
                  FROM 'TRANSACTION' t
                  JOIN RENTAL r ON t.RENTAL_ID = r.RENTAL_ID
                  JOIN CUSTOMER c ON r.CUSTOMER_ID = c.CUSTOMER_ID
                  JOIN COPY co ON r.COPY_ID = co.COPY_ID
                  JOIN MOVIE m ON co.MOVIE_ID = m.MOVIE_ID
                  ORDER BY TRANSACTION_DATE;";

        try {
            $stmt = $conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error in getTransactionHistory: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get the total revenue from all transactions.
     *
     * @param PDO $conn
     * @return float
     */
    public static function getTotalRevenue(PDO $conn): float
    {
        $query = "SELECT SUM(TRANSACTION_AMOUNT) AS TOTAL_REVENUE FROM 'TRANSACTION';";

        try {
            $stmt = $conn->query($query);
            $row = $stmt->fetch(PDO::FETCH_ASSOC);
            return (float) $row['TOTAL_REVENUE'];
        } catch (PDOException $e) {
            error_log("SQL Error in getTotalRevenue: " . $e->getMessage());
            return 0.0;
        }
    }

    /**
     * Get all rentals that are currently late including the customer's name, movie title, rental date, and number of
     * days late.
     *
     * @param PDO $conn
     * @return array[] 'CUSTOMER_FIRST_NAME', 'CUSTOMER_LAST_NAME', 'MOVIE_TITLE', 'RENTAL_DATE', 'DAYS_LATE'
     */
    public static function getCurrentLateRentals(PDO $conn): array
    {
        $query = "SELECT 
                    CUSTOMER_FIRST_NAME,
                    CUSTOMER_LAST_NAME,
                    MOVIE_TITLE,
                    RENTAL_DATE,
                    CAST(JULIANDAY('now') - JULIANDAY(RENTAL_DATE) - 10 AS INTEGER) AS DAYS_LATE
                  FROM RENTAL r
                  JOIN CUSTOMER c ON r.CUSTOMER_ID = c.CUSTOMER_ID
                  JOIN COPY co ON r.COPY_ID = co.COPY_ID
                  JOIN MOVIE m ON co.MOVIE_ID = m.MOVIE_ID
                  WHERE RENTAL_RETURNED IS NULL
                    AND JULIANDAY('now') > JULIANDAY(RENTAL_DATE, '+10 days')
                  ORDER BY RENTAL_DATE;";

        try {
            $stmt = $conn->query($query);
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error in getCurrentLateRentals: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Get the amount of rentals by month.
     *
     * @param PDO $conn
     * @return array[]
     */
    public static function getRentalCountByMonth(PDO $conn): array
    {
        $months = [];
        $rentals = [];

        $query = "SELECT 
                    STRFTIME('%Y-%m', RENTAL_DATE) AS month_year,
                    COUNT(*) AS rental_count 
                  FROM RENTAL
                  GROUP BY month_year
                  ORDER BY month_year;";

        try {
            $stmt = $conn->query($query);
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $months[] = $row['month_year'];
                $rentals[] = $row['rental_count'];
            }
            return [$months, $rentals];
        } catch (PDOException $e) {
            error_log("SQL Error in getRentalCountByMonth: " . $e->getMessage());
            return [$months, $rentals];
        }
    }

    /**
     * Inserts a new transaction into the database and returns the transaction id.
     *
     * @param int $rentalId
     * @param float $amount
     * @param int $lateFee
     * @param PDO $conn
     * @return int transactionId
     */
    public static function createTransaction(int $rentalId, float $amount, int $lateFee, PDO $conn): int
    {
        $query = "INSERT INTO 'TRANSACTION' (RENTAL_ID, TRANSACTION_DATE, TRANSACTION_AMOUNT, TRANSACTION_LATE_FEE)
                  VALUES (:rentalId, DATE('now'), :amount, :lateFee);";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':rentalId', $rentalId, PDO::PARAM_INT);
            $stmt->bindValue(':amount', $amount, PDO::PARAM_STR);
            $stmt->bindValue(':lateFee', $lateFee, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("SQL Error in createTransaction: " . $e->getMessage());
            return 0;
        }
    }
}
