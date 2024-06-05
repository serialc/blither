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

    public function getCourse($cid)
    {
        return $this->db->getCourse($cid);
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

    public function displayCid( $cid )
    {
        global $user;

        $course = $this->getCourse($cid);

        // check that something is returned
        if ( isset($course['cid']) ) {

            echo '<h1>' . $course['name'];
            echo '<a href="/play/' . $course['cid'] . '"><button class="btn btn-success m-1 float-end">Play</button></a>';
            if ( $user->getName() ) {
                echo '<a href="/courses/edit/' . $course['cid'] . '"><button class="btn btn-success m-1 float-end">Edit</button></a>';
            }
            echo '</h1>';
            echo "<div>" . $course['location'] . ' (<a href="/courses/country/' . $course['country_code'] . '">' . $course['country_code'] . "</a>)</div>";
            echo "<div>" . $course['description'] . "</div>";

        } else {
            echo "<h1>Course not found</h1>";
        }
    }

    public function submitNew( $name, $location, $country, $description, $baskets ) {
        return $this->db->submitNewCourse( $name, $location, $country, $description, $baskets );
    }
}
