<?php

// Filename: php/classes/db.php
// Purpose: Handles all DB access

namespace frakturmedia\blither;

use Datetime;
use PDO;

class DataBaseConnection
{
    # define private variables here
    private $conn;

    # constructor
    public function __construct()
    {
        // create connection, MySQL setup
        try {
            $this->conn = new PDO(
                'mysql:host=' . DB_SERVER . ';dbname=' . DB_NAME . ';charset=utf8',
                DB_USERNAME,
                DB_PASS,
                [PDO::MYSQL_ATTR_INIT_COMMAND =>"SET NAMES utf8;SET time_zone = '" . TIMEZONE . "'"]
            );
            //$this->conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            //echo "MySQL driver name:<br>";
            //echo $this->conn->getAttribute(PDO::ATTR_DRIVER_NAME);
            //echo ' (' . $this->conn->getAttribute(PDO::ATTR_CLIENT_VERSION) . ')<br>';
        } catch (PDOException $e) {
            // Database connection failed
            echo "Database connection failed" and die;
        }
    }

    public function __destruct()
    {
        # php will close the db connection automatically when the process ends
        $this->conn = null;
        //mysqli_close($this->conn);
    }

    public function checkTZ()
    {
        //$this->conn->exec("SET time_zone = '" . date('P') . "'");
        $sql = "SELECT @@global.time_zone, @@session.time_zone";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        print_r($stmt->fetchAll(PDO::FETCH_ASSOC));
    }

    public function getNumberOfUsers()
    {
        $sql = "SELECT COUNT(*) AS unumber FROM users";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute();
        if ($result) {
            $usernum = (int) $stmt->fetchAll(PDO::FETCH_ASSOC)[0]['unumber'];
            return $usernum;
        } else {
            error_log(print_r($this->conn->errorInfo(), true));
            error_log("Failed to determine number of users") and die;
        }
    }

    public function listUsers()
    {
        $sql = "SELECT username, usertype, status FROM " . TABLE_USERS . "";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function retrieveUserFromUid($uid)
    {
        $sql = "SELECT * FROM " . TABLE_USERS . " WHERE uid=?";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$uid]);

        // if successfull and there is a user with this username
        if ($result && $stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function retrieveUserFromEmail($email)
    {
        $sql = "SELECT * FROM " . TABLE_USERS . " WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$email]);

        // if successfull and there is a user with this username
        if ($result && $stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC);
        }
        return false;
    }

    public function userExists($uname)
    {
        $sql = "SELECT COUNT(*) as number FROM " . TABLE_USERS .
           " WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$uname]);
        if ($stmt->fetchAll(PDO::FETCH_ASSOC)[0]['number'] == 1) {
            return true;
        } else {
            return false;
        }
    }

    public function updateUser($uname, $institute, $email, $status, $pwd, $uid, $ntf_nr, $ntf_no, $ntf_nq, $ntf_pq, $ntf_om)
    {
        $sql = "UPDATE " . TABLE_USERS .
            " SET username=?, institute=?, email=?, status=?, password=?, ntf_newreg=?, ntf_newoer=?, ntf_newqry=?, ntf_perqry=?, ntf_oermod=? WHERE uid=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$uname, $institute, $email, $status, $pwd, $ntf_nr, $ntf_no, $ntf_nq, $ntf_pq, $ntf_om, $uid]);
    }

