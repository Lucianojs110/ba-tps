<?php
//use File;

    //check if the user is Admin//
    function isAdmin($user) {
        if ($user->roles[0]->id==1) {
                return true;
            }
    }
    //check if the user is designer//
    function isUser($user) {

        if ($user->roles[0]->id==2) {
                return true;
            }
    }


    function AllRoles($user) {

        if ($user->roles[0]->id==2 or $user->roles[0]->id==1) {
            return true;
        }
    }

    function get_simulated_data() {
        $data = array();
        return $data;
    }

?>