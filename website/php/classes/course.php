<?php
// Filename: course.php
// Purpose: handle all disc golf course related tasks

namespace frakturmedia\blither;

class Course
{
    # define private variables here
    private $db;

    public function __construct()
    {
        $this->db = new DataBaseConnection();
    }

    public function __destruct()
    {
        unset($this->db);
    }

    public function getCourses()
    {
        return $this->db->getCoursesSummary();
    }

    public function displayList()
    {
        global $user;
        $courses = $this->getCourses();

        echo '<div class="row">';
        foreach ($courses as $c) {
            echo '<div class="col-lg-4 col-md-6 mb-4"><div class="course rounded-2">';
            if ($user->getStatus() >= MEMBER_STATUS_CREATOR) {
                echo '<a href="/courses/edit/' . $c['cid'] . '">';
                echo '<button class="btn btn-success m-1 float-end">Edit</button></a>';
            }
            echo '<a href="/courses/detail/' . $c['cid'] . '">';
            echo '<h2>' . $c['name'] . '</h2>';
            echo '<div>' . $c['location'] . ', ' . $c['country_code'] . '</div>';
            echo '<div>Baskets: ' . $c['baskets'] . '</div>';
            echo '<div>' . $c['description'] . '</div>';
            echo '</a>';
            echo '</div></div>';
        }
        echo '</div>';
    }

    public function displayCid( $cid ) {
        echo "display course id=$cid in php/courses.php";
    }

    public function submitNew( $name, $location, $country, $description, $baskets ) {
        return $this->db->submitNewCourse( $name, $location, $country, $description, $baskets );
    }
}
