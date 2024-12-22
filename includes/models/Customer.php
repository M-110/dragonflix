<?php

namespace includes\models;

use PDO;

class Customer
{
    /**
     * @param int $customerId
     * @param int $movieId
     * @param PDO $conn
     * @return array 'IS_RENTED', 'IS_IN_WATCH_LIST'
     */
    public static function getMovieStatusesByMovieAndCustomerId(int $customerId, int $movieId, PDO $conn): array
    {
        $query = "SELECT
                    SUM(CASE WHEN r.RENTAL_RETURNED IS NULL AND r.COPY_ID IS NOT NULL THEN 1 ELSE 0 END) AS IS_RENTED,
                    COUNT(w.CUSTOMER_ID) AS IS_IN_WATCH_LIST
                  FROM COPY c
                  LEFT JOIN RENTAL r ON c.COPY_ID = r.COPY_ID AND r.CUSTOMER_ID = :customerId
                  LEFT JOIN WATCH_LIST w ON c.MOVIE_ID = w.MOVIE_ID AND w.CUSTOMER_ID = :customerId
                  WHERE c.MOVIE_ID = :movieId;";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * @param int $customerId
     * @param PDO $conn
     * @return array
     */
    public static function getCustomerById(int $customerId, PDO $conn): array
    {
        $query = "SELECT * FROM CUSTOMER WHERE CUSTOMER_ID = :customerId;";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();

        return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
    }

    /**
     * @param PDO $conn
     * @return array
     */
    public static function getAllCustomers(PDO $conn): array
    {
        $query = "SELECT * FROM CUSTOMER;";

        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