    public function changeUserStatus($uid, $status)
    {
        $sql = "UPDATE " . TABLE_USERS .
            " SET status=? WHERE uid=?";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$status, $uid]);
    }

    public function addUser($uname, $email, $status, $pwd)
    {
        $sql = "INSERT INTO " . TABLE_USERS .
            " (username, email, password, status)" .
            " VALUES (?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$uname, $email, $pwd, $status]);
    }

    public function addRegistrant($n, $i, $pu, $e, $c)
    {
        $sql = "INSERT INTO " . TABLE_WAITLIST .
            " (name, institute, profile_url, email, code)" .
            " VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        // return boolean
        return $stmt->execute([$n, $i, $pu, $e, $c]);
    }

    public function getUsernameFromEmail($email)
    {
        $sql = "SELECT username FROM " . TABLE_USERS . " WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$email]);

        if ($result && $stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['username'];
        }
        return false;
    }

    public function getUserIdFromEmail($email)
    {
        $sql = "SELECT uid FROM " . TABLE_USERS . " WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$email]);
        if ($result && $stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['uid'];
        }
        return false;
    }

    public function getUidFromPasswordCode($code)
    {
        $sql = "SELECT uid FROM " . TABLE_CODES . " WHERE purpose='pw' AND code=?";
        $stmt = $this->conn->prepare($sql);
        $result = $stmt->execute([$code]);
        if ($result && $stmt->rowCount() === 1) {
            return $stmt->fetch(PDO::FETCH_ASSOC)['uid'];
        }
        return false;
    }

    public function createOrUpdateUserPasswordResetCode($uid)
    {
        // generate the random code to insert
        $code = getRandomCode(128);

        $sql = "INSERT INTO " . TABLE_CODES . " (uid, purpose, code) VALUES (?,'pw',?) " .
           "ON DUPLICATE KEY UPDATE code=?";
        $stmt = $this->conn->prepare($sql);

        // execute
        if ($stmt->execute([$uid, $code, $code])) {
            // if successfull return the code for the email
            return $code;
        }
        return false;
    }

    public function checkExistenceOfEmail($email)
    {
        $sql = "SELECT COUNT(*) AS count FROM " . TABLE_USERS . " WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
            return true;
        }

        $sql = "SELECT COUNT(*) AS count FROM " . TABLE_WAITLIST . " WHERE email=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$email]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
            return true;
        }
        return false;
    }

    public function checkExistenceOfUsername($username)
    {
        $sql = "SELECT COUNT(*) AS count FROM " . TABLE_USERS . " WHERE username=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
            return true;
        }

        $sql = "SELECT COUNT(*) AS count FROM " . TABLE_WAITLIST . " WHERE name=?";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$username]);
        if ($stmt->fetch(PDO::FETCH_ASSOC)['count'] > 0) {
            return true;
        }
        return false;
    }

    public function getValidGeneralContactQueriesCount ()
    {
        // we only want the queries where the email has been validated (ccode is 1) and this is not in relation to a specific OER
        $sql = "SELECT count(*) AS count FROM " . TABLE_CONTACT . " WHERE ccode=1 AND ctype=" . CONTACT_TYPE_GENERAL;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getValidOverallQueriesCount ($uid)
    {
        $sql = "SELECT count(*) AS count FROM " . TABLE_CONTACT . " WHERE ccode=1 AND (ctype=" . CONTACT_TYPE_GENERAL . " OR ruid=?)";
        $stmt = $this->conn->prepare($sql);
        $stmt->execute([$uid]);
        return $stmt->fetch(PDO::FETCH_ASSOC)['count'];
    }

    public function getValidGeneralContactQueries ()
    {
        // we only want the queries where the email has been validated (ccode is 1) and this is not in relation to a specific OER
        $sql = "SELECT * FROM " . TABLE_CONTACT . " WHERE ccode=1 AND ctype=" . CONTACT_TYPE_GENERAL;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $queries = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($queries, $row);
        }
        return $queries;
    }
    public function getCoursesSummary()
    {
        $sql = "SELECT * FROM " . TABLE_COURSES;
        $stmt = $this->conn->prepare($sql);
        $stmt->execute();
        $courses = [];
        while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
            array_push($courses, $row);
        }
        return $courses;
    }

    public function submitNewCourse( $name, $location, $country, $description, $baskets)
    {
        $sql = "INSERT INTO " . TABLE_COURSES . " (name, location, country_code, description, baskets) VALUES (?,?,?,?,?)";
        $stmt = $this->conn->prepare($sql);
        return $stmt->execute([$name, $location, $country, $description, $baskets]);
    }
}

// EOF
