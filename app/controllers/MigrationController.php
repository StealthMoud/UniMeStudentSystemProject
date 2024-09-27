<?php

namespace App\controllers;

use App\databases\neo4j\Mysql_to_neo4j;
use App\core\Controller;

class MigrationController extends Controller
{
    public function migrate(): void
    {
        $migration = new Mysql_to_neo4j();
        $courses = $migration->fetchCoursesAndMajorsFromMySQL();
        $migration->insertCoursesAndMajorsIntoNeo4j($courses);

        echo "Courses migration from MySQL to Neo4j completed.\n";
    }
}
