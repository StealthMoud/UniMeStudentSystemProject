<?php

namespace App\databases\neo4j;

use App\config\Database;
use Exception;
use Laudis\Neo4j\Contracts\ClientInterface;
use mysqli;

class Mysql_to_neo4j
{
    protected ?mysqli $mysql_connn;
    protected ?ClientInterface $neo4j_connn;

    /**
     * @throws Exception
     */
    public function __construct()
    {
        $database = new Database();
        $this->mysql_connn = $database->getMysqlConnection();
        $this->neo4j_connn = $database->getNeo4jConnection();
    }

    /**
     * Fetches data from MySQL courses and majors tables.
     */
    public function fetchCoursesAndMajorsFromMySQL(): array
    {
        $data = [
            'courses' => [],
            'majors' => []
        ];

        // Query to fetch courses data
        $coursesQuery = "SELECT * FROM courses";
        $coursesResult = $this->mysql_connn->query($coursesQuery);

        if ($coursesResult) {
            while ($row = $coursesResult->fetch_assoc()) {
                $data['courses'][] = $row;
            }
        } else {
            die("Courses query failed: " . $this->mysql_connn->error);
        }

        // Query to fetch majors data
        $majorsQuery = "SELECT * FROM majors";
        $majorsResult = $this->mysql_connn->query($majorsQuery);

        if ($majorsResult) {
            while ($row = $majorsResult->fetch_assoc()) {
                $data['majors'][] = $row;
            }
        } else {
            die("Majors query failed: " . $this->mysql_connn->error);
        }

        return $data;
    }


    /**
     * Inserts courses and majors data into Neo4j.
     */
    public function insertCoursesAndMajorsIntoNeo4j(array $data): void
    {
        // Insert majors first
        foreach ($data['majors'] as $major) {
            $query = "CREATE (m:Major {id: \$id, name: \$name, level: \$level, description: \$description})";
            $parameters = [
                'id' => $major['id'],
                'name' => $major['name'],
                'level' => $major['level'],
                'description' => $major['description']
            ];

            try {
                $this->neo4j_connn->run($query, $parameters);
            } catch (Exception $e) {
                echo "Neo4j major insertion error: " . $e->getMessage();
            }
        }

        // Insert courses and create relationships to majors
        foreach ($data['courses'] as $course) {
            $query = "CREATE (c:Course {id: \$id, name: \$name, level: \$level, credits: \$credits, description: \$description, schedule: \$schedule})";
            $parameters = [
                'id' => $course['id'],
                'name' => $course['name'],
                'level' => $course['level'],
                'credits' => $course['credits'],
                'description' => $course['description'],
                'schedule' => $course['schedule']
            ];

            try {
                $this->neo4j_connn->run($query, $parameters);

                // Create relationship to major if major_id is present
                if (!empty($course['major_id'])) {
                    $relQuery = "MATCH (c:Course {id: \$course_id}), (m:Major {id: \$major_id}) CREATE (c)-[:BELONGS_TO]->(m)";
                    $relParameters = [
                        'course_id' => $course['id'],
                        'major_id' => $course['major_id']
                    ];

                    try {
                        $this->neo4j_connn->run($relQuery, $relParameters);
                    } catch (Exception $e) {
                        echo "Neo4j relationship creation error: " . $e->getMessage();
                    }
                }
            } catch (Exception $e) {
                echo "Neo4j course insertion error: " . $e->getMessage();
            }
        }
    }

}
