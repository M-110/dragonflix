<?php

namespace includes\models;

use PDO;
use PDOException;

class WatchList
{
    /**
     * Returns an array of movies on a user's watch list including the movie id, title, date it was added to the
     * watch list, and the movie poster URL.
     *
     * @param int $customerId
     * @param PDO $conn
     * @return array[] 'MOVIE_ID', 'MOVIE_TITLE', 'WATCH_LIST_DATE_ADDED', 'MOVIE_POSTER'
     */
    public static function getMoviesOnWatchListByCustomerId(int $customerId, PDO $conn): array
    {
        $query = "SELECT m.MOVIE_ID, m.MOVIE_TITLE, wl.WATCH_LIST_DATE_ADDED, m.MOVIE_POSTER
                  FROM MOVIE m
                  JOIN WATCH_LIST wl ON m.MOVIE_ID = wl.MOVIE_ID
                  JOIN CUSTOMER c ON wl.CUSTOMER_ID = c.CUSTOMER_ID
                  WHERE c.CUSTOMER_ID = :customerId;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->execute();
            return $stmt->fetchAll(PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("SQL Error in getMoviesOnWatchListByCustomerId: " . $e->getMessage());
            return [];
        }
    }

    /**
     * Creates a watch list entry for a user/movie combination
     *
     * @param int $customerId
     * @param int $movieId
     * @param PDO $conn
     */
    public static function createWatchListEntryForUser(int $customerId, int $movieId, PDO $conn): void
    {
        $query = "INSERT INTO WATCH_LIST (CUSTOMER_ID, MOVIE_ID, WATCH_LIST_DATE_ADDED)
                  VALUES (:customerId, :movieId, DATE('now'));";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            $stmt->execute();
        } catch (PDOException $e) {
            error_log("SQL Error in createWatchListEntryForUser: " . $e->getMessage());
        }
    }

    /**
     * Deletes a watch list entry for a user/movie combination
     *
     * @param int $customerId
     * @param int $movieId
     * @param PDO $conn
     * @return bool true if the query was successful, false otherwise
     */
    public static function deleteWatchListEntryForUser(int $customerId, int $movieId, PDO $conn): bool
    {
        $query = "DELETE FROM WATCH_LIST
                  WHERE CUSTOMER_ID = :customerId
                    AND MOVIE_ID = :movieId;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            return $stmt->execute();
        } catch (PDOException $e) {
            error_log("SQL Error in deleteWatchListEntryForUser: " . $e->getMessage());
            return false;
        }
    }
}
