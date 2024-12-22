<?php

namespace includes\models;

use PDO;

class Review
{
    /**
     * Gets all reviews for a movie and returns stars, review content, date added, and the customer's id and their
     * first and last name.
     *
     * @param int $movieId
     * @param PDO $conn
     * @return array[] 'REVIEW_STARS', 'REVIEW_CONTENT', 'REVIEW_DATE_ADDED', 'CUSTOMER_ID', 'CUSTOMER_FIRST_NAME',
     *                 'CUSTOMER_LAST_NAME'
     */
    public static function getReviewsByMovieId(int $movieId, PDO $conn): array
    {
        $query = "SELECT MOVIE_ID,
                         REVIEW_STARS, 
                         REVIEW_CONTENT, 
                         REVIEW_DATE_ADDED, 
                         c.CUSTOMER_ID, 
                         CUSTOMER_FIRST_NAME, 
                         CUSTOMER_LAST_NAME
                  FROM REVIEW r
                           JOIN CUSTOMER c ON r.CUSTOMER_ID = c.CUSTOMER_ID AND REVIEW_CONTENT != ''
                  WHERE r.MOVIE_ID = :movieId
                  ORDER BY REVIEW_DATE_ADDED DESC;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error in getReviewsByMovieId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Add a new review to the database.
     *
     * @param int $customerId
     * @param int $movieId
     * @param int $stars
     * @param string $review
     * @param PDO $conn
     * @return bool
     */
    public static function createReview(int $customerId, int $movieId, int $stars, string $review, PDO $conn): bool
    {
        $query = "INSERT INTO REVIEW (CUSTOMER_ID, MOVIE_ID, REVIEW_STARS, REVIEW_CONTENT, REVIEW_DATE_ADDED)
                  VALUES (:customerId, :movieId, :stars, :review, DATE('now'));";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            $stmt->bindValue(':stars', $stars, PDO::PARAM_INT);
            $stmt->bindValue(':review', $review, PDO::PARAM_STR);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("SQL Error in createReview: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Delete the review for a movie.
     *
     * @param int $customerId
     * @param int $movieId
     * @param PDO $conn
     * @return bool
     */
    public static function deleteReview(int $customerId, int $movieId, PDO $conn): bool
    {
        $query = "DELETE FROM REVIEW
                  WHERE CUSTOMER_ID = :customerId
                    AND MOVIE_ID = :movieId;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("SQL Error in deleteReview: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Edit a review for a movie.
     *
     * @param int $customerId
     * @param int $movieId
     * @param int $stars
     * @param string $review
     * @param PDO $conn
     * @return bool
     */
    public static function editReview(int $customerId, int $movieId, int $stars, string $review, PDO $conn): bool
    {
        $query = "UPDATE REVIEW
                  SET REVIEW_STARS = :stars,
                      REVIEW_CONTENT = :review
                  WHERE CUSTOMER_ID = :customerId
                    AND MOVIE_ID = :movieId;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':stars', $stars, PDO::PARAM_INT);
            $stmt->bindValue(':review', $review, PDO::PARAM_STR);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("SQL Error in editReview: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get the rating overview for a movie.
     *
     * @param int $movieId
     * @param PDO $conn
     * @return array
     */
    public static function getRatingOverview(int $movieId, PDO $conn): array
    {
        $query = "SELECT
                      AVG(r.REVIEW_STARS) AS AVERAGE_STARS,
                      COUNT(r.REVIEW_STARS) AS RATING_COUNT,
                      SUM(CASE WHEN r.REVIEW_STARS = 5 THEN 1 ELSE 0 END) AS FIVE_STAR_COUNT,
                      SUM(CASE WHEN r.REVIEW_STARS = 4 THEN 1 ELSE 0 END) AS FOUR_STAR_COUNT,
                      SUM(CASE WHEN r.REVIEW_STARS = 3 THEN 1 ELSE 0 END) AS THREE_STAR_COUNT,
                      SUM(CASE WHEN r.REVIEW_STARS = 2 THEN 1 ELSE 0 END) AS TWO_STAR_COUNT,
                      SUM(CASE WHEN r.REVIEW_STARS = 1 THEN 1 ELSE 0 END) AS ONE_STAR_COUNT,
                      SUM(CASE WHEN r.REVIEW_CONTENT != '' THEN 1 ELSE 0 END) AS REVIEW_COUNT
                  FROM REVIEW r
                  WHERE r.MOVIE_ID = :movieId
                  GROUP BY r.MOVIE_ID;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetch(PDO::FETCH_ASSOC) ?: [];
        } catch (PDOException $e) {
            error_log("SQL Error in getRatingOverview: " . $e->getMessage());
            return [];
        }
    }
}
