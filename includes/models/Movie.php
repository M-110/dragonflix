<?php namespace includes\models;

use PDO;

class Movie
{
    /**
     * Returns the title of a movie by its id.
     *
     * @param int $movieId
     * @param PDO $conn
     * @return string
     */
    public static function getTitleByMovieId(int $movieId, PDO $conn): string
    {
        $query = "SELECT MOVIE_TITLE FROM MOVIE WHERE MOVIE_ID = :movieId;";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['MOVIE_TITLE'];
    }

    /**
     * Returns the top ten most rented videos along with their id, title, release date, maturity rating, runtime length,
     * number of times rented, average review stars, comma separated genre ids, comma separated genre names, whether it
     * is on the given user's watchlist, and whether it is currently rented by the given user.
     *
     * @param int $customerId
     * @param PDO $conn
     * @return array[] 'MOVIE_ID', 'MOVIE_TITLE', 'MOVIE_RELEASE', 'MOVIE_RATING', 'MOVIE_LENGTH', 'RENTAL_COUNT',
     *                 'REVIEW_AVERAGE_STARS', 'GENRE_IDS', 'GENRE_NAMES', 'IN_WATCHLIST', 'IS_RENTED'
     */
    public static function getTopTenMovies(int $customerId, PDO $conn): array
    {

        $query = "SELECT m.MOVIE_ID,
                         m.MOVIE_TITLE,
                         m.MOVIE_RELEASE,
                         m.MOVIE_RATING,
                         m.MOVIE_LENGTH,
                         COUNT(r.COPY_ID)                    AS RENTAL_COUNT,
                         AVG(r2.REVIEW_STARS)                 AS REVIEW_AVERAGE_STARS,
                         GROUP_CONCAT(DISTINCT g.GENRE_ID)   AS GENRE_IDS,
                         GROUP_CONCAT(DISTINCT g.GENRE_NAME) AS GENRE_NAMES,
                         EXISTS (SELECT 1 FROM WATCH_LIST wl WHERE wl.MOVIE_ID = m.MOVIE_ID AND wl.CUSTOMER_ID = :customerId) AS IN_WATCHLIST,
                         EXISTS (SELECT 1 FROM RENTAL r WHERE r.COPY_ID = c.COPY_ID AND r.CUSTOMER_ID = :customerId AND r.RENTAL_RETURNED IS NULL) AS IS_RENTED
                  FROM MOVIE m
                           JOIN COPY c ON m.MOVIE_ID = c.MOVIE_ID
                           JOIN RENTAL r ON c.COPY_ID = r.COPY_ID
                           JOIN MOVIE_GENRE mg ON m.MOVIE_ID = mg.MOVIE_ID
                           JOIN GENRE g ON mg.GENRE_ID = g.GENRE_ID
                           LEFT JOIN REVIEW r2 ON m.MOVIE_ID = r2.MOVIE_ID
                  GROUP BY m.MOVIE_ID
                  ORDER BY RENTAL_COUNT DESC
                  LIMIT 10;";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    /**
     * Returns the details of a movie by its id, including the movie id, title, release date, maturity rating, runtime,
     * director id, director name, average review stars, comma separated genre ids, comma separated genre names, and
     * comma separated actor names.
     *
     * @param int $movieId
     * @param int $customerId
     * @param PDO $conn
     * @return array 'MOVIE_ID', 'MOVIE_TITLE', 'MOVIE_RELEASE', 'MOVIE_RATING', 'MOVIE_LENGTH', 'MOVIE_OVERVIEW',
     *               'MOVIE_BACKDROP', 'MOVIE_POSTER' 'DIRECTOR_ID', 'DIRECTOR_NAME', 'REVIEW_AVERAGE_STARS',
     *               'GENRE_IDS', 'GENRE_NAMES', 'ACTOR_NAMES'
     */
    public static function getMovieDetailsByMovieId(int $movieId, int $customerId, PDO $conn): array
    {

        $query = "SELECT m.MOVIE_ID,
                           m.MOVIE_TITLE AS MOVIE_TITLE,
                           m.MOVIE_BACKDROP AS MOVIE_BACKDROP,
                           STRFTIME('%Y', m.MOVIE_RELEASE) AS MOVIE_YEAR,
                           printf('%d hr %d min', m.MOVIE_LENGTH / 60, m.MOVIE_LENGTH % 60) AS MOVIE_RUNTIME,
                           m.MOVIE_RATING AS MOVIE_RATING,
                           m.MOVIE_OVERVIEW AS MOVIE_OVERVIEW,
                           GROUP_CONCAT(DISTINCT g.GENRE_NAME) AS GENRES,
                           GROUP_CONCAT(DISTINCT a.ACTOR_NAME) AS ACTORS,
                           d.DIRECTOR_NAME AS DIRECTOR_NAME,
                           CASE WHEN EXISTS (SELECT 1 FROM WATCH_LIST wl WHERE wl.MOVIE_ID = m.MOVIE_ID AND wl.CUSTOMER_ID = :customerId) THEN 1 ELSE 0 END AS IN_WATCHLIST,
                           CASE WHEN EXISTS (SELECT 1 FROM RENTAL r WHERE r.COPY_ID = co.COPY_ID AND r.CUSTOMER_ID = :customerId AND r.RENTAL_RETURNED IS NULL) THEN 1 ELSE 0 END AS IS_RENTED,
                           CASE WHEN NOT EXISTS (SELECT 1 FROM RENTAL re3 WHERE re3.COPY_ID = co.COPY_ID AND re3.RENTAL_RETURNED IS NULL) THEN 1 ELSE 0 END AS IS_AVAILABLE,
                           co.COPY_ID AS COPY_ID
                    FROM MOVIE m
                             JOIN MOVIE_GENRE mg ON m.MOVIE_ID = mg.MOVIE_ID
                             JOIN GENRE g ON mg.GENRE_ID = g.GENRE_ID
                             JOIN ROLE r ON m.MOVIE_ID = r.MOVIE_ID
                             JOIN ACTOR a ON r.ACTOR_ID = a.ACTOR_ID
                             JOIN DIRECTOR d ON m.DIRECTOR_ID = d.DIRECTOR_ID
                             JOIN COPY co ON m.MOVIE_ID = co.MOVIE_ID
                             LEFT JOIN WATCH_LIST w ON m.MOVIE_ID = w.MOVIE_ID AND w.CUSTOMER_ID = :customerId
                             LEFT JOIN RENTAL re ON co.COPY_ID = re.COPY_ID AND re.RENTAL_RETURNED IS NULL AND re.CUSTOMER_ID = :customerId
                    WHERE m.MOVIE_ID = :movieId
                    GROUP BY m.MOVIE_ID;";

        $stmt = $conn->prepare($query);
        $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
        $stmt->bindValue(':customerId', $customerId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }

    public static function getFeaturedMoviesList(PDO $conn) : array
    {
        $query = "SELECT m.MOVIE_ID, m.MOVIE_TITLE, m.MOVIE_POSTER
                  FROM MOVIE m
                           JOIN REVIEW r ON m.MOVIE_ID = r.MOVIE_ID
                  GROUP BY m.MOVIE_ID
                  ORDER BY AVG(r.REVIEW_STARS) DESC
                  LIMIT 7;";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPopularMoviesList(PDO $conn): array
    {
        $query = "SELECT m.MOVIE_ID, m.MOVIE_TITLE, m.MOVIE_POSTER
                  FROM MOVIE m
                           JOIN COPY c ON m.MOVIE_ID = c.MOVIE_ID
                           JOIN RENTAL r ON c.COPY_ID = r.COPY_ID
                  GROUP BY m.MOVIE_ID
                  ORDER BY COUNT(*) DESC
                  LIMIT 7;";
        $stmt = $conn->query($query);
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMoviesListByGenre(string $genreName, PDO $conn): array
    {
        $query = "SELECT m.MOVIE_ID, m.MOVIE_TITLE, m.MOVIE_POSTER
              FROM MOVIE m
              JOIN MOVIE_GENRE mg ON m.MOVIE_ID = mg.MOVIE_ID
              JOIN GENRE g ON mg.GENRE_ID = g.GENRE_ID
              WHERE g.GENRE_NAME = :genreName
              GROUP BY m.MOVIE_ID, m.MOVIE_POSTER
              ORDER BY RANDOM() -- SQLite doesn't support MD5; RANDOM() gives a shuffle
              LIMIT 7;";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':genreName', $genreName, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getMoviesByQuery(string $q, PDO $conn): array
    {
        $query = "SELECT DISTINCT m.MOVIE_ID, m.MOVIE_TITLE, m.MOVIE_POSTER
              FROM MOVIE m
              JOIN MOVIE_GENRE mg ON m.MOVIE_ID = mg.MOVIE_ID
              JOIN GENRE g ON mg.GENRE_ID = g.GENRE_ID
              JOIN ROLE r ON m.MOVIE_ID = r.MOVIE_ID
              JOIN ACTOR a ON r.ACTOR_ID = a.ACTOR_ID
              WHERE LOWER(g.GENRE_NAME) LIKE :query
                 OR LOWER(m.MOVIE_TITLE) LIKE :query
                 OR LOWER(a.ACTOR_NAME) LIKE :query
              ORDER BY
                  CASE
                      WHEN LOWER(g.GENRE_NAME) LIKE :queryExact OR LOWER(m.MOVIE_TITLE) LIKE :queryExact OR LOWER(a.ACTOR_NAME) LIKE :queryExact THEN 1
                      WHEN LOWER(g.GENRE_NAME) LIKE :querySpace OR LOWER(m.MOVIE_TITLE) LIKE :querySpace OR LOWER(a.ACTOR_NAME) LIKE :querySpace THEN 2
                      ELSE 3
                  END
              LIMIT 7;";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':query', "%$q%", PDO::PARAM_STR);
        $stmt->bindValue(':queryExact', "$q%", PDO::PARAM_STR);
        $stmt->bindValue(':querySpace', "% $q%", PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getSuggestedMovies(int $movieId, PDO $conn): array
    {
        $query = "SELECT
                  m.MOVIE_ID,
                  m.MOVIE_POSTER,
                  m.MOVIE_TITLE,
                  COUNT(DISTINCT mg.GENRE_ID) AS MATCH_COUNT,
                  AVG(r.REVIEW_STARS) AS AVERAGE_REVIEW
              FROM MOVIE m
              JOIN MOVIE_GENRE mg ON m.MOVIE_ID = mg.MOVIE_ID
              JOIN REVIEW r ON m.MOVIE_ID = r.MOVIE_ID
              WHERE mg.GENRE_ID IN (
                    SELECT mg2.GENRE_ID
                    FROM MOVIE_GENRE mg2
                    WHERE mg2.MOVIE_ID = :movieId
              )
              AND m.MOVIE_ID != :movieId
              GROUP BY m.MOVIE_ID, m.MOVIE_TITLE
              ORDER BY MATCH_COUNT DESC, AVERAGE_REVIEW DESC
              LIMIT 7;";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public static function getPosterURLByMovieId(int $movieId, PDO $conn): string
    {
        $query = "SELECT MOVIE_POSTER FROM MOVIE WHERE MOVIE_ID = :movieId;";
        $stmt = $conn->prepare($query);
        $stmt->bindValue(':movieId', $movieId, PDO::PARAM_INT);
        $stmt->execute();
        $row = $stmt->fetch(PDO::FETCH_ASSOC);
        return $row['MOVIE_POSTER'];
    }
}