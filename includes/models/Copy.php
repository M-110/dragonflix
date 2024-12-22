<?php

namespace includes\models;

use PDO;

class Copy
{
    /**
     * Returns the copy id of the first available copy of a movie.
     *
     * @param int $movieId
     * @param PDO $conn
     * @return int
     * @throws \Exception if no copies are available
     */
    public static function getAvailableCopyByMovieId(int $movieId, PDO $conn): int
    {
        $query = "SELECT c.COPY_ID
                  FROM COPY c
                  WHERE c.MOVIE_ID = :movieId
                  AND NOT EXISTS (
                      SELECT 1
                      FROM RENTAL r
                      WHERE r.COPY_ID = c.COPY_ID
                      AND r.RENTAL_RETURNED IS NULL
                  )
                  LIMIT 1;";

        try {
            $stmt = $conn->prepare($query);
            $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
            $stmt->execute();
            $row = $stmt->fetch(PDO::FETCH_ASSOC);

            if ($row === false) {
                throw new \Exception("No copies available");
            }

            return (int) $row['COPY_ID'];
        } catch (PDOException $e) {
            error_log("SQL Error in getAvailableCopyByMovieId: " . $e->getMessage());
            throw new \Exception("Failed to fetch available copy");
        }
    }
}
