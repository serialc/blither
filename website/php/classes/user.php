<?php
// Filename: user.php
// Purpose: handle all user related tasks

namespace frakturmedia\blither;

# Constants
require_once '../php/classes/db.php';

class User
{
    # define private variables here
    private $db;

    private $id;
    private $username;
    private $email;
    private $status;
    private $password_hash;

    # constructor
    public function __construct()
    {
        $this->db = new DataBaseConnection();
    }

    public function __destruct()
    {
        unset($this->db);
    }

    public function getId()
    {
        if (!isset($this->id)) {
            return false;
        }
        return $this->id;
    }

    public function getName()
    {
        if (!isset($this->username)) {
            return false;
        }
        return $this->username;
    }

    public function setName($new_username) {
        // if existing name is the same as the new, then just return true
        if ($this->username === $new_username) {
            return true;
        }

        // check it doesn't exist already
        if ($this->db->userExists($username)) {
            return false;
        }

        // ok, overwrite username in object (not yet in db)
        $this->username = $new_username;
    }

    public function getEmail()
    {
        return $this->email;
    }

    public function getStatus()
    {
        if (!isset($this->status)) {
            return false;
        }
        return $this->status;
    }

    public function setStatus($new_status)
    {
        if (!is_int($new_status)) {
            global $log;
            $log->error("Tried to update status for " . $this->username . " to " . $new_status . " but it is not an integer value.");
            return false;
        }
        $this->status = $new_status;
    }

    public function create($username, $email, $status, $pwd): bool
    {
        $email_lwr = strtolower($email);
        $hashed_pw = $this->hashPassword($pwd);

        if ($this->db->userExists($username)) {
            echo "Username already exists";
            return false;
        } else {
            $this->db->addUser($username, $email_lwr, $status, $hashed_pw);
            $uid = $this->getIdFromEmail($email_lwr);
            $this->loadFromUid($uid);
            return true;
        }
    }

    public function updatePassword($pw)
    {
        $this->password_hash = $this->hashPassword($pw);
    }

    private function hashPassword($pw)
    {
        return password_hash(
            $pw,
            PASSWORD_DEFAULT,
            ['cost' => PASSWORD_HASH_COST]
        );
    }

    // is passed the attributes in an associative array converted to properties
    private function populate($details)
    {
        $this->id = $details['uid'];
        $this->username = $details['username'];
        $this->email = $details['email'];
        $this->status = $details['status'];
        $this->password_hash = $details['password'];
    }

    private function getType()
    {
        // status is an int of range 0-255
        if ($this->status > MEMBER_STATUS_ADMIN) {
            return "Administrator";
        }
        if ($this->status > MEMBER_STATUS_EDITOR) {
            return "Editor";
        }
        if ($this->status > MEMBER_STATUS_MODERATOR) {
            return "Moderator";
        }
        if ($this->status > MEMBER_STATUS_CONTRIBUTOR) {
            return "Contributor";
        }
    }

    public function verify($email, $pw): bool
    {
        $email_lwr = strtolower($email);
        $user_row = $this->db->retrieveUserFromEmail($email_lwr);

        if ($user_row === false) {
            return false;
        }

        if (password_verify($pw, $user_row['password'])) {
            $this->populate($user_row);

            $pw_needs_rehash = password_needs_rehash(
                $this->password_hash,
                PASSWORD_DEFAULT,
                ['cost' => PASSWORD_HASH_COST]
            );
            if ($pw_needs_rehash) {
                $this->password_hash = $this->hashPassword($pw);
                // update password
                $this->save();
            }
            return true;
        }
        return false;
    }

    public function loadFromUid($uid): bool
    {
        $user_row = $this->db->retrieveUserFromUid($uid);

        if ($user_row !== false) {
            $this->populate($user_row);
            return true;
        }
        return false;
    }

    public function save(): bool
    {
        return $this->db->updateUser(
            $this->username,
            $this->institute,
            $this->email,
            $this->status,
            $this->password_hash,
            $this->id,
            $this->ntf_newreg,
            $this->ntf_newoer,
            $this->ntf_newqry,
            $this->ntf_perqry,
            $this->ntf_oermod
        );
    }

    public function update(): bool
    {
        if ($this->save()) {
            $this->loadFromUid($this->id);
            return true;
        }
        return false;
    }

    public function getIdFromEmail($email)
    {
        $email_lwr = strtolower($email);
        return $this->db->getUseridFromEmail($email_lwr);
    }

    public function resetPassword($code, $pw)
    {
        // check the code is in the code table
        // get the uid
        $uid = $this->db->getUidFromPasswordCode($code);

        if ($uid !== false) {

            require_once('../php/classes/user.php');
            // create a User object
            $temp_user = new User();
            $temp_user->loadFromUid($uid);
            // update the password for the user with that uid
            $temp_user->updatePassword($pw);
            // update pw to db
            $temp_user->save();

            // delete code from code table for the user
            $db->deleteUserCode($code);
            return true;
        }
        return false;
    }

    public function generateUserPasswordResetCode($email)
    {
        $uid = $this->db->getUserIdFromEmail($email);

        if ( $uid !== false ) {
            // returns a code or false
            return($db->createOrUpdateUserPasswordResetCode($uid));
        }
        return false;
    }

    public function count()
    {
        return $this->db->getNumberOfUsers();
    }
}

// EOF
