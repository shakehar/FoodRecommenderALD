<?php

/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */

/**
 * Description of pearsonR
 *
 * @author Shashank
 */
include 'globalConsts.php';

class pearsonR {

    public static function insertUserR($userA, $userB, $R) {

        $query = "INSERT INTO user_user_r_taste(USER_1_ID,USER_2_ID,R) values (" . $userA . "," . $userB . "," . $R . ")";
        mysql_connect(SERVER_ADDRESS, USER, PASS);


        if (mysqli_connect_errno()) {
            //echo "Could not connect to database";

            $response["success"] = 0;
            $response["message"] = "Failed to connect to database";
            echo json_encode($response);
            exit;
        }

        mysql_select_db(DATABASE);

        $result = mysql_query($query);
    }

    public static function calculatePearson($userA, $userB) {
        $sumA = 0;
        $sumB = 0;
        $sqSumA = 0;
        $sqSumB = 0;
        $pSum = 0;
        foreach ($userA as $key => $valueA) {
            $valueB = $userB[$key];

            $sumA+=$valueA;
            $sumB+=$valueB;

            $sqSumA += pow($valueA, 2);
            $sqSumB += pow($valueB, 2);

            $pSum+= $valueA * $valueB;
        }
        $num = $pSum - ($sumA * $sumB / count($userA));

        $den = sqrt(($sqSumA - pow($sumA, 2)/count($userA)) * ($sqSumB - pow($sumB, 2)/count($userA)));

        if ($den == 0) {
            return 0;
        }
        return $num / $den;
    }

    // pattern for result  0:user 1  1:user 2    2:foodId    3:rating 1  4:rating 2
    public static function generateDictionary($result) {

        $row = mysql_fetch_array($result);
        $userA = $row[0];
        $userB = $row[1];
        $ratingA = array();
        $ratingB = array();
        $ratingA[$row[2]] = $row[3];
        $ratingB[$row[2]] = $row[4];
        $R;
        while ($row = mysql_fetch_array($result)) {
            if ($row[0] == $userA && $userB == $row[1]) {
                $ratingA[$row[2]] = $row[3];
                $ratingB[$row[2]] = $row[4];
            } else {
                $R = self::calculatePearson($ratingA, $ratingB);
                self::insertUserR($userA, $userB, $R);
                $ratingA = array();
                $ratingB = array();
                if ($row[0] != $userA)
                    $userA = $row[0];
                if ($userB != $row[1])
                    $userB = $row[1];
            }
        }
    }

    //put your code here
    public static function getDataset() {

        mysql_connect(SERVER_ADDRESS, USER, PASS);

        $query = "select a.UR_UserID,b.UR_UserID ,a.UR_FNM_ID,a.UR_Rating,b.UR_Rating "
                . " from user_rating a , user_rating b"
                . " where a.ur_fnm_id = b.ur_fnm_id"
                . " and a.UR_id < b.UR_id"
                . " order by a.UR_UserID,b.UR_UserID";
        if (mysqli_connect_errno()) {
            //echo "Could not connect to database";

            $response["success"] = 0;
            $response["message"] = "Failed to connect to database";
            echo json_encode($response);
            exit;
        }

        mysql_select_db(DATABASE);
        $result = mysql_query($query);

        self::generateDictionary($result);
    }

}

?>
