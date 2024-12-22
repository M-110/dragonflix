<?php

namespace includes\models;

use PDO;

class Rental
{
    /**
     * Inserts a new rental into the database and returns the rental id.
     *
     * @param int $copyId
     * @param int $customerId
     * @param PDO $conn
     * @return int rentalId
     */
    public static function createRental(int $copyId, int $customerId, PDO $conn): int
    {
        $query = "INSERT INTO RENTAL (COPY_ID, CUSTOMER_ID, RENTAL_DATE)
                  VALUES (:copyId, :customerId, DATE('now'));";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':copyId', $copyId, PDO::PARAM_INT);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->execute();
            return (int) $conn->lastInsertId();
        } catch (PDOException $e) {
            error_log("SQL Error in createRental: " . $e->getMessage());
            throw new \Exception("Failed to create rental.");
        }
    }

    /**
     * Updates the database by setting the return date of the RENTAL to the current date.
     *
     * @param int $rentalId
     * @param PDO $conn
     * @return void
     * @throws \Exception if the update fails
     */
    public static function updateRentalReturnDate(int $rentalId, PDO $conn): void
    {
        $query = "UPDATE RENTAL SET RENTAL_RETURNED = DATE('now') WHERE RENTAL_ID = :rentalId;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':rentalId', $rentalId, PDO::PARAM_INT);
            if (!$stmt->execute()) {
                throw new \Exception("Rental return failed.");
            }
        } catch (PDOException $e) {
            error_log("SQL Error in updateRentalReturnDate: " . $e->getMessage());
            throw new \Exception("Failed to update rental return date.");
        }
    }

    /**
     * Gets the movie title, rental id, and rental date for a movie rented by a customer.
     *
     * @param int $movieId
     * @param int $customerId
     * @param PDO $conn
     * @return array|null 'MOVIE_TITLE', 'RENTAL_ID', 'RENTAL_DATE', 'DUE_DATE', 'LATE_FEE'
     */
    public static function getRentalByMovieIdAndCustomerId(int $movieId, int $customerId, PDO $conn): ?array
    {
        $query = "SELECT m.MOVIE_TITLE, m.MOVIE_POSTER, r.RENTAL_ID, r.RENTAL_DATE, 
                         DATE(r.RENTAL_DATE, '+10 days') AS DUE_DATE, 
                         MAX(0, JULIANDAY('now') - JULIANDAY(DATE(r.RENTAL_DATE, '+10 days'))) AS LATE_FEE
                  FROM CUSTOMER c
                  JOIN RENTAL r ON c.CUSTOMER_ID = r.CUSTOMER_ID
                  JOIN COPY co ON r.COPY_ID = co.COPY_ID
                  JOIN MOVIE m ON m.MOVIE_ID = co.MOVIE_ID
                  WHERE c.CUSTOMER_ID = :customerId AND m.MOVIE_ID = :movieId AND r.RENTAL_RETURNED IS NULL;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: null;
        } catch (PDOException $e) {
            error_log("SQL Error in getRentalByMovieIdAndCustomerId: " . $e->getMessage());
            throw new \Exception("Invalid return request: you do not have this movie rented.");
        }
    }

    /**
     * Returns all late rentals for a customer.
     *
     * @param int $customerId
     * @param PDO $conn
     * @return array[] 'MOVIE_ID', 'MOVIE_TITLE', 'RENTAL_DATE', 'DUE_DATE', 'DAYS_LATE'
     */
    public static function getLateRentalsByCustomerId(int $customerId, PDO $conn): array
    {
        $query = "SELECT m.MOVIE_ID, m.MOVIE_TITLE, m.MOVIE_POSTER, r.RENTAL_DATE,
                         DATE(r.RENTAL_DATE, '+10 days') AS DUE_DATE,
                         JULIANDAY('now') - JULIANDAY(DATE(r.RENTAL_DATE, '+10 days')) AS DAYS_LATE
                  FROM MOVIE m
                  JOIN COPY c ON m.MOVIE_ID = c.MOVIE_ID
                  JOIN RENTAL r ON c.COPY_ID = r.COPY_ID
                  JOIN CUSTOMER co ON r.CUSTOMER_ID = co.CUSTOMER_ID
                  WHERE co.CUSTOMER_ID = :customerId
                    AND r.RENTAL_RETURNED IS NULL
                    AND JULIANDAY('now') > JULIANDAY(DATE(r.RENTAL_DATE, '+10 days'))
                  ORDER BY r.RENTAL_DATE;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error in getLateRentalsByCustomerId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Returns all non-late rentals for a customer.
     *
     * @param int $customerId
     * @param PDO $conn
     * @return array[] 'MOVIE_ID', 'MOVIE_TITLE', 'RENTAL_DATE', 'DUE_DATE', 'DAYS_LEFT'
     */
    public static function getNonLateRentalsByCustomerId(int $customerId, PDO $conn): array
    {
        $query = "SELECT m.MOVIE_ID, m.MOVIE_TITLE, m.MOVIE_POSTER, r.RENTAL_DATE,
                         DATE(r.RENTAL_DATE, '+10 days') AS DUE_DATE,
                         JULIANDAY(DATE(r.RENTAL_DATE, '+10 days')) - JULIANDAY('now') AS DAYS_LEFT
                  FROM MOVIE m
                  JOIN COPY c ON m.MOVIE_ID = c.MOVIE_ID
                  JOIN RENTAL r ON c.COPY_ID = r.COPY_ID
                  JOIN CUSTOMER co ON r.CUSTOMER_ID = co.CUSTOMER_ID
                  WHERE co.CUSTOMER_ID = :customerId
                    AND r.RENTAL_RETURNED IS NULL
                    AND JULIANDAY('now') <= JULIANDAY(DATE(r.RENTAL_DATE, '+10 days'))
                  ORDER BY r.RENTAL_DATE;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error in getNonLateRentalsByCustomerId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Returns all past rentals for a customer.
     *
     * @param int $customerId
     * @param PDO $conn
     * @return array[] 'MOVIE_ID', 'MOVIE_TITLE', 'RENTAL_DATE', 'RENTAL_RETURNED', 'DAYS_LATE'
     */
    public static function getPastRentalsByCustomerId(int $customerId, PDO $conn): array
    {
        $query = "SELECT m.MOVIE_ID, m.MOVIE_TITLE, m.MOVIE_POSTER, r.RENTAL_DATE, r.RENTAL_RETURNED,
                         MAX(0, JULIANDAY(r.RENTAL_RETURNED) - JULIANDAY(DATE(r.RENTAL_DATE, '+10 days'))) AS DAYS_LATE
                  FROM MOVIE m
                  JOIN COPY c ON m.MOVIE_ID = c.MOVIE_ID
                  JOIN RENTAL r ON c.COPY_ID = r.COPY_ID
                  JOIN CUSTOMER co ON r.CUSTOMER_ID = co.CUSTOMER_ID
                  WHERE co.CUSTOMER_ID = :customerId
                    AND r.RENTAL_RETURNED IS NOT NULL
                  ORDER BY r.RENTAL_RETURNED DESC;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error in getPastRentalsByCustomerId: " . $e->getMessage());
            return [];
        }
    }
}
